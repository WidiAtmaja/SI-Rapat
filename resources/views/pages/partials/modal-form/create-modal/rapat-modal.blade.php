{{-- Modal Tambah Rapat --}}
<x-modal-base title="Tambah Rapat" max-width="lg" :scrollable="true">
    <x-slot:trigger>
        <button
            class="flex items-center justify-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="#ffffff" viewBox="0 0 24 24">
                <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path>
                <path
                    d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                </path>
            </svg>
            Tambah Rapat
        </button>
    </x-slot:trigger>

    <form id="formTambahRapat" action="{{ route('rapat.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid gap-4 grid-cols-2">
            {{-- Judul --}}
            <div class="col-span-2">
                <label for="judul" class="block mb-2 text-sm font-medium text-gray-900">
                    Judul
                </label>
                <input type="text" name="judul" id="judul"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan judul rapat" required>
            </div>

            {{-- Nama Perangkat Daerah --}}
            <div class="col-span-2">
                <label for="nama_perangkat_daerah" class="block mb-2 text-sm font-medium text-gray-900">
                    Nama Perangkat Daerah
                </label>
                <input type="text" name="nama_perangkat_daerah" id="nama_perangkat_daerah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan perangkat daerah" required>
            </div>

            {{-- Tanggal --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900">
                    Tanggal
                </label>
                <input type="date" name="tanggal" id="tanggal"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- Waktu Mulai --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="waktu_mulai" class="block mb-2 text-sm font-medium text-gray-900">
                    Waktu Mulai
                </label>
                <input type="time" name="waktu_mulai" id="waktu_mulai"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- Waktu Selesai --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="waktu_selesai" class="block mb-2 text-sm font-medium text-gray-900">
                    Waktu Selesai
                </label>
                <input type="time" name="waktu_selesai" id="waktu_selesai"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- PIC --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="pic_id" class="block mb-2 text-sm font-medium text-gray-900">
                    PIC
                </label>
                <select name="pic_id" id="pic_id" required
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih PIC</option>
                    @foreach ($users as $user)
                        {{-- Tambahkan kondisi @if di sini --}}
                        @if ($user->peran == 'pegawai')
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            {{-- Link Zoom --}}
            <div class="col-span-2">
                <label for="link_zoom" class="block mb-2 text-sm font-medium text-gray-900">
                    Link Zoom
                </label>
                <input type="url" name="link_zoom" id="link_zoom"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="https://zoom.us/j/..." required>
            </div>

            {{-- Lokasi --}}
            <div class="col-span-2">
                <label for="lokasi" class="block mb-2 text-sm font-medium text-gray-900">
                    Lokasi
                </label>
                <input type="text" name="lokasi" id="lokasi"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan lokasi" required>
            </div>

            {{-- Status --}}
            <div class="col-span-2">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">
                    Status
                </label>
                <select name="status" id="status" required
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="terjadwal" selected>Terjadwal</option>
                    <option value="sedang berlangsung">Sedang Berlangsung</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>

            {{-- File Materi --}}
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Materi</label>
                <div id="drop-area-materi"
                    class="flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau seret file ke sini untuk mengunggah
                    </p>
                    <input id="materi" name="materi" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                        class="hidden" />
                </div>
                <p id="file-name-materi" class="mt-2 text-sm text-gray-600"></p>
                <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max: 20MB)</p>
            </div>

            {{-- File Surat --}}
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Surat</label>
                <div id="drop-area-surat"
                    class="flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau seret file ke sini untuk mengunggah
                    </p>
                    <input id="surat" name="surat" type="file" accept=".pdf,.doc,.docx" class="hidden" />
                </div>
                <p id="file-name-surat" class="mt-2 text-sm text-gray-600"></p>
                <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX (Max: 20MB)</p>
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" @click="open = false"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            Batal
        </button>
        <button type="submit" form="formTambahRapat"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
            Simpan Rapat
        </button>
    </x-slot:footer>
</x-modal-base>
