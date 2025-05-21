<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\DetailPengguna;
use Illuminate\Support\HtmlString;
use App\Models\Jabatan;
use App\Models\RekapGaji;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
}
