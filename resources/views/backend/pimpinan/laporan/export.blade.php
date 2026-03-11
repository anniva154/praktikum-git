<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengguna SIMLAB</title>

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

       <h3>LAPORAN KERUSAKAN BARANG</h3>
    <p style="text-align: center; font-size: 11pt; margin-top: -15px;">Laboratorium: {{ $lab->nama_lab ?? 'Semua Laboratorium' }}</p>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Barang</th>
                <th width="15%">Pelapor</th>
                <th width="30%">Keterangan Kerusakan</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $i => $item)
                <tr>
                    <td align="center">{{ $i + 1 }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->user->name ?? 'Anonim' }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td align="center">{{ \Carbon\Carbon::parse($item->tgl_laporan)->translatedFormat('d/m/Y') }}</td>
                    <td align="center" class="status-badge">
                        @if($item->status == 'diajukan') Menunggu 
                        @elseif($item->status == 'diproses') Perbaikan 
                        @else Selesai @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center">Tidak ada data laporan kerusakan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

        <!-- ========== FOOTER KANAN BAWAH ========== -->
        <div class="footer">
            Sistem Informasi Manajemen Laboratorium – SMKN 3 Bangkalan
        </div>

</body>

</html>