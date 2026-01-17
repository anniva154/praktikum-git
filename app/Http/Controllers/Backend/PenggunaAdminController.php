<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'status' => 'required',
            'id_jurusan' => 'nullable'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'id_jurusan' => $request->id_jurusan,
        ]);

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

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
            'status' => $request->status,
            'id_jurusan' => $request->id_jurusan,
        ]);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data diperbarui');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna dihapus');
    }
}
