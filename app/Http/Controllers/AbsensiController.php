<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Potongan;
use App\Models\RekapGaji;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AbsensiController extends Controller
{

    public function index()
    {
        $karyawan = User::where('role', 'karyawan')->where('status', 0)->get();
        return view('absensi.index', compact('karyawan'));
    }
    public function prosesRekapGaji(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');

        $users = User::where('role', 'karyawan')->where('status', 0)->get();

        foreach ($users as $user) {
            $jabatan = $user->jabatan;

            $absensi = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', date('m', strtotime($bulan)))
                ->whereYear('tanggal', date('Y', strtotime($bulan)))
                ->get();

            $hari_kerja = $absensi->count();
            $jumlah_lembur = $absensi->where('lembur', true)->count();

            $potongan = Potongan::where('user_id', $user->id)
                ->whereMonth('tanggal', date('m', strtotime($bulan)))
                ->whereYear('tanggal', date('Y', strtotime($bulan)))
                ->sum('jumlah');

            $gaji_pokok = $hari_kerja * $jabatan->honor_harian;
            $gaji_lembur = $jumlah_lembur * $jabatan->honor_lembur;
            $gaji_bersih = $gaji_pokok + $gaji_lembur - $potongan;

            RekapGaji::updateOrCreate(
                ['user_id' => $user->id, 'bulan' => $bulan],
                [
                    'total_hari_kerja' => $hari_kerja,
                    'total_lembur' => $jumlah_lembur,
                    'total_potongan' => $potongan,
                    'gaji_pokok' => $gaji_pokok,
                    'gaji_lembur' => $gaji_lembur,
                    'gaji_bersih' => $gaji_bersih
                ]
            );

            // Kirim email gaji ke karyawan
            Mail::to($user->email)->send(new GajiBulananMail($user, $bulan, $gaji_bersih));
        }

        return back()->with('success', 'Rekap gaji berhasil diproses.');
    }
    public function data(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $data = Absensi::with('user')->where('tanggal', $tanggal)->get();

        return response()->json($data);
    }
    public function store(Request $request)
    {
        $tanggal = $request->tanggal;
        $dataAbsen = $request->absen;

        DB::beginTransaction();
        try {
            foreach ($dataAbsen as $userId => $absen) {
                Absensi::updateOrCreate(
                    ['user_id' => $userId, 'tanggal' => $tanggal],
                    [
                        'hadir' => isset($absen['hadir']),
                        'lembur' => isset($absen['lembur']),
                        'keterangan' => $absen['keterangan'] ?? null,
                    ]
                );
            }
            DB::commit();
            return response()->json(['message' => 'Absensi berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan absensi.'], 500);
        }
    }
}
