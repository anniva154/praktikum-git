<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('backend.profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = [
        'name' => $request->name,
    ];

    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $filename = 'user_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile'), $filename);

        $data['foto'] = $filename;
    }

    $user->update($data);

    return back()->with('success', 'Profil berhasil diperbarui');
}

}
