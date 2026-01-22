<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan SIMLAB</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        /* ================= KOP SURAT ================= */
        .kop {
            width: 100%;
            margin-bottom: 15px;
        }

        .kop table {
            width: 100%;
            border: none;
        }

        .kop img {
            width: 85px;
        }

        .kop .text {
            text-align: center;
            line-height: 1.4;
        }

        .kop .text h1 {
            margin: 0;
            font-size: 14px;
            
        }

        .kop .text h2 {
            margin: 2px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .kop .text p {
            margin: 0;
            font-size: 11px;
        }

        .garis {
            border-top: 2px solid #000;
            margin-top: 8px;
        }

        /* ================= TABEL ================= */
        h3 {
            margin-top: 20px;
            margin-bottom: 5px;
            text-align: center;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }

        table.data th {
            background: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            font-size: 9px;
            text-align: right;
        }
    </style>
</head>
<body>

{{-- ================= KOP SURAT ================= --}}
<div class="kop">
    <table>
        <tr>
            <td width="15%" align="center" style="vertical-align: middle;">
                <img src="{{ public_path('assets/img/logo.png') }}">
            </td>
            <td class="text">
                <h1>PEMERINTAH PROVINSI JAWA TIMUR</h1>
                <h1>DINAS PENDIDIKAN</h1>
                <h2>SMK NEGERI 3 BANGKALAN</h2>
                <p>SISTEM INFORMASI MANAJEMEN LABORATORIUM</p>
                <p>Jl. Mertajasah No. 70, Bangkalan – Jawa Timur 69117</p>
            </td>
        </tr>
    </table>

    {{-- SATU GARIS BAWAH SAJA --}}
    <div class="garis"></div>
</div>

<p style="text-align:right; font-size:10px;">
    Dicetak oleh: {{ auth()->user()->name }} <br>
    Tanggal: {{ now()->format('d-m-Y') }}
</p>

{{-- ================= DATA PENGGUNA ================= --}}
<h3>DATA PENGGUNA</h3>

<table class="data">
    <thead>
        <tr>
            <th width="4%">No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Jurusan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $i => $u)
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td class="text-center">{{ ucfirst($u->role) }}</td>
            <td>{{ $u->jurusan?->nama_jurusan ?? '-' }}</td>
            <td class="text-center">{{ ucfirst($u->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Sistem Informasi Manajemen Laboratorium – SMKN 3 Bangkalan
</div>

</body>
</html>
