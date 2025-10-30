{{-- Modal Edit Absensi --}}
<x-modal-base title="Edit Absensi {{ $absen->rapat->judul }}" max-width="lg" :scrollable="false">
    <x-slot:trigger>
        <button
            class="mr-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            type="button" data-modal-target="edit-modal-{{ $absen->id }}"
            data-modal-toggle="edit-modal-{{ $absen->id }}">
            Edit Absensi
        </button>
    </x-slot:trigger>

    {{-- Form Edit Absensi --}}
    <form id="formEditAbsensi-{{ $absen->id }}" action="{{ route('absensi.update', $absen->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid gap-4 grid-cols-2">
            {{-- Detail Absensi --}}
            <div class="mb-4 col-span-2">
                <div class="flex items-center text-sm my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#323232"
                        viewBox="0 0 24 24">
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
                    <span class="text-gray-600 mx-2">
                        {{ \Carbon\Carbon::parse($absen->rapat->tanggal)->format('d M Y') }}</span>
                </div>
                <div class="flex items-center text-sm my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#323232"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                        </path>
                        <path d="M13 6h-2v6c0 .18.05.35.13.5l3 5.2 1.73-1-2.87-4.96V6.01Z">
                        </path>
                    </svg>
                    <span class="text-gray-600 mx-2">
                        {{ \Carbon\Carbon::parse($absen->rapat->waktu_mulai)->translatedFormat('H.i') }} -
                        {{ \Carbon\Carbon::parse($absen->rapat->waktu_selesai)->translatedFormat('H.i') }} WITA</span>
                </div>
                <div class="flex items-center text-sm my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#323232"
                        viewBox="0 0 24 24">
                        <path
                            d="M16 10c0-2.21-1.79-4-4-4s-4 1.79-4 4 1.79 4 4 4 4-1.79 4-4m-6 0c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2">
                        </path>
                        <path
                            d="M11.42 21.81c.17.12.38.19.58.19s.41-.06.58-.19c.3-.22 7.45-5.37 7.42-11.82 0-4.41-3.59-8-8-8s-8 3.59-8 8c-.03 6.44 7.12 11.6 7.42 11.82M12 4c3.31 0 6 2.69 6 6 .02 4.44-4.39 8.43-6 9.74-1.61-1.31-6.02-5.29-6-9.74 0-3.31 2.69-6 6-6">
                        </path>
                    </svg>
                    <span class="text-gray-600 mx-2">{{ $absen->rapat->lokasi }}</span>
                </div>

                <label for="kehadiran-{{ $absen->id }}" class="block mb-2 py-2 text-sm font-medium text-gray-900">
                    Pilih Kehadiran
                </label>
                <select name="kehadiran" id="kehadiran-{{ $absen->id }}" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                           focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="hadir" {{ $absen->kehadiran == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ $absen->kehadiran == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="tidak hadir" {{ $absen->kehadiran == 'tidak hadir' ? 'selected' : '' }}>
                        Tidak Hadir
                    </option>
                </select>
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" @click="open = false"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            Batal
        </button>
        <button type="submit" form="formEditAbsensi-{{ $absen->id }}"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
            Simpan Absensi
        </button>
    </x-slot:footer>
</x-modal-base>
