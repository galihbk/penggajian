<?php

namespace App\Http\Controllers;

use App\Models\DetailPengguna;
use Illuminate\Support\HtmlString;
use App\Models\Jabatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
    public function dataKaryawan()
    {
        $data = Jabatan::select(['id', 'nama_jabatan', 'honor_harian', 'honor_lembur', 'updated_at']);

        $data = User::with('jabatan')->whereIn('role', ['admin', 'karyawan'])->where('status', 0)->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('jabatan.nama_jabatan', function ($row) {
                return $row->jabatan->nama_jabatan ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <button class="btn btn-sm btn-warning" onclick="editKaryawan(' . $row->id . ')">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="hapusKaryawan(' . $row->id . ')">Hapus</button>
            ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function jabatan()
    {
        return view('users.jabatan');
    }
    public function karyawan()
    {
        $jabatans = Jabatan::all();
        return view('users.karyawan', compact('jabatans'));
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
    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable',
            'jabatan_id' => 'required|exists:jabatans,id',
            'role' => 'required|in:admin,karyawan',
            'nomor_rekening' => 'required|max:100',
            'nama_bank' => 'required|max:50',
            'nama_penerima' => 'required|max:50',
        ]);
        $lemburan = 0;
        if (!empty($request->lemburan)) {
            $lemburan = 1;
        }
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->jabatan_id = $request->jabatan_id;
        $user->role = $request->role;
        $user->nama_penerima = $request->nama_penerima;
        $user->nama_bank = $request->nama_bank;
        $user->nomor_rekening = $request->nomor_rekening;
        $user->alamat = $request->alamat;
        $user->alamat = $request->alamat;
        $user->tgl_masuk = $request->tgl_masuk;
        $user->lemburan = $lemburan;
        $user->password = Hash::make('12345678');

        $user->save();

        return response()->json(['message' => 'Karyawan berhasil ditambahkan.']);
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
    public function editKaryawan($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    public function updateKaryawan(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable',
            'jabatan_id' => 'required|exists:jabatans,id',
            'role' => 'required|in:admin,karyawan',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nomor_rekening' => 'required|max:100',
            'nama_bank' => 'required|max:50',
            'nama_penerima' => 'required|max:50',
        ]);
        $lemburan = 0;
        if (!empty($request->lemburan)) {
            $lemburan = 1;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->jabatan_id = $request->jabatan_id;
        $user->role = $request->role;
        $user->alamat = $request->alamat;
        $user->tgl_masuk = $request->tgl_masuk;
        $user->nama_penerima = $request->nama_penerima;
        $user->nama_bank = $request->nama_bank;
        $user->nomor_rekening = $request->nomor_rekening;
        $user->lemburan = $lemburan;

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('foto_karyawan', 'public');
            $user->foto = $path;
        }

        $user->save();

        return response()->json(['message' => 'Data karyawan berhasil diperbarui.']);
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
    public function deleteKaryawan($id)
    {
        $user = User::findOrFail($id);

        $user->status = 1;
        $user->save();

        return response()->json(['message' => 'Karyawan berhasil dinonaktifkan.']);
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
