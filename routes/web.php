<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\PotonganController;
use App\Http\Controllers\RekapGajiController;
use App\Exports\RekapGajiExport;
use App\Http\Controllers\KaryawanController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Potongan;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    $bulan = Carbon::now()->month;
    $tahun = Carbon::now()->year;

    if ($user->role === 'karyawan') {
        $hadir = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('hadir', true)
            ->count();

        $lembur = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('lembur', true)
            ->count();

        $potongan = Potongan::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $honorHarian = $user->jabatan->honor_harian ?? 0;
        $honorLembur = $user->jabatan->honor_lembur ?? 0;

        $gajiHarian = $hadir * $honorHarian;
        $gajiLembur = $lembur * $honorLembur;
        $gajiTotal = $gajiHarian + $gajiLembur - $potongan;

        $totalHariKerja = collect(range(1, Carbon::now()->daysInMonth))
            ->map(function ($day) use ($bulan, $tahun) {
                $tanggal = Carbon::create($tahun, $bulan, $day);
                return $tanggal->isWeekday() ? $tanggal : null;
            })->filter()->count();

        return view('dashboard', [
            'role' => 'karyawan',
            'gajiTotal' => $gajiTotal,
            'hadir' => $hadir,
            'totalHariKerja' => $totalHariKerja,
        ]);
    }

    if (in_array($user->role, ['admin', 'owner'])) {
        $karyawan = User::with('jabatan')
            ->where('role', '!=', 'owner')
            ->where('status', 0)
            ->get();

        $totalGajiSemua = $karyawan->sum(function ($k) use ($bulan, $tahun) {
            $hadir = Absensi::where('user_id', $k->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('hadir', true)
                ->count();

            $lembur = Absensi::where('user_id', $k->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('lembur', true)
                ->count();

            $potongan = Potongan::where('user_id', $k->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $honorHarian = $k->jabatan->honor_harian ?? 0;
            $honorLembur = $k->jabatan->honor_lembur ?? 0;

            $gajiHarian = $hadir * $honorHarian;
            $gajiLembur = $lembur * $honorLembur;

            return $gajiHarian + $gajiLembur - $potongan;
        });
        $karyawan = \App\Models\User::where('role', 'karyawan')->where('status', 0)->count();
        $absensiBulanIni = \App\Models\Absensi::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();

        $totalHadir = $absensiBulanIni->where('hadir', true)->count();
        return view('dashboard', [
            'role' => $user->role,
            'totalGaji' => $totalGajiSemua,
            'karyawan' => $karyawan,
            'totalHadir' => $totalHadir,
        ]);
    }

    return view('dashboard', [
        'role' => $user->role,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', CheckRole::class . ':karyawan'])->group(function () {
    Route::get('/riwayat-gaji', [KaryawanController::class, 'riwayatGaji'])->name('karyawan.riwayat-gaji');
    Route::get('/riwayat-gaji-karyawan', [KaryawanController::class, 'riwayatGajiKaryawan'])->name('karyawan.riwayat-gaji-karyawan');
});
Route::middleware('auth')->group(function () {
    Route::get('/rekap-gaji/print-slip', [RekapGajiController::class, 'printSlip'])->name('gaji.print-slip');
    Route::post('/ganti-password', [UserController::class, 'updatePassword'])->name('password.update');
});
Route::middleware(['auth', CheckRole::class . ':admin,owner'])->group(function () {
    Route::get('/users/jabatan', [UserController::class, 'jabatan'])->name('users.jabatan');
    Route::get('/users/data-jabatan', [UserController::class, 'dataJabatan'])->name('users.data-jabatan');
    Route::post('/users/store-jabatan', [UserController::class, 'storeJabatan'])->name('users.store-jabatan');
    Route::post('/users/jabatan/{id}', [UserController::class, 'updateJabatan']);
    Route::get('/users/karyawan', [UserController::class, 'karyawan'])->name('users.karyawan');
    Route::get('/users/data-karyawan', [UserController::class, 'dataKaryawan'])->name('users.data-karyawan');
    Route::post('/users/store-karyawan', [UserController::class, 'storeKaryawan'])->name('users.store-karyawan');
    Route::post('/users/karyawan/update/{id}', [UserController::class, 'updateKaryawan']);
    Route::get('/users/karyawan/edit/{id}', [UserController::class, 'editKaryawan']);
    Route::delete('/users/karyawan/delete/{id}', [UserController::class, 'deleteKaryawan']);

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/data', [AbsensiController::class, 'data'])->name('absensi.data');
    Route::post('/absensi/check', [AbsensiController::class, 'check'])->name('absensi.check');


    Route::get('/potongan', [PotonganController::class, 'index'])->name('potongan');
    Route::post('/potongan', [PotonganController::class, 'store'])->name('potongan.store');
    Route::delete('/potongan/{id}', [PotonganController::class, 'destroy']);

    Route::get('/rekap-gaji', [RekapGajiController::class, 'index'])->name('gaji.index');
    Route::get('/rekap-gaji/data', [RekapGajiController::class, 'getData'])->name('gaji.data');
    Route::post('/send-slip', [RekapGajiController::class, 'sendSlip'])->name('gaji.send');

    Route::delete('/users/jabatan/{id}', [UserController::class, 'deleteJabatan']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/gaji/export', function (\Illuminate\Http\Request $request) {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        return Excel::download(new RekapGajiExport($bulan, $tahun), 'rekap-gaji.xlsx');
    })->name('gaji.export');
});

require __DIR__ . '/auth.php';
