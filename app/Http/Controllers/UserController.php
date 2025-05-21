<?php

namespace App\Http\Controllers;

use App\Models\DetailPengguna;
use Illuminate\Support\HtmlString;
use App\Models\Jabatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function index()
    {
        return view('users.index');
    }
    public function dataJabatan()
    {
        $data = Jabatan::select(['id', 'nama_jabatan', 'honor_harian', 'honor_lembur', 'updated_at']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('honor_harian', function ($row) {
                return 'Rp. ' . number_format($row->honor_harian, 0, ',', '.');
            })
            ->editColumn('honor_lembur', function ($row) {
                return 'Rp. ' . number_format($row->honor_lembur, 0, ',', '.');
            })
            ->editColumn('updated_at', function ($row) {
                return Carbon::parse($row->updated_at)->translatedFormat('d M Y');
            })
            ->addColumn('aksi', function ($row) {
                return '
                <button class="btn btn-sm btn-warning btn-edit" data-id="' . $row->id . '" data-nama="' . $row->nama_jabatan . '" data-harian="' . $row->honor_harian . '" data-lembur="' . $row->honor_lembur . '">Edit</button>
                <button class="btn btn-sm btn-danger btn-hapus" data-id="' . $row->id . '">Hapus</button>
            ';
            })
            ->rawColumns(['aksi']) // supaya tombol HTML tidak di-escape
            ->make(true);
    }
    public function jabatan()
    {
        return view('users.jabatan');
    }
    public function storeJabatan(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'honor_harian' => 'required|numeric',
            'honor_lembur' => 'required|numeric',
        ]);

        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'honor_harian' => $request->honor_harian,
            'honor_lembur' => $request->honor_lembur,
        ]);

        return response()->json(['message' => 'Data jabatan berhasil ditambahkan.']);
    }
    public function updateJabatan(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
            'honor_harian' => str_replace(['Rp. ', '.'], '', $request->honor_harian),
            'honor_lembur' => str_replace(['Rp. ', '.'], '', $request->honor_lembur),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function deleteJabatan($id)
    {
        $used = User::where('jabatan_id', $id)->exists();

        if ($used) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Tidak bisa dihapus karena jabatan sedang digunakan.'
            ]);
        }

        Jabatan::destroy($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Jabatan berhasil dihapus.'
        ]);
    }
}
