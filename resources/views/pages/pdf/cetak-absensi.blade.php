<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Daftar Hadir - {{ $rapat->judul }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }

        .container {
            width: 98%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
        }

        .header p {
            font-size: 12px;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
            /* Word wrap untuk kolom yang panjang */
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-size: 11px;
            text-align: center;
        }

        .group-header {
            background-color: #e0e0e0;
            font-weight: bold;
            font-size: 12px;
            padding: 8px;
            /* Kolom ini mencakup semua kolom tabel */
        }

        .text-center {
            text-align: center;
        }

        .bukti-img {
            /* Mengecilkan gambar bukti */
            max-width: 60px;
            /* Atur lebar maksimum gambar */
            max-height: 60px;
            /* Atur tinggi maksimum gambar */
            height: auto;
            display: block;
            margin: 2px;
        }

        .bukti-container {
            display: flex;
            /* dompdf mungkin tidak mendukung flex, kita atur manual */
            width: 100%;
        }

        .bukti-container img {
            /* Atur agar gambar tidak terlalu besar */
            max-width: 45%;
            height: auto;
            margin: 2px;
        }

        /* Status Kehadiran */
        .status {
            font-weight: bold;
            text-align: center;
        }

        .status-hadir {
            color: green;
        }

        .status-izin {
            color: #b8860b;
        }

        /* DarkGoldenRod */
        .status-tidakhadir {
            color: red;
        }

        /* Penomoran */
        .nomor {
            width: 25px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>DAFTAR HADIR PESERTA RAPAT</h1>
            <p><strong>Nama Rapat:</strong> {{ $rapat->judul }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($rapat->tanggal)->isoFormat('dddd, D MMMM Y') }} |
                <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('H:i') }} -
                {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('H:i') }} WITA | <strong>Lokasi:</strong>
                {{ $rapat->lokasi }}
            </p>
        </div>

        @forelse ($absensisDikelompokkan as $namaPerangkatDaerah => $absensis)
            <table>
                <thead>
                    <!-- Judul Grup Perangkat Daerah -->
                    <tr>
                        <th class="group-header" colspan="8">
                            {{ $namaPerangkatDaerah }}
                        </th>
                    </tr>
                    <!-- Header Tabel -->
                    <tr>
                        <th class="nomor">No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Kehadiran</th>
                        <th>Waktu Absensi</th>
                        <th>Tanda Tangan</th>
                        <th>Bukti Foto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($absensis as $index => $absensi)
                        <tr>
                            <td class="nomor">{{ $loop->iteration }}</td>
                            <td>{{ $absensi->user->name ?? '-' }}</td>
                            <td>{{ $absensi->user->nip ?? '-' }}</td>
                            <td>{{ $absensi->user->jabatan ?? '-' }}</td>

                            <!-- Status Kehadiran -->
                            <td class="status">
                                @if ($absensi->kehadiran == 'hadir')
                                    <span class="status-hadir">Hadir</span>
                                @elseif($absensi->kehadiran == 'izin')
                                    <span class="status-izin">Izin</span>
                                @else
                                    <span class="status-tidakhadir">Tidak Hadir</span>
                                @endif
                            </td>

                            <!-- Waktu Absensi -->
                            <td class="text-center">
                                @if ($absensi->updated_at != $absensi->created_at && $absensi->kehadiran != 'tidak hadir')
                                    {{ $absensi->updated_at->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>

                            <!-- Tanda Tangan (Base64) -->
                            <td class="text-center">
                                @if ($absensi->tanda_tangan)
                                    <img src="{{ $absensi->tanda_tangan }}" alt="TTE" class="bukti-img"
                                        style="max-width: 80px;">
                                @else
                                    -
                                @endif
                            </td>

                            <!-- Bukti Foto (Wajah & Zoom) -->
                            <td class="text-center">
                                <div class="bukti-container">
                                    {{-- Foto Wajah --}}
                                    @if ($absensi->foto_wajah && file_exists(storage_path('app/public/' . $absensi->foto_wajah)))
                                        <img src="{{ storage_path('app/public/' . $absensi->foto_wajah) }}"
                                            alt="Wajah" class="bukti-img">
                                    @endif

                                    {{-- Foto Zoom --}}
                                    @if ($absensi->foto_zoom && file_exists(storage_path('app/public/' . $absensi->foto_zoom)))
                                        <img src="{{ storage_path('app/public/' . $absensi->foto_zoom) }}"
                                            alt="Zoom" class="bukti-img">
                                    @endif

                                    @if (!$absensi->foto_wajah && !$absensi->foto_zoom && $absensi->kehadiran == 'hadir')
                                        <span>-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data pegawai untuk perangkat daerah ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @empty
            <p class="text-center">Tidak ada data absensi ditemukan untuk rapat ini.</p>
        @endforelse
    </div>
</body>

</html>
