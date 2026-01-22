<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Jurusan;

class LabController extends Controller
{
   
   public function index(Request $request)
{
    $labs = Lab::with('jurusan')
        ->when($request->jurusan, function ($q) use ($request) {
            $q->where('id_jurusan', $request->jurusan);
        })
        ->when($request->search, function ($q) use ($request) {
            $q->where('nama_lab', 'like', '%' . $request->search . '%');
        })
        ->paginate(10);

    $jurusan = Jurusan::all();

    return view('backend.admin.datalab.index', compact('labs', 'jurusan'));
}

    public function create()
    {
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        return view('backend.admin.datalab.create', compact('jurusan'));
    }

  public function store(Request $request)
{
    $data = $request->validate([
        'nama_lab'   => 'required|string|max:100',
        'id_jurusan' => 'required|exists:jurusan,id_jurusan',
        'status'     => 'required|in:kosong,dipakai,perbaikan',
        'keterangan' => 'nullable|string',
    ]);

    Lab::create($data);

    return redirect()
        ->route('admin.lab.index') 
        ->with('success', 'Laboratorium berhasil ditambahkan');
}


    public function edit($id)
    {
        $lab = Lab::findOrFail($id);
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();

        return view('backend.admin.datalab.edit', compact('lab', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        $lab = Lab::findOrFail($id);

        $data = $request->validate([
            'nama_lab'   => 'required|string|max:100',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'keterangan' => 'nullable|string',
            'status'     => 'required|in:kosong,dipakai,perbaikan',
        ]);

        $lab->update($data);

        return redirect()
            ->route('admin.lab.index')
            ->with('success', 'Laboratorium berhasil diperbarui');
    }

    public function destroy($id)
    {
        $lab = Lab::findOrFail($id);
        $lab->delete();

        return redirect()
            ->route('admin.lab.index')
            ->with('success', 'Laboratorium berhasil dihapus');
    }
}
