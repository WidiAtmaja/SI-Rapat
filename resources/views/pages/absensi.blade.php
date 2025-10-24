<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex p-7 justify-between items-center">
                    <h1 class="text-xl font-semibold">Absensi</h1>
                    <div class="items-center flex">
                        <div class="flex-1 px-6 max-w-sm mr-auto sm:ml-8">
                            <form class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="search" id="default-search"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-9 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Cari..." />
                            </form>
                        </div>
                        @if (auth()->user()->peran === 'admin')
                            @include('pages.partials.modal-form.create-modal.absensi-modal')
                        @endif
                        <x-buttondropdown />
                    </div>
                </div>

                <!--  Card Admin -->
                @if (auth()->user()->peran === 'admin')
                    <div class="p-6">
                        <div class="overflow-x-auto space-y-4">
                            @forelse ($absensis as $absen)
                                <div
                                    class="bg-white border-l-4 border-l-blue-500  rounded-xl shadow-sm border border-gray-00">
                                    <!-- Header -->
                                    <div class="flex justify-between items-center p-4 border-b border-gray-100">
                                        <div>
                                            <h1 class="text-lg font-bold mb-2">
                                                {{ $absen->rapat->judul ?? '-' }}
                                            </h1>
                                            <div class="flex items-center text-sm my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="#323232" viewBox="0 0 24 24">
                                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                                    <path
                                                        d="m19,4h-2v-2h-2v2h-6v-2h-2v2h-2c-1.1,0-2,.9-2,2v14c0,1.1.9,2,2,2h14c1.1,0,2-.9,2-2V6c0-1.1-.9-2-2-2ZM5,20v-12h14v-2,14s-14,0-14,0Z">
                                                    </path>
                                                    <path d="M7 11H9V13H7z"></path>
                                                    <path d="M11 11H13V13H11z"></path>
                                                    <path d="M15 11H17V13H15z"></path>
                                                    <path d="M7 15H9V17H7z"></path>
                                                    <path d="M11 15H13V17H11z"></path>
                                                    <path d="M15 15H17V17H15z"></path>
                                                </svg>
                                                <span class="text-gray-600 mx-2">{{ $absen->rapat->tanggal }}</span>
                                            </div>
                                            <div class="flex items-center text-sm my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="#323232" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                                                    </path>
                                                    <path d="M13 6h-2v6c0 .18.05.35.13.5l3 5.2 1.73-1-2.87-4.96V6.01Z">
                                                    </path>
                                                </svg>
                                                <span class="text-gray-600 mx-2">{{ $absen->rapat->waktu_mulai }} -
                                                    {{ $absen->rapat->waktu_mulai }}</span>
                                            </div>

                                            <div class="flex items-center text-sm my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="#323232" viewBox="0 0 24 24">
                                                    <path
                                                        d="M16 10c0-2.21-1.79-4-4-4s-4 1.79-4 4 1.79 4 4 4 4-1.79 4-4m-6 0c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2">
                                                    </path>
                                                    <path
                                                        d="M11.42 21.81c.17.12.38.19.58.19s.41-.06.58-.19c.3-.22 7.45-5.37 7.42-11.82 0-4.41-3.59-8-8-8s-8 3.59-8 8c-.03 6.44 7.12 11.6 7.42 11.82M12 4c3.31 0 6 2.69 6 6 .02 4.44-4.39 8.43-6 9.74-1.61-1.31-6.02-5.29-6-9.74 0-3.31 2.69-6 6-6">
                                                    </path>
                                                </svg>
                                                <span class="text-gray-600 mx-2">{{ $absen->rapat->lokasi }}</span>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-semibold text-green-600">{{ $absen->hadir }}</span>
                                                Hadir |
                                                <span class="font-semibold text-yellow-600">{{ $absen->izin }}</span>
                                                Izin |
                                                <span class="font-semibold text-red-600">{{ $absen->tidakHadir }}</span>
                                                Tidak Hadir
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Total: {{ $absen->totalPegawai }} Pegawai
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Body -->
                                    <div class="p-4 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                Dibuat: {{ $absen->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('absensi.show', $absen->rapat_id) }}"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                Detail Absensi
                                            </a>
                                            <form action="{{ route('absensi.destroy', $absen->rapat_id) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="confirmDelete(this); return false;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Belum ada data absensi.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
                <!--  Card Admin -->

                <!--  Card Pegawai -->
                @if (auth()->user()->peran === 'pegawai')
                    <div class="p-6">
                        <div class="overflow-x-auto space-y-4">
                            @forelse ($absensis as $absen)
                                <div
                                    class="bg-white border-l-4 border-l-blue-500 rounded-xl shadow-sm border border-gray-200">
                                    <!-- Header -->
                                    <div class="flex justify-between items-center p-4 border-b border-gray-100">
                                        <div>
                                            <h1 class="text-lg font-bold mb-2">
                                                {{ $absen->rapat->judul ?? '-' }}
                                            </h1>
                                            <div class="flex items-center text-sm my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="#323232" viewBox="0 0 24 24">
                                                    <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                                    <path
                                                        d="m19,4h-2v-2h-2v2h-6v-2h-2v2h-2c-1.1,0-2,.9-2,2v14c0,1.1.9,2,2,2h14c1.1,0,2-.9,2-2V6c0-1.1-.9-2-2-2ZM5,20v-12h14v-2,14s-14,0-14,0Z">
                                                    </path>
                                                    <path d="M7 11H9V13H7z"></path>
                                                    <path d="M11 11H13V13H11z"></path>
                                                    <path d="M15 11H17V13H15z"></path>
                                                    <path d="M7 15H9V17H7z"></path>
                                                    <path d="M11 15H13V17H11z"></path>
                                                    <path d="M15 15H17V17H15z"></path>
                                                </svg>
                                                <span class="text-gray-600 mx-2">{{ $absen->rapat->tanggal }}</span>
                                            </div>
                                            <div class="flex items-center text-sm my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="#323232" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                                                    </path>
                                                    <path d="M13 6h-2v6c0 .18.05.35.13.5l3 5.2 1.73-1-2.87-4.96V6.01Z">
                                                    </path>
                                                </svg>
                                                <span class="text-gray-600 mx-2">{{ $absen->rapat->waktu_mulai }} -
                                                    {{ $absen->rapat->waktu_mulai }}</span>
                                            </div>

                                            <div class="flex items-center text-sm my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="#323232" viewBox="0 0 24 24">
                                                    <path
                                                        d="M16 10c0-2.21-1.79-4-4-4s-4 1.79-4 4 1.79 4 4 4 4-1.79 4-4m-6 0c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2">
                                                    </path>
                                                    <path
                                                        d="M11.42 21.81c.17.12.38.19.58.19s.41-.06.58-.19c.3-.22 7.45-5.37 7.42-11.82 0-4.41-3.59-8-8-8s-8 3.59-8 8c-.03 6.44 7.12 11.6 7.42 11.82M12 4c3.31 0 6 2.69 6 6 .02 4.44-4.39 8.43-6 9.74-1.61-1.31-6.02-5.29-6-9.74 0-3.31 2.69-6 6-6">
                                                    </path>
                                                </svg>
                                                <span class="text-gray-600 mx-2">{{ $absen->rapat->lokasi }}</span>
                                            </div>
                                        </div>
                                        @if ($absen->kehadiran == 'hadir')
                                            <span
                                                class="inline-block px-3 py-1 rounded-lg bg-green-100 text-green-800 font-semibold">
                                                Hadir
                                            </span>
                                        @elseif($absen->kehadiran == 'izin')
                                            <span
                                                class="inline-block px-3 py-1 rounded-lg bg-yellow-100 text-yellow-800 font-semibold">
                                                Izin
                                            </span>
                                        @else
                                            <span
                                                class="inline-block px-3 py-1 rounded-lg bg-red-100 text-red-800 font-semibold">
                                                Tidak Hadir
                                            </span>
                                        @endif
                                    </div>
                                    <!-- Body -->
                                    <div class="p-4 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-600">
                                                Status Kehadiran:
                                                @if ($absen->kehadiran == 'hadir')
                                                    <p1 class="inline-block text-green-800 font-semibold">
                                                        Hadir
                                                        </span>
                                                    @elseif($absen->kehadiran == 'izin')
                                                        <span class="inline-block text-yellow-800 font-semibold">
                                                            Izin
                                                        </span>
                                                    @else
                                                        <span class="inline-block text-red-800 font-semibold">
                                                            Tidak Hadir
                                                        </span>
                                                @endif
                                            </p>
                                            @if ($absen->updated_at != $absen->created_at)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Terakhir diupdate: {{ $absen->updated_at->format('d M Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                        @include('pages.partials.modal-form.edit-modal.absensi-modal')
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Belum ada absensi untuk Anda.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
                <!--  Card Pegawai -->
            </div>
        </div>
    </div>
</x-app-layout>
