<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Breadcrumb --}}
                <div class="p-6 border-b border-gray-200">
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2">
                            <li class="inline-flex items-center">
                                <a href="{{ route('rapat.index') }}"
                                    class="inline-flex text-xs items-center font-medium text-gray-500 hover:text-blue-600">
                                    <svg class="w-2.5 h-2.5 me-2.5" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                    </svg>
                                    Rapat
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-2.5 h-2.5 text-gray-400 mx-1" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <span class="ms-1 text-xs font-medium text-gray-500 hover:text-blue-600 md:ms-2">
                                        Detail Rapat
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-sm text-gray-600 mt-2">Detail Rapat</p>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $rapat->judul ?? '-' }}</h1>
                        </div>

                        @if ($rapat->link_zoom)
                            <a href="{{ $rapat->link_zoom }}" target="_blank"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                Join Zoom Rapat
                            </a>
                        @endif
                    </div>

                    {{-- Detail Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Tanggal Rapat</p>
                            <p class="text-lg font-semibold">
                                {{ \Carbon\Carbon::parse($rapat->tanggal)->format('d M Y') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Waktu Rapat</p>
                            <p class="text-lg font-semibold">
                                {{ $rapat->waktu_mulai ?? '-' }} - {{ $rapat->waktu_selesai ?? '-' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Lokasi Rapat</p>
                            <p class="text-lg font-semibold">{{ $rapat->lokasi ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Tambahan Detail --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Perangkat Daerah</p>
                            <p class="text-lg font-semibold">{{ $rapat->nama_perangkat_daerah ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">PIC</p>
                            <p class="text-lg font-semibold">{{ $rapat->pic->name ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="text-lg font-semibold capitalize">{{ $rapat->status ?? '-' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
