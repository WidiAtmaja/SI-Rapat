<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
                {{-- Kolom Kiri --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-50 p-6 h-screen flex flex-col">
                    <div class="flex justify-between items-center mb-10">
                        <h1 class="text-xl font-semibold">Jadwal Rapat</h1>
                        <x-buttondropdown />
                    </div>

                    @if (auth()->user()->peran === 'admin')
                        @include('pages.partials.modal-form.create-modal.rapat-modal')
                    @endif
                    <div class="overflow-y-auto py-4 flex-1 space-y-4 items-center justify-center">
                        @forelse ($rapat as $item)
                            {{--  Card --}}
                            <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                <div class="p-5">
                                    <div class="flex justify-between items-center">
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">
                                            {{ $item->judul }}
                                        </h5>
                                        <button id="dropdownDefaultButton1" data-dropdown-toggle="dropdown2"
                                            type="button"
                                            class="p-2 text-gray-70 hover:bg-gray-100  rounded-md inline-flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 32 32" class="w-5 h-5 text-gray-700">
                                                <path
                                                    d="M13,16c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,14.346,13,16z">
                                                </path>
                                                <path
                                                    d="M13,26c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,24.346,13,26z">
                                                </path>
                                                <path
                                                    d="M13,6c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,4.346,13,6z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="dropdown2"
                                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 border border-gray-200">
                                            <ul class="py-2 text-sm text-gray-700"
                                                aria-labelledby="dropdownDefaultButton1">
                                                <li>
                                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Edit</a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="block px-4 py-2 hover:bg-gray-100">Hapus</a>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>

                                    <h1 class="text-xs">{{ $item->status }}</h1>

                                    <div class="py-4">

                                        <!-- Tanggal -->
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $item->tanggal }}</span>
                                        </div>

                                        <!-- Lokasi -->
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $item->lokasi }}</span>
                                        </div>

                                        <!-- Pukul -->
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $item->waktu_mulai }} -
                                                {{ $item->waktu_selesai }}</span>
                                        </div>
                                    </div>


                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('rapat.show', $item->id) }}"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-blue-700 rounded-lg hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                            Detail

                                        </a>
                                        <a href="{{ $item->link_zoom }}" target="_blank"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                            Bergabung
                                            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M1 5h12m0 0L9 1m4 4L9 9" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h1 class="text-gray-500 text-sm text-center">
                                Tidak ada rapat
                            </h1>
                        @endforelse
                    </div>
                </div>





                {{-- Kolom Kanan --}}
                <div class="lg:col-span-3 space-y-4 ">
                    {{-- Section Atas --}}
                    {{-- @foreach ($rapat as $ra)
                        <div
                            class="bg-white border-l-4 border-l-blue-500 rounded-xl shadow-sm border border-gray-50 overflow-hidden">
                            <!-- Header Section -->
                            <div class="p-4 border-b border-gray-100">
                                <p class="text-sm text-gray-500 mb-1">{{ $ra->status }}</p>
                                <h2 class="text-lg font-semibold text-gray-900">{{ $ra->judul }}</h2>
                            </div>

                            <!-- Content Section -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-4">
                                <!-- Info Section -->
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                                    <!-- Tanggal -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $ra->tanggal }}</span>
                                    </div>

                                    <!-- Waktu -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $ra->pic->name }}</span>
                                    </div>

                                    <!-- Lokasi -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $ra->lokasi }}</span>
                                    </div>

                                    <!-- Peserta -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $ra->nama_perangkat_daerah }}</span>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="flex gap-2 mt-3 md:mt-0">
                                    <button type="button"
                                        class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                                        Salin Link
                                    </button>
                                    <button type="button"
                                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                                        Bergabung
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach --}}
                    <x-calendar />
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
