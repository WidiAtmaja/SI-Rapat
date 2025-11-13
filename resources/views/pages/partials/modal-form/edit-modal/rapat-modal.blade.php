{{-- Modal Edit Rapat --}}
<x-modal-base id="edit-modal-rapat{{ $item->id }}" title="Edit Rapat {{ $item->judul }}" max-width="lg"
    :scrollable="true">

    <x-slot:trigger>
        <button class="w-full text-left px-4 text-sm hover:bg-gray-100 text-blue-600 font-medium" type="button">
            Edit
        </button>
    </x-slot:trigger>

    {{-- Form Edit Rapat --}}
    <form id="formEditRapat-{{ $item->id }}" action="{{ route('rapat.update', $item->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid gap-4 grid-cols-2">
            {{-- Judul --}}
            <div class="col-span-2">
                <label for="judul-{{ $item->id }}"
                    class="block mb-2 text-sm font-medium text-gray-900">Judul</label>
                <input type="text" name="judul" id="judul-{{ $item->id }}"
                    value="{{ old('judul', $item->judul) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan judul rapat" required>
            </div>

            {{-- Nama Perangkat Daerah --}}
            <div class="col-span-2">
                <label for="nama_perangkat_daerah-{{ $item->id }}"
                    class="block mb-2 text-sm font-medium text-gray-900">
                    Nama Perangkat Daerah
                </label>
                <input type="text" name="nama_perangkat_daerah" id="nama_perangkat_daerah-{{ $item->id }}"
                    value="{{ old('nama_perangkat_daerah', $item->nama_perangkat_daerah) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan perangkat daerah" required>
            </div>

            {{-- Tanggal --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="tanggal-{{ $item->id }}"
                    class="block mb-2 text-sm font-medium text-gray-900">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal-{{ $item->id }}"
                    value="{{ old('tanggal', $item->tanggal) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- Waktu Mulai --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="waktu_mulai-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">Waktu
                    Mulai</label>
                <input type="time" name="waktu_mulai" id="waktu_mulai-{{ $item->id }}"
                    value="{{ old('waktu_mulai', \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i')) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- Waktu Selesai --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="waktu_selesai-{{ $item->id }}"
                    class="block mb-2 text-sm font-medium text-gray-900">Waktu Selesai</label>
                <input type="time" name="waktu_selesai" id="waktu_selesai-{{ $item->id }}"
                    value="{{ old('waktu_selesai', \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i')) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- PIC --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="pic_id-{{ $item->id }}"
                    class="block mb-2 text-sm font-medium text-gray-900">PIC</label>
                <select name="pic_id" id="pic_id-{{ $item->id }}" required
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih PIC</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('pic_id', $item->pic_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Link Zoom --}}
            <div class="col-span-2">
                <label for="link_zoom-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">Link
                    Zoom</label>
                <input type="url" name="link_zoom" id="link_zoom-{{ $item->id }}"
                    value="{{ old('link_zoom', $item->link_zoom) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="https://zoom.us/j/..." required>
            </div>

            {{-- Lokasi --}}
            <div class="col-span-2">
                <label for="lokasi-{{ $item->id }}"
                    class="block mb-2 text-sm font-medium text-gray-900">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi-{{ $item->id }}"
                    value="{{ old('lokasi', $item->lokasi) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan lokasi" required>
            </div>

            {{-- Status --}}
            <div class="col-span-2">
                <label for="status-{{ $item->id }}"
                    class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                <select name="status" id="status-{{ $item->id }}" required
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="terjadwal" {{ old('status', $item->status) == 'terjadwal' ? 'selected' : '' }}>
                        Terjadwal</option>
                    <option value="sedang berlangsung"
                        {{ old('status', $item->status) == 'sedang berlangsung' ? 'selected' : '' }}>Sedang Berlangsung
                    </option>
                    <option value="selesai" {{ old('status', $item->status) == 'selesai' ? 'selected' : '' }}>Selesai
                    </option>
                    <option value="dibatalkan" {{ old('status', $item->status) == 'dibatalkan' ? 'selected' : '' }}>
                        Dibatalkan</option>
                </select>
            </div>

            {{-- File Materi --}}
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Materi</label>
                <div id="drop-area-materi-{{ $item->id }}"
                    class="drop-area-materi flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau seret file baru ke sini (untuk
                        mengganti)</p>
                    <input id="materi_file-edit-{{ $item->id }}" name="materi" type="file"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="hidden materi-file-edit" />
                </div>

                <p id="file-name-materi-{{ $item->id }}"
                    class="file-name-materi-display-edit mt-2 text-sm text-gray-600">
                    @if ($item->materi)
                        File saat ini: {{ $item->nama_file_asli ?? basename($item->materi) }}
                    @else
                        Tidak ada Materi terlampir.
                    @endif
                </p>
            </div>

            {{-- File Surat --}}
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Surat</label>
                <div id="drop-area-surat-{{ $item->id }}"
                    class="drop-area-surat flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <svg class="w-8 h-8 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-500">Klik atau seret file baru ke sini (untuk
                        mengganti)</p>
                    <input id="surat_file-edit-{{ $item->id }}" name="surat" type="file"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="hidden surat-file-edit" />
                </div>

                <p id="file-name-surat-{{ $item->id }}"
                    class="file-name-surat-display-edit mt-2 text-sm text-gray-600">
                    @if ($item->surat)
                        File saat ini: {{ $item->nama_file_asli ?? basename($item->surat) }}
                    @else
                        Tidak ada Surat terlampir.
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
        <button type="submit" form="formEditRapat-{{ $item->id }}"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
            Simpan Rapat
        </button>
    </x-slot:footer>
</x-modal-base>
