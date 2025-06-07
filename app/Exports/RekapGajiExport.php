<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Potongan;
use Maatwebsite\Excel\Concerns\FromView;

class RekapGajiExport implements FromView
{
    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $karyawan = User::with('jabatan')
            ->where('role', '!=', 'owner')
            ->where('status', 0)
            ->get();

        $data = $karyawan->map(function ($k) {
            $hadir = Absensi::where('user_id', $k->id)
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->where('hadir', true)
                ->count();

            $lembur = Absensi::where('user_id', $k->id)
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->where('lembur', true)
                ->count();

            $potongan = Potongan::where('user_id', $k->id)
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->sum('jumlah');

            $honorHarian = $k->jabatan->honor_harian ?? 0;
            $honorLembur = $k->jabatan->honor_lembur ?? 0;

            $gajiHarian = $hadir * $honorHarian;
            $gajiLembur = $lembur * $honorLembur;
            $total = $gajiHarian + $gajiLembur - $potongan;

            return [
                'nama' => $k->name,
                'jabatan' => $k->jabatan->nama_jabatan ?? '-',
                'hadir' => $hadir,
                'lembur' => $lembur,
                'honor_harian' => $honorHarian,
                'honor_lembur' => $honorLembur,
                'potongan' => $potongan,
                'total' => $total,
            ];
        });

        return view('exports.rekap_gaji', [
            'data' => $data,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
