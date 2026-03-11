@extends('layouts.frontend')

@section('title', 'Edulab | Home')

@section('main-content')

@php
    // Banner manual (tidak null, aman)
    $banners = collect([
        (object) ['photo' => asset('assets/img/a.png')],
        (object) ['photo' => asset('assets/img/a.png')],
        (object) ['photo' => asset('assets/img/a.png')],
    ]);
@endphp

{{-- SLIDER --}}
@if($banners->isNotEmpty())
<section id="Gslider" class="carousel slide" data-bs-ride="carousel">

    {{-- Indicator --}}
    <div class="carousel-indicators">
        @foreach($banners as $key => $banner)
            <button type="button"
                    data-bs-target="#Gslider"
                    data-bs-slide-to="{{ $key }}"
                    class="{{ $loop->first ? 'active' : '' }}"
                    aria-current="{{ $loop->first ? 'true' : 'false' }}">
            </button>
        @endforeach
    </div>

    {{-- Images --}}
    <div class="carousel-inner">
        @foreach($banners as $banner)
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                <img src="{{ $banner->photo }}" alt="Banner">
            </div>
        @endforeach
    </div>

    {{-- Controls --}}
    <button class="carousel-control-prev" type="button" data-bs-target="#Gslider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#Gslider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</section>
{{-- LABORATORIUM --}}
<section class="container my-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Laboratorium SMKN 3 Bangkalan</h2>
        <p class="text-muted">Fasilitas praktik unggulan untuk menunjang pembelajaran siswa</p>
    </div>

    <div class="row g-4">
        {{-- Farmasi --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/img/f.jpeg') }}" class="card-img-top" alt="Lab Farmasi">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Laboratorium Farmasi</h5>
                    
                </div>
            </div>
        </div>

        {{-- TKJ --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/img/tkj.jpeg') }}" class="card-img-top" alt="Lab TKJ">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Laboratorium TKJ</h5>
                    
                </div>
            </div>
        </div>

        {{-- Perhotelan --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/img/aph.jpeg') }}" class="card-img-top" alt="Lab Perhotelan">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Laboratorium Perhotelan</h5>
                
                </div>
            </div>
        </div>
    </div>
</section>
{{-- INFORMASI WEBSITE --}}
<section class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="fw-bold mb-3">Sistem Informasi Manajemen Laboratorium</h2>
                <h5 class="mb-4">SMKN 3 Bangkalan</h5>

                <p class="text-muted">
                    Selamat datang di website resmi Sistem Informasi Manajemen Laboratorium SMKN 3 Bangkalan.
                    Website ini dikembangkan sebagai media informasi dan pengelolaan laboratorium sekolah
                    yang meliputi Laboratorium Farmasi, Teknik Komputer dan Jaringan (TKJ), serta Perhotelan.
                </p>

                <p class="text-muted">
                    Sistem ini bertujuan untuk mendukung kegiatan praktikum, pengelolaan inventaris,
                    penjadwalan penggunaan laboratorium, serta pelaporan kerusakan sarana dan prasarana
                    secara terintegrasi dan terkomputerisasi.
                </p>

                <p class="text-muted">
                    Dengan adanya sistem ini, diharapkan pengelolaan laboratorium di SMKN 3 Bangkalan
                    dapat berjalan lebih efektif, efisien, dan transparan guna menunjang proses
                    pembelajaran siswa sesuai dengan kebutuhan dunia pendidikan dan industri.
                </p>
            </div>
        </div>
    </div>
</section>

@endif
<div id="chatbot-wrapper">
    <button id="chatbot-launcher" class="btn shadow">
        <i class="fa-solid fa-comment-dots"></i>
    </button>

    <div id="chatbot-window" class="d-none shadow">
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-robot me-2"></i>
                <h6 class="mb-0">Asisten SIM LAB</h6>
            </div>
            <button id="close-chatbot" class="btn-close btn-close-white"></button>
        </div>
        <div id="chat-body" class="chatbot-body">
            <div class="msg bot-msg">Halo! Ada yang bisa saya bantu terkait informasi Lab di SMKN 3 Bangkalan?</div>
        </div>
        <div class="chatbot-footer">
            <input type="text" id="user-input" placeholder="Ketik pertanyaan..." class="form-control">
            <button id="send-btn" class="btn btn-primary ms-2">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection
<style>
    /* Floating Chatbot Styles */
#chatbot-wrapper {
    position: fixed;
    bottom: 25px;
    right: 25px;
    z-index: 2000;
}
#chatbot-launcher {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: #ffd000;
    color: #084298;
    border: none;
    font-size: 22px;
}
#chatbot-window {
    position: absolute;
    bottom: 70px;
    right: 0;
    width: 300px;
    height: 400px;
    background: white;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    border: 1px solid #ddd;
}
.chatbot-header {
    background: #0d6efd;
    color: white;
    padding: 12px;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.chatbot-body {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    font-size: 13px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.msg { padding: 8px 12px; border-radius: 12px; max-width: 85%; }
.bot-msg { background: #f1f1f1; align-self: flex-start; }
.user-msg { background: #0d6efd; color: white; align-self: flex-end; }
.chatbot-footer { padding: 10px; border-top: 1px solid #eee; display: flex; }
    .card img {
    height: 220px;
    object-fit: cover;
}

.card {
    border-radius: 12px;
}

section h2 {
    letter-spacing: 0.5px;
}

    /* offset navbar fixed */
.main-content {
    padding-top: 90px;
}

/* slider */
#Gslider {
    width: 100%;
}

#Gslider .carousel-item {
    height: 70vh; /* RESPONSIVE */
}

#Gslider img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* safety */
body {
    overflow-x: hidden;
}

</style>
<script>
    // Script Logika Chatbot
    document.addEventListener('DOMContentLoaded', function() {
        const launcher = document.getElementById('chatbot-launcher');
        const windowChat = document.getElementById('chatbot-window');
        const closeBtn = document.getElementById('close-chatbot');
        const sendBtn = document.getElementById('send-btn');
        const userInput = document.getElementById('user-input');
        const chatBody = document.getElementById('chat-body');

        launcher.onclick = () => windowChat.classList.toggle('d-none');
        closeBtn.onclick = () => windowChat.classList.add('d-none');

        function appendMessage(text, type) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `msg ${type}-msg`;
            msgDiv.innerText = text;
            chatBody.appendChild(msgDiv);
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function handleChat() {
            const text = userInput.value.trim();
            if (!text) return;

            appendMessage(text, 'user');
            userInput.value = '';

            // Simulasi respon statis
            setTimeout(() => {
                let reply = "Maaf, saya belum mengenali pertanyaan itu. Silakan hubungi admin di smkn3bangkalan.adm@gmail.com";
                const lowerText = text.toLowerCase();
                
                if (lowerText.includes('pinjam')) reply = "Untuk meminjam alat, silakan Login terlebih dahulu dan pilih menu 'Pinjam Alat'.";
                if (lowerText.includes('tkj')) reply = "Lab TKJ memiliki fasilitas komputer yang lengkap untuk praktik jaringan dan software.";
                if (lowerText.includes('lokasi')) reply = "Laboratorium kami berlokasi di dalam area sekolah SMKN 3 Bangkalan.";

                appendMessage(reply, 'bot');
            }, 600);
        }

        sendBtn.onclick = handleChat;
        userInput.onkeypress = (e) => { if(e.key === 'Enter') handleChat() };
    });
</script>