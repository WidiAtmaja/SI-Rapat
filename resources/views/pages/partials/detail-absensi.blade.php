<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200">
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                                <a href="{{ route('absensi.index') }}"
                                    class="inline-flex text-xs items-center font-medium text-gray-500 hover:text-blue-600">
                                    <svg class="w-2.5 h-2.5 me-2.5" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                    </svg>
                                    Absensi
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-2.5 h-2.5 text-gray-400 mx-1" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <a href="#"
                                        class="ms-1 text-xs font-medium text-gray-500 hover:text-blue-600 md:ms-2">
                                        {{ $rapat->judul }}</a>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mt-2">
                                Detail Absensi
                            </p>
                            <h1 class="text-2xl font-bold text-gray-900"> {{ $rapat->judul }}</h1>
                        </div>
                        <div class="flex justify-end mb-4">
                            <div class="flex justify-end mb-4">
                                <a href="{{ route('absensi.cetak-pdf', ['rapat' => $rapat->id]) }}" target="_blank"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Cetak Absensi (PDF)
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Tanggal Rapat</p>
                            <p class="text-lg font-semibold">
                                {{ \Carbon\Carbon::parse($rapat->tanggal)->format('d M Y') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Waktu Rapat</p>
                            <p class="text-lg font-semibold">
                                {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->translatedFormat('H.i') }} -
                                {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->translatedFormat('H.i') }} WITA
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Lokasi Rapat</p>
                            <p class="text-lg font-semibold">{{ $rapat->lokasi }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Perangkat Daerah</p>
                            <p class="text-lg font-semibold">
                                {{ $rapat->perangkatDaerahs->pluck('nama_perangkat_daerah')->implode(', ') ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg text-center">
                            <p class="text-3xl font-bold text-blue-700">{{ $total }}</p>
                            <p class="text-sm text-blue-600 mt-1">Total Pegawai</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg text-center">
                            <p class="text-3xl font-bold text-green-700">{{ $hadir }}</p>
                            <p class="text-sm text-green-600 mt-1">Hadir</p>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg text-center">
                            <p class="text-3xl font-bold text-yellow-700">{{ $izin }}</p>
                            <p class="text-sm text-yellow-600 mt-1">Izin</p>
                        </div>
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg text-center">
                            <p class="text-3xl font-bold text-red-700">{{ $tidakHadir }}</p>
                            <p class="text-sm text-red-600 mt-1">Tidak Hadir</p>
                        </div>
                    </div>
                </div>

                <!-- Tabel -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs text-gray-600 border border-gray-200">
                            <thead class="bg-gray-100 text-gray-500 text-sm">
                                <tr>
                                    <th class="w-10 px-2 py-3 border border-gray-200 text-center">No</th>
                                    <th class="w-32 px-2 py-3 border border-gray-200 text-center">Nama</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">NIP</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Perangkat Daerah</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Unit Kerja</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Jabatan</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Kehadiran</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Waktu Absensi</th>
                                    <th class="w-24 px-2 py-3 border border-gray-200 text-center">Bukti Kehadiran</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($absensis as $index => $absensi)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-3 border border-gray-100 text-center">{{ $loop->iteration }}
                                        </td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->name ?? '-' }}</td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->nip ?? '-' }}
                                        </td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->perangkatDaerah->nama_perangkat_daerah ?? ($absensi->user->unit_kerja ?? '-') }}
                                        </td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->unit_kerja ?? '-' }}</td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->jabatan ?? '-' }}</td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            @if ($absensi->kehadiran == 'hadir')
                                                <span
                                                    class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 font-semibold">
                                                    Hadir
                                                </span>
                                            @elseif($absensi->kehadiran == 'izin')
                                                <span
                                                    class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold">
                                                    Izin
                                                </span>
                                            @else
                                                <span
                                                    class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800 font-semibold">
                                                    Tidak Hadir
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            @if ($absensi->updated_at != $absensi->created_at && $absensi->kehadiran != 'tidak hadir')
                                                <span class="text-xs text-gray-600">
                                                    {{ $absensi->updated_at->format('d M Y H:i') }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">Belum diisi</span>
                                            @endif
                                        </td>

                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            @if ($absensi->kehadiran == 'hadir')
                                                <div class="flex justify-center items-center space-x-2">
                                                    {{-- Lihat Foto Wajah --}}
                                                    @if ($absensi->foto_wajah)
                                                        <a href="{{ Storage::url($absensi->foto_wajah) }}"
                                                            target="_blank" title="Lihat Foto Wajah"
                                                            class="text-blue-600 hover:text-blue-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" fill="#8001ff" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M8.5 8A1.5 1.5 0 1 0 8.5 11 1.5 1.5 0 1 0 8.5 8z">
                                                                </path>
                                                                <path
                                                                    d="m14,9.5c0,.83.67,1.5,1.5,1.5s1.5-.67,1.5-1.5-.67-1.5-1.5-1.5-1.5.67-1.5,1.5Z">
                                                                </path>
                                                                <path
                                                                    d="m8.65,15.98c.32.21.66.4,1.02.55.36.15.74.27,1.13.35.4.08.8.12,1.21.12s.81-.04,1.21-.12c.38-.08.76-.2,1.13-.35.36-.15.7-.34,1.02-.55.32-.21.62-.46.88-.73.27-.27.52-.57.73-.89l-1.66-1.12c-.14.21-.31.41-.49.59-.18.18-.38.35-.59.49-.21.14-.44.27-.68.37-.24.1-.5.18-.75.23-.53.11-1.09.11-1.62,0-.25-.05-.51-.13-.75-.23-.24-.1-.47-.23-.68-.37-.21-.14-.41-.31-.59-.49-.18-.18-.35-.38-.49-.59l-1.66,1.12c.21.32.46.62.73.88.27.27.57.52.89.73Z">
                                                                </path>
                                                                <path d="m5,5h4v-2h-4c-1.1,0-2,.9-2,2v4h2v-4Z"></path>
                                                                <path d="m5,21h4v-2h-4v-4h-2v4c0,1.1.9,2,2,2Z"></path>
                                                                <path d="m21,15h-2v4h-4v2h4c1.1,0,2-.9,2-2v-4Z"></path>
                                                                <path d="m21,5c0-1.1-.9-2-2-2h-4v2h4v4h2v-4Z"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    {{-- Lihat Foto Zoom --}}
                                                    @if ($absensi->foto_zoom)
                                                        <a href="{{ Storage::url($absensi->foto_zoom) }}"
                                                            target="_blank" title="Lihat SS Zoom"
                                                            class="text-purple-600 hover:text-purple-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" fill="#005eff" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M18 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4.33L22 17V7l-4 3.33zm-2 12H4V6h12z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    @endif

                                                    {{-- Lihat Tanda Tangan Zoom --}}
                                                    @if ($absensi->tanda_tangan)
                                                        <x-modal-base id="modal-tte-{{ $absensi->id }}"
                                                            title="Tanda Tangan: {{ $absensi->user->name ?? '' }}"
                                                            max-width="md" :scrollable="false">
                                                            <x-slot:trigger>
                                                                <button type="button" title="Lihat Tanda Tangan">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24" fill="#00abab"
                                                                        viewBox="0 0 24 24">
                                                                        <path class="b"
                                                                            d="M14 8 13.27 8.73 12.53 9.47 13.53 10.47 14.53 11.47 15.27 10.73 16 10 15 9 14 8z">
                                                                        </path>
                                                                        <path class="b"
                                                                            d="M8 14 8 16 10 16 13.82 12.18 11.82 10.18 8 14z">
                                                                        </path>
                                                                        <path class="b"
                                                                            d="m19,3H5c-1.1,0-2,.9-2,2v14c0,1.1.9,2,2,2h14c1.1,0,2-.9,2-2V5c0-1.1-.9-2-2-2ZM5,19V5h14v14s-14,0-14,0Z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                            </x-slot:trigger>

                                                            <div class="p-4">
                                                                <div
                                                                    class="border rounded-lg bg-gray-50 overflow-hidden">
                                                                    {{-- Tampilkan gambar dari Data URI --}}
                                                                    <img src="{{ $absensi->tanda_tangan }}"
                                                                        alt="Tanda Tangan" class="w-full h-auto">
                                                                </div>
                                                            </div>

                                                            <x-slot:footer>
                                                                <button type="button" @click="open = false"
                                                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                                                    Tutup
                                                                </button>
                                                            </x-slot:footer>
                                                        </x-modal-base>
                                                    @endif

                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data absensi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
