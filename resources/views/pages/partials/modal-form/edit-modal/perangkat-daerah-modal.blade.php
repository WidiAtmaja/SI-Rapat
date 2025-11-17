{{-- Modal Perangkat Daerah --}}
<x-modal-base id="edit-modal-perangkat-daerah{{ $perangkat->id }}" title="Edit Perangkat Daerah{{ $perangkat->judul }}"
    max-width="lg" :scrollable="false">
    <x-slot:trigger>
        <button class="w-full text-left text-sm hover:bg-gray-100 text-blue-600 font-medium" type="button">
            Edit
        </button>
    </x-slot:trigger>

    <form id="formEditPerangkatDaerah-{{ $perangkat->id }}"
        action="{{ route('perangkat-daerah.update', $perangkat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid gap-4 grid-cols-2">
            {{-- Judul --}}
            <div class="col-span-2">
                <label for="nama_perangkat_daerah-{{ $perangkat->id }}"
                    class="block mb-2 text-sm font-medium text-gray-900">
                    Nama Perangkat Daerah
                </label>
                <input type="text" name="nama_perangkat_daerah" id="nama_perangkat_daerah-{{ $perangkat->id }}"
                    value="{{ old('nama_perangkat_daerah', $perangkat->nama_perangkat_daerah) }}"
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
        <button type="submit" form="formEditPerangkatDaerah-{{ $perangkat->id }}"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
            Simpan
        </button>
    </x-slot:footer>
</x-modal-base>
