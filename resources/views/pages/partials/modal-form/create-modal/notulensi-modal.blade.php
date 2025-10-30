{{-- Modal Tambah Notulensi --}}
<x-modal-base title="Tambah Notulensi" max-width="lg" :scrollable="false">
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
            Buat Notulensi
        </button>
    </x-slot:trigger>

    {{-- Form Tambah Notulensi --}}
    <form id="formTambahNotulensi" action="{{ route('notulensi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid gap-4 grid-cols-2">
            {{-- Pilih Rapat --}}
            <div class="col-span-2">
                <label for="rapat_id" class="block mb-2 text-sm font-medium text-gray-900">
                    Pilih Rapat <span class="text-red-500">*</span>
                </label>
                <select name="rapat_id" id="rapat_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">-- Pilih Rapat --</option>
                    @php
                        // Ambil rapat yang belum punya notulensi
                        $rapatTersedia = \App\Models\Rapat::whereDoesntHave('notulensi')
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
                        Semua rapat sudah memiliki notulensi atau belum ada rapat yang dibuat.
                    </p>
                @endif
            </div>

            {{-- Ringkasan --}}
            <div class="col-span-2">
                <label for="ringkasan" class="block mb-2 text-sm font-medium text-gray-900">
                    Ringkasan Notulensi <span class="text-red-500">*</span>
                </label>
                <textarea id="ringkasan" name="ringkasan" rows="4" required
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Tulis ringkasan rapat di sini..."></textarea>
            </div>

            {{-- File --}}
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Lampiran File</label>
                <div id="drop-area"
                    class="flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau seret file ke sini untuk mengunggah</p>
                    <input id="lampiran_file" name="lampiran_file" type="file"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="hidden" />
                </div>
                <p id="file-name" class="mt-2 text-sm text-gray-600"></p>
                <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max: 20MB)</p>
            </div>
        </div>
    </form>

    {{-- Footer Buttons --}}
    <x-slot:footer>
        <button type="button" @click="open = false"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            Batal
        </button>
        <button type="submit" form="formTambahNotulensi"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition"
            @if ($rapatTersedia->isEmpty()) disabled @endif>
            Simpan Notulensi
        </button>
    </x-slot:footer>
</x-modal-base>
