<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Gunakan model User yang benar
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\PostComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Rules\MatchOldPassword;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman dashboard pengguna.
     */
    public function index()
    {
        return view('frontend.user.index'); // Dashboard pengguna
    }

    /**
     * Menampilkan halaman profil pengguna.
     */
    public function profile()
{
    // Mendapatkan pengguna yang sedang login
    $profile = Auth::user();

    // Mengirim data ke view
    return view('frontend.user.users.profile', compact('profile'));
}

    /**
     * Memperbarui profil pengguna.
     */
    public function profileUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update data profil
        $data = $request->only('name', 'email');

        // Proses upload foto jika ada
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile', $filename);

            // Hapus foto lama jika ada
            if ($user->photo && Storage::exists('public/profile/' . $user->photo)) {
                Storage::delete('public/profile/' . $user->photo);
            }

            $data['photo'] = $filename;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman daftar pesanan pengguna.
     */
    public function orderIndex()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('frontend.user.transaction.index', compact('orders'));
    }


    /**
     * Menampilkan halaman untuk mengganti password pengguna.
     */
    public function changePassword()
    {
        return view('frontend.user.layouts.changePassword');
    }

    /**
     * Menyimpan password baru pengguna.
     */
    public function changePasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min:8'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diperbarui!');
    }
}
