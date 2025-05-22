<?php

namespace App\Http\Controllers;

use App\Models\Potongan;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class PotonganController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Potongan::with('user')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('jumlah', function ($row) {
                    return 'Rp. ' . number_format($row->jumlah, 0, ',', '.');
                })
                ->addColumn('aksi', function ($row) {
                    return '<button class="btn btn-danger btn-sm btn-hapus" data-id="' . $row->id . '">Hapus</button>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $karyawan = User::where('role', '!=', 'owner')->where('status', 0)->get();
        return view('potongan.index', compact('karyawan'));
    }
    public function store(Request $request)
    {
        $jumlah = preg_replace('/[^0-9]/', '', $request->jumlah);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'jumlah' => 'required|min:0',
        ]);

        Potongan::create([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'nama_potongan' => $request->keterangan,
            'jumlah' => $jumlah,
        ]);

        return response()->json(['message' => 'Potongan berhasil disimpan']);
    }
    public function destroy($id)
    {
        $potongan = Potongan::findOrFail($id);
        $potongan->delete();

        return response()->json(['message' => 'Potongan berhasil dihapus']);
    }
}
