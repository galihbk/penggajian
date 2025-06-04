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
    public function check(Request $request)
    {
        $tanggal = $request->tanggal;

        $exists = Absensi::where('tanggal', $tanggal)->exists();

        return response()->json(['exists' => $exists]);
    }
}
