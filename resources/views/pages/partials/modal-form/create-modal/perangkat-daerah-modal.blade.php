{{-- Modal Perangkat Daerah --}}
<x-modal-base title="Perangkat Daerah" max-width="lg" :scrollable="false">
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
            Perangkat Daerah
        </button>
    </x-slot:trigger>

    <form id="formTambahPerangkatDaerah" action="{{ route('perangkat-daerah.store') }}" method="POST">
        @csrf
        <div class="grid gap-4 grid-cols-2">
            {{-- Judul --}}
            <div class="col-span-2">
                <label for="nama_perangkat_daerah" class="block mb-2 text-sm font-medium text-gray-900">
                    Nama Perangkat Daerah
                </label>
                <input type="text" name="nama_perangkat_daerah" id="nama_perangkat_daerah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan Perangkat Daerah" required>
            </div>

        </div>
    </form>

    <x-slot:footer>
        <button type="button" @click="open = false"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            Batal
        </button>
        <button type="submit" form="formTambahPerangkatDaerah"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
            Simpan
        </button>
    </x-slot:footer>
</x-modal-base>
