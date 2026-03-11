<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chatbot;

class ChatbotController extends Controller
{
    public function getResponse(Request $request)
    {
        // 1. Ambil pesan dari user dan ubah ke huruf kecil
        $pesan = strtolower($request->pesan);

        // 2. Cari di database: apakah ada 'keyword' yang terkandung dalam 'pesan' user
        // Contoh: User tanya "Gimana cara pinjam?", bot cari keyword "pinjam"
        $data = Chatbot::whereRaw('? LIKE CONCAT("%", keyword, "%")', [$pesan])->first();

        // 3. Jika ketemu, kirim jawaban. Jika tidak, kirim pesan default.
        if ($data) {
            return response()->json(['jawaban' => $data->jawaban]);
        } else {
            return response()->json([
                'jawaban' => "Maaf, saya tidak mengerti pertanyaan Anda. Silakan hubungi admin SMKN 3 Bangkalan."
            ]);
        }
    }
}