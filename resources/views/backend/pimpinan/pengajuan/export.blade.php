<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengajuan SIMLAB</title>

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
    @if(isset($pengajuan))
        <div class="judul-laporan" style="text-align: center; margin-bottom: 20px;">
            <h2 style="margin:0; font-size: 14pt;">LAPORAN DAFTAR PENGAJUAN BARANG</h2>
            <p style="margin:5px 0; font-size: 12pt;">Laboratorium: <strong>{{ $lab->nama_lab }}</strong></p>
            <p style="margin:0; font-size: 10pt; color: #555;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <table class="data" style="width: 100%; border-collapse: collapse; font-size: 10pt;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th width="3%" style="border: 1px solid #000; padding: 8px;">No</th>
                    <th width="15%" style="border: 1px solid #000; padding: 8px;">Nama Barang & Spesifikasi</th>
                    <th width="10%" style="border: 1px solid #000; padding: 8px;">Jumlah</th>
                    <th width="12%" style="border: 1px solid #000; padding: 8px;">Estimasi Harga</th>
                    <th width="15%" style="border: 1px solid #000; padding: 8px;">Alasan Kebutuhan</th>
                    <th width="10%" style="border: 1px solid #000; padding: 8px;">Urgensi</th>
                    <th width="10%" style="border: 1px solid #000; padding: 8px;">Status</th>
                    <th width="15%" style="border: 1px solid #000; padding: 8px;">Catatan Pimpinan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuan as $index => $item)
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: top;">
                            {{ $index + 1 }}</td>
                        <td style="border: 1px solid #000; padding: 8px; vertical-align: top;">
                            <strong>{{ $item->nama_barang }}</strong><br>
                            <small style="color: #444; font-style: italic;">Spesifikasi: {{ $item->spesifikasi ?? '-' }}</small>
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: top;">
                            {{ $item->jumlah }} {{ $item->satuan }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right; vertical-align: top;">
                            Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; vertical-align: top; font-size: 9pt;">
                            {{ $item->alasan_kebutuhan }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: top;">
                            {{ $item->urgensi }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center; vertical-align: top;">
                            <strong>{{ strtoupper($item->status_persetujuan) }}</strong>
                        </td>
                        <td
                            style="border: 1px solid #000; padding: 8px; vertical-align: top; font-size: 9pt; background-color: #fafafa;">
                            {{ $item->catatan_pimpinan ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="border: 1px solid #000; padding: 20px; text-align: center;">
                            Tidak ada data pengajuan barang di laboratorium ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px; font-size: 10pt;">
            <p><strong>Total Estimasi Anggaran: </strong>
                Rp
                {{ number_format($pengajuan->where('status_persetujuan', 'Disetujui')->sum('estimasi_harga'), 0, ',', '.') }}
                (Hanya yang disetujui)
            </p>
        </div>
    @endif
    <div class="footer">
        Sistem Informasi Manajemen Laboratorium – SMKN 3 Bangkalan
    </div>

</body>

</html>