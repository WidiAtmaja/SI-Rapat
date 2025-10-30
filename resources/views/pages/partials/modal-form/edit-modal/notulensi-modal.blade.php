{{-- Modal Edit Notulensi --}}
<x-modal-base title="Edit Notulensi" max-width="lg" :scrollable="false">
    <x-slot:trigger>
        <button
            class="text-white bg-yellow-400 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            type="button">
            Edit
        </button>
    </x-slot:trigger>

    {{-- Form Edit Notulensi --}}
    <form id="formEdithNotulensi-{{ $item->id }}" action="{{ route('notulensi.update', $item->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid gap-4 grid-cols-2">
            {{-- Ringkasan --}}
            <div class="col-span-2">
                <label for="ringkasan-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    Ringkasan Notulensi <span class="text-red-500">*</span>
                </label>

                <textarea id="ringkasan-{{ $item->id }}" name="ringkasan" rows="4" required
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Tulis ringkasan rapat di sini...">{{ old('ringkasan', $item->ringkasan ?? '') }}</textarea>
            </div>

            {{-- File --}}
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Lampiran File (Opsional)</label>
                <div id="drop-area-edit-{{ $item->id }}"
                    class="drop-area-edit flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau seret file baru ke sini (untuk mengganti)</p>
                    <input id="lampiran_file-edit-{{ $item->id }}" name="lampiran_file" type="file"
                        accept=".pdf,.doc,.docx,.png,.jpg" class="hidden lampiran-file-edit" />
                </div>

                <p id="file-name-edit-{{ $item->id }}" class="file-name-display-edit mt-2 text-sm text-gray-600">
                    @if ($item->lampiran_file)
                        File saat ini: {{ $item->nama_file_asli ?? basename($item->lampiran_file) }}
                    @else
                        Tidak ada file terlampir.
                    @endif
                </p>
            </div>
        </div>
    </form>

    <x-slot:footer>
        <button type="button" @click="open = false"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            Batal
        </button>
        <button type="submit" form="formEdithNotulensi-{{ $item->id }}"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
            Simpan Perubahan
        </button>
    </x-slot:footer>
</x-modal-base>
