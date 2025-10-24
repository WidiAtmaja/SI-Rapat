<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200">

                    <!-- Breadcrump -->
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
                        <button type="button"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Cetak</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Tanggal Rapat</p>
                            <p class="text-lg font-semibold">{{ $rapat->tanggal }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Waktu Rapat</p>
                            <p class="text-lg font-semibold">
                                {{ $rapat->waktu_mulai }} -
                                {{ $rapat->waktu_selesai }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Lokasi Rapat</p>
                            <p class="text-lg font-semibold">{{ $rapat->lokasi }}</p>
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
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Unit Kerja</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Jabatan</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Kehadiran</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Waktu Absensi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($absensis as $index => $absensi)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-3 border border-gray-100 text-center">{{ $loop->iteration }}
                                        </td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->name }}</td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->nip }}
                                        </td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->unit_kerja }}</td>
                                        <td class="px-2 py-3 border border-gray-100 text-center">
                                            {{ $absensi->user->jabatan }}</td>
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
                                            @if ($absensi->updated_at != $absensi->created_at)
                                                <span class="text-xs text-gray-600">
                                                    {{ $absensi->updated_at->format('d M Y H:i') }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">Belum diisi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
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
