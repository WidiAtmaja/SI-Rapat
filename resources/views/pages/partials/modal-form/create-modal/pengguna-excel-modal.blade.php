<x-modal-base title="Tambah Pengguna Baru Excel" max-width="lg" :scrollable="false">
    <x-slot:trigger>
        <button
            class="text-white w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            type="button">
            Tambah Pengguna Excell
        </button>
    </x-slot:trigger>

    <form id="formTambahExcelPengguna" action="{{ route('user.import-excel') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="grid gap-4 grid-cols-2">
            {{-- File --}}
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
