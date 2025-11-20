{{-- Modal Tambah Absensi --}}
<x-modal-base title="Tambah Absensi" max-width="lg" :scrollable="false">
    <x-slot:trigger>
        <button
            class="mr-4 flex items-center justify-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ffffff" viewBox="0 0 24 24">
                <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path>
                <path
                    d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                </path>
            </svg>
            Buat Absensi
        </button>
    </x-slot:trigger>

    {{-- Form Tambah Absensi --}}
    <form id="formTambahAbsensi" action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <div class="grid gap-4 grid-cols-2">
            {{-- Pilih Rapat --}}
            <div class="col-span-2">
                <label for="rapat_id" class="block mb-2 text-sm font-medium text-gray-900">
                    Pilih Rapat <span class="text-red-500">*</span>
                </label>
                <select name="rapat_id" id="rapat_id" required
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Rapat</option>
                    @php
                        $rapatTersedia = \App\Models\Rapat::whereDoesntHave('absensis')
                            ->orderBy('tanggal', 'desc')
                            ->get();
                    @endphp

                    @forelse($rapatTersedia as $rapat)
                        <option value="{{ $rapat->id }}">
                            {{ $rapat->judul }} - {{ \Carbon\Carbon::parse($rapat->tanggal)->format('d/m/Y') }}
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada rapat yang tersedia</option>
                    @endforelse
                </select>

                @if ($rapatTersedia->isEmpty())
                    <p class="mt-2 text-sm text-red-600">
                        Semua rapat sudah memiliki absensi atau belum ada rapat yang dibuat.
                    </p>
                @endif
            </div>

            {{-- Input Waktu Buka --}}
            <div>
                <label for="datetime_absen_buka" class="block mb-2 text-sm font-medium text-gray-900">
                    Waktu Buka Absen <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="datetime_absen_buka" id="datetime_absen_buka" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            {{-- Input Waktu Tutup --}}
            <div>
                <label for="datetime_absen_tutup" class="block mb-2 text-sm font-medium text-gray-900">
                    Waktu Tutup Absen <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="datetime_absen_tutup" id="datetime_absen_tutup" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
        </div>

        <div class="p-3 mt-3 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Info:</strong> Absensi akan dibuat untuk semua pegawai dengan status default "Tidak Hadir"
            </p>
        </div>
    </form>

    {{-- Footer Buttons --}}
    <x-slot:footer>
        <button type="button" @click="open = false"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            Batal
        </button>
        <button type="submit" form="formTambahAbsensi"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition"
            @if ($rapatTersedia->isEmpty()) disabled @endif>
            Simpan Absensi
        </button>
    </x-slot:footer>
</x-modal-base>
