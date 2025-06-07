<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Potongan;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function riwayatGaji()
    {
        return view('employes.riwayat-gaji');
    }
    public function riwayatGajiKaryawan()
    {
        $user = auth()->user();
        $bulanTahunList = Absensi::where('user_id', $user->id)
            ->selectRaw('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun')
            ->groupByRaw('MONTH(tanggal), YEAR(tanggal)')
            ->orderByRaw('YEAR(tanggal) DESC, MONTH(tanggal) DESC')
            ->get();

        $data = $bulanTahunList->map(function ($bt) use ($user) {
            $hadir = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $bt->bulan)
                ->whereYear('tanggal', $bt->tahun)
                ->where('hadir', true)
                ->count();

            $lembur = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $bt->bulan)
                ->whereYear('tanggal', $bt->tahun)
                ->where('lembur', true)
                ->count();

            $potongan = Potongan::where('user_id', $user->id)
                ->whereMonth('tanggal', $bt->bulan)
                ->whereYear('tanggal', $bt->tahun)
                ->sum('jumlah');

            $honorHarian = $user->jabatan->honor_harian ?? 0;
            $honorLembur = $user->jabatan->honor_lembur ?? 0;

            $gajiHarian = $hadir * $honorHarian;
            $gajiLemburan = $lembur * $honorLembur;

            return [
                'bulan' => $bt->bulan,
                'tahun' => $bt->tahun,
                'hadir' => $hadir,
                'lembur' => $lembur,
                'honor_harian' => $honorHarian,
                'honor_lembur' => $honorLembur,
                'potongan' => $potongan,
                'total' => $gajiHarian + $gajiLemburan - $potongan,
            ];
        });
        return response()->json(['data' => $data]);
    }
}
