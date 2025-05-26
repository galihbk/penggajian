<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Potongan;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class RekapGajiController extends Controller
{

    public function index()
    {
        return view('gaji.index');
    }
    public function getData(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $karyawan = User::with('jabatan')->where('role', '!=', 'owner')->where('status', 0)->get();

        $data = $karyawan->map(function ($k) use ($bulan, $tahun) {
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
            $gajiLemburan = $lembur * $honorLembur;

            return [
                'nama' => $k->name,
                'jabatan' => $k->jabatan->nama_jabatan ?? '-',
                'hadir' => $hadir,
                'lembur' => $lembur,
                'honor_harian' => $honorHarian,
                'honor_lembur' => $honorLembur,
                'potongan' => $potongan,
                'total' => $gajiHarian + $gajiLemburan - $potongan,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'id' => $k->id,
            ];
        });

        return response()->json(['data' => $data]);
    }
    public function printSlip(Request $request)
    {
        $id_user = $request->id_user;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namaBulan = \Carbon\Carbon::createFromDate(null, (int)$bulan)->locale('id')->translatedFormat('F');
        $karyawan = User::with('jabatan')->findOrFail($id_user);

        $hadir = Absensi::where('user_id', $id_user)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('hadir', true)
            ->count();
        $lembur = Absensi::where('user_id', $id_user)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('lembur', true)
            ->count();

        $potongan = Potongan::where('user_id', $id_user)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');
        $potongans = Potongan::where('user_id', $id_user)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $honorHarian = $karyawan->jabatan->honor_harian ?? 0;
        $honorLembur = $karyawan->jabatan->honor_lembur ?? 0;

        $gajiHarian = $hadir * $honorHarian;
        $gajiLemburan = $lembur * $honorLembur;
        $total = $gajiHarian + $gajiLemburan - $potongan;

        $data = [
            'nama' => $karyawan->name,
            'id' => $karyawan->id,
            'no_hp' => $karyawan->no_hp,
            'email' => $karyawan->email,
            'jabatan' => $karyawan->jabatan->nama_jabatan ?? '-',
            'hadir' => $hadir,
            'lembur' => $lembur,
            'honor_harian' => $honorHarian,
            'honor_lembur' => $honorLembur,
            'potongan' => $potongan,
            'potongans' => $potongans,
            'total' => $total,
            'bulan' => $namaBulan,
            'tahun' => $tahun,
        ];

        return view('gaji.slip', compact('data'));
    }

    public function sendSlip(Request $request)
    {
        $id_user = $request->id_user;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $namaBulan = \Carbon\Carbon::createFromDate(null, (int)$bulan)->locale('id')->translatedFormat('F');
        $karyawan = User::with('jabatan')->findOrFail($id_user);

        $hadir = Absensi::where('user_id', $id_user)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('hadir', true)
            ->count();

        $lembur = Absensi::where('user_id', $id_user)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('lembur', true)
            ->count();

        $potongan = Potongan::where('user_id', $id_user)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $potongans = Potongan::where('user_id', $id_user)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $honorHarian = $karyawan->jabatan->honor_harian ?? 0;
        $honorLembur = $karyawan->jabatan->honor_lembur ?? 0;

        $gajiHarian = $hadir * $honorHarian;
        $gajiLemburan = $lembur * $honorLembur;
        $total = $gajiHarian + $gajiLemburan - $potongan;

        $data = [
            'nama' => $karyawan->name,
            'no_hp' => $karyawan->no_hp,
            'email' => $karyawan->email,
            'jabatan' => $karyawan->jabatan->nama_jabatan ?? '-',
            'hadir' => $hadir,
            'lembur' => $lembur,
            'honor_harian' => $honorHarian,
            'honor_lembur' => $honorLembur,
            'potongan' => $potongan,
            'potongans' => $potongans,
            'total' => $total,
            'bulan' => $namaBulan,
            'tahun' => $tahun,
        ];

        $pdf = Pdf::loadView('gaji.email-slip', ['data' => $data]);

        Mail::send('emails.slip_body', ['data' => $data], function ($message) use ($data, $pdf) {
            $message->to($data['email'], $data['nama'])
                ->subject('Slip Gaji ' . $data['bulan'] . ' ' . $data['tahun'])
                ->attachData($pdf->output(), 'Slip_Gaji_' . $data['bulan'] . '.pdf');
        });

        return back()->with('success', 'Slip berhasil dikirim ke email.');
    }
}
