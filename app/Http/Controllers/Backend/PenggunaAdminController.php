<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PenggunaAdminController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('jurusan')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->role, function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if (auth()->user()->role === 'pimpinan') {
            return view('backend.pimpinan.pengguna.index', compact('users'));
        }
        return view('backend.admin.datapengguna.index', compact('users'));
    }

    public function create()
    {
        $jurusan = Jurusan::all();
        return view('backend.admin.datapengguna.create', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8',
            'no_wa'         => 'nullable|string|max:20',
            'role'          => 'required',
            'tipe_pengguna' => 'nullable|in:guru,siswa',
            'status'        => 'required',
            'id_jurusan'    => 'nullable|exists:jurusans,id_jurusan',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'no_wa'         => $request->no_wa, // Perbaikan: Menambah no_wa
            'role'          => $request->role,
            'tipe_pengguna' => ($request->role === 'pengguna') ? $request->tipe_pengguna : null, // Perbaikan: Simpan tipe
            'status'        => $request->status,
            'id_jurusan'    => $request->id_jurusan,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $request->name . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            $data['foto'] = $filename;
        }

        User::create($data);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $jurusan = Jurusan::all();
        return view('backend.admin.datapengguna.edit', compact('user', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'no_wa'         => 'nullable|string|max:20',
            'role'          => 'required',
            'tipe_pengguna' => 'nullable|in:guru,siswa',
            'status'        => 'required',
            'id_jurusan'    => 'nullable',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'no_wa'         => $request->no_wa, // Perbaikan: Update no_wa
            'role'          => $request->role,
            'tipe_pengguna' => ($request->role === 'pengguna') ? $request->tipe_pengguna : null, // Perbaikan: Update tipe
            'status'        => $request->status,
            'id_jurusan'    => $request->id_jurusan,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto))) {
                unlink(public_path('uploads/profile/' . $user->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $request->name . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            $data['foto'] = $filename;
        }

        $user->update($data);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Hapus foto dari server sebelum hapus data
        if ($user->foto && file_exists(public_path('uploads/profile/' . $user->foto))) {
            unlink(public_path('uploads/profile/' . $user->foto));
        }

        $user->delete();
        return back()->with('success', 'Pengguna dihapus');
    }
}