<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Potongan;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

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

            $potongan = Potongan::where('user_id', $k->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $honorHarian = $k->jabatan->honor_harian ?? 0;
            $honorLembur = $k->jabatan->honor_lembur ?? 0;

            $gajiHarian = $hadir * $honorHarian;

            return [
                'nama' => $k->name,
                'jabatan' => $k->jabatan->nama_jabatan ?? '-',
                'hadir' => $hadir,
                'honor_harian' => $honorHarian,
                'honor_lembur' => $honorLembur,
                'potongan' => $potongan,
                'total' => $gajiHarian + $honorLembur - $potongan
            ];
        });

        return response()->json(['data' => $data]);
    }
}
