{{-- Modal Tambah Pengguna --}}
<x-modal-base title="Tambah Pengguna Baru Excel" max-width="lg" :scrollable="false">
    <x-slot:trigger>
        <button type="button"
            class="flex items-center justify-center gap-2 w-full text-green-600 hover:bg-green-50 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#00c23c" viewBox="0 0 24 24">
                <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path>
                <path
                    d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                </path>
            </svg>
            Tambah Excel
        </button>
    </x-slot:trigger>

    <form id="formTambahExcelPengguna" action="{{ route('user.import-excel') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="grid gap-4 grid-cols-2">
            {{-- Lampiran File Excell --}}
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Lampiran File Excel</label>
                <div id="drop-area"
                    class="flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau seret file ke sini untuk mengunggah</p>
                    <input id="lampiran_file" name="lampiran_file" type="file" accept=".xls,.xlsx" class="hidden" />
                </div>
                <p id="file-name" class="mt-2 text-sm text-gray-600"></p>
            </div>
        </div>
    </form>


    <x-slot:footer>
        <button type="button" @click="open = false"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            Batal
        </button>
        <button type="submit" form="formTambahExcelPengguna"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
            Simpan Pengguna
        </button>
    </x-slot:footer>
</x-modal-base>
