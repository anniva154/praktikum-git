<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman SIMLAB</title>

    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            margin: 0;
        }

        .kop table {
            width: 100%;
            border: none;
        }

        .kop img {
            width: 100px;
            height: auto;
        }

        .kop .text {
            text-align: center;
            line-height: 1.25;
            margin: 0;
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
    <div class="kop">
        <table>
            <tr>
                <td width="15%" align="center">
                    <img src="{{ public_path('assets/img/dinas.png') }}">
                </td>
                <td width="70%" class="text">
                    <h1>PEMERINTAH PROVINSI JAWA TIMUR</h1>
                    <h1>DINAS PENDIDIKAN</h1>
                    <h2>SMK NEGERI 3 BANGKALAN</h2>
                    <p>Alamat: Jl. Mertajasah No. 70, Bangkalan – Jawa Timur 69117</p>
                    <p>Email: smkn3bangkalan.adm@gmail.com
                        Website: <a href="https://smkn3-bangkalan.sch.id/"
                            style="color: blue;">smkn3-bangkalan.sch.id</a>
                    </p>
                </td>
                <td width="15%" align="center">
                    <img src="{{ public_path('assets/img/logo.png') }}">
                </td>
            </tr>
        </table>
        <div class="garis"></div>
    </div>
    <p style="text-align:right; font-size:10px;">
        Dicetak oleh: {{ auth()->user()->name }} <br>
        Tanggal: {{ now()->format('d-m-Y') }}
    </p>
    {{-- ================= DATA BARANG ================= --}}
    @if(isset($peminjaman))
        <div class="judul-laporan">
            <h2 style="margin:0; font-size: 14pt;">LAPORAN MONITORING PEMINJAMAN</h2>
            <p style="margin:5px 0; font-size: 12pt;">Laboratorium: <strong>{{ $lab->nama_lab }}</strong></p>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Barang</th>
                    <th width="20%">Peminjam</th>
                    <th width="8%">Jumlah</th>
                    <th width="15%">Waktu Pinjam</th>
                    <th width="15%">Waktu Kembali</th>
                    <th width="12%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-bold">{{ $item->barang->nama_barang }}</span>
                        </td>
                        <td>{{ $item->user->name }}</td>
                        <td class="text-center">{{ $item->jumlah_pinjam }} Unit</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/Y H:i') }}
                        </td>
                        <td class="text-center">
                            @if($item->waktu_kembali)
                                {{ \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="status">{{ $item->status == 'diajukan' ? 'Menunggu' : $item->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data peminjaman di laboratorium ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @endif

    {{-- FOOTER --}}
    <div class="footer">
        Sistem Informasi Manajemen Laboratorium – SMKN 3 Bangkalan
    </div>

</body>

</html>