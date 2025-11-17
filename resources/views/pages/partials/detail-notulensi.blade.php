<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 border-b border-gray-200">
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                                <a href="{{ route('notulensi.index') }}"
                                    class="inline-flex text-xs items-center font-medium text-gray-500 hover:text-blue-600">
                                    <svg class="w-2.5 h-2.5 me-2.5" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                    </svg>
                                    Notulensi
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-2.5 h-2.5 text-gray-400 mx-1" fill="none"
                                        viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <span class="ms-1 text-xs font-medium text-gray-500 hover:text-blue-600 md:ms-2">
                                        Detail Notulensi
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mt-2">Detail Notulensi</p>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $notulen->rapat->judul ?? '-' }}</h1>
                        </div>

                        <div class="justify-between flex">
                            {{-- Tombol Rekaman Rapat --}}
                            @if ($notulen->rekaman)
                                <a href="{{ $notulen->rekaman }}" target="_blank"
                                    class="mr-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Rekaman Rapat
                                </a>
                            @else
                                <div
                                    class="mr-3 text-gray-500 bg-gray-200 cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Tidak ada rekaman
                                </div>
                            @endif

                            {{-- Tombol Unduh Notulensi --}}
                            @if ($notulen->lampiran_file)
                                <a href="{{ route('notulensi.download', $notulen->id) }}" target="_blank"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Unduh Notulensi
                                </a>
                            @else
                                <div
                                    class="text-gray-500 bg-gray-200 cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Tidak ada dokumen
                                </div>
                            @endif
                        </div>



                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Tanggal Rapat</p>
                            <p class="text-lg font-semibold">
                                {{ \Carbon\Carbon::parse($notulen->rapat->tanggal)->format('d M Y') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Waktu Rapat</p>
                            <p class="text-lg font-semibold">
                                {{ \Carbon\Carbon::parse($notulen->rapat->waktu_mulai)->translatedFormat('H.i') }} -
                                {{ \Carbon\Carbon::parse($notulen->rapat->waktu_selesai)->translatedFormat('H.i') }}
                                WITA
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Lokasi Rapat</p>
                            <p class="text-lg font-semibold">{{ $notulen->rapat->lokasi }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Perangkat Daerah</p>
                            <p class="text-lg font-semibold">
                                {{ $notulen->rapat->perangkatDaerahs->pluck('nama_perangkat_daerah')->implode(', ') ?: '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-lg font-semibold py-4">Ringkasan</h1>
                    <div class="p-7 border rounded-lg bg-gray-50">
                        <p class="text-sm text-gray-700 whitespace-pre-line">
                            {{ $notulen->ringkasan ?? 'Belum ada ringkasan.' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
