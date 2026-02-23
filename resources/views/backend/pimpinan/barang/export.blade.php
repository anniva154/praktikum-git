<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Barang SIMLAB</title>

    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
            /* atas kanan bawah kiri */
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            margin: 0;
        }

        /* ===== KOP SURAT ===== */
        .kop table {
            width: 100%;
            border: none;
        }

        .kop img {
            width: 100px;
            /* logo diperkecil agar tidak mendorong teks */
            height: auto;
        }

        .kop .text {
            text-align: center;
            line-height: 1.25;
            margin: 0;
            /* pastikan tidak bergeser */
        }

        .kop .text h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .kop .text h2 {
            margin: 3px 0;
            font-size: 16pt;
            font-weight: bold;
        }

        .kop .text p {
            margin: 1px 0;
            font-size: 11pt;
        }

        .garis {
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            margin-top: 6px;
            height: 2px;
        }

        /* ===== TABEL DATA ===== */
        h3 {
            margin-top: 20px;
            text-align: center;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10px;
        }

        table.data th {
            background: #f2f2f2;
            text-align: center;
        }

        /* ===== FOOTER POJOK KANAN BAWAH ===== */
        .footer {
            position: fixed;
            bottom: 0;
            right: 0;
            font-size: 9px;
            text-align: right;
        }
    </style>
</head>

<body>


  <!-- ========== KOP SURAT ========== -->
<div class="kop">
    <table>
        <tr>
            <!-- Logo kiri -->
            <td width="15%" align="center">
                <img src="{{ public_path('assets/img/dinas.png') }}">
            </td>

            <!-- Teks tengah -->
            <td width="70%" class="text">
                <h1>PEMERINTAH PROVINSI JAWA TIMUR</h1>
                <h1>DINAS PENDIDIKAN</h1>
                <h2>SMK NEGERI 3 BANGKALAN</h2>
                <p>Alamat: Jl. Mertajasah No. 70, Bangkalan – Jawa Timur 69117</p>
                <p>Email: smkn3bangkalan.adm@gmail.com 
                    Website: <a href="https://smkn3-bangkalan.sch.id/" style="color: blue;">smkn3-bangkalan.sch.id</a>
                </p>
            </td>

            <!-- Logo kanan -->
            <td width="15%" align="center">
                <img src="{{ public_path('assets/img/logo.png') }}">
            </td>
        </tr>
    </table>

    <!-- Garis bawah -->
    <div class="garis"></div>
</div>


        <!-- Dicetak oleh -->
        <p style="text-align:right; font-size:10px;">
            Dicetak oleh: {{ auth()->user()->name }} <br>
            Tanggal: {{ now()->format('d-m-Y') }}
        </p>
{{-- ================= DATA BARANG ================= --}}
@if(isset($barang))
<h3>DATA BARANG</h3>

<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Lab</th>
            <th>Jurusan</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($barang as $i => $b)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $b->nama_barang }}</td>
            <td>{{ $b->kode_barang }}</td>

            {{-- LAB --}}
            <td>{{ $b->laboratorium->nama_lab ?? '-' }}</td>

            {{-- JURUSAN --}}
            <td>{{ $b->jurusan->nama_jurusan ?? '-' }}</td>

            <td class="text-center">{{ $b->jumlah }}</td>
            <td class="text-center">{{ ucfirst($b->kondisi) }}</td>
            <td class="text-center">{{ ucfirst($b->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif




{{-- FOOTER --}}
<div class="footer">
    Sistem Informasi Manajemen Laboratorium – SMKN 3 Bangkalan
</div>

</body>
</html>
