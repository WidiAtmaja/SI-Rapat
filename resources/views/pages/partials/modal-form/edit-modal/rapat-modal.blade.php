{{-- Modal Edit Rapat dengan Trigger Button --}}
<x-modal-base id="edit-modal-rapat{{ $item->id }}" {{-- <== INI YANG DIPERBAIKI --}} title="Edit Rapat {{ $item->judul }}"
    max-width="lg" :scrollable="true">

    <x-slot:trigger>
        {{-- Tombol trigger Anda (sekarang akan berfungsi) --}}
        <button class="w-full text-left px-4 py-2 hover:bg-gray-100 text-blue-600 font-semibold" type="button"
            data-modal-target="edit-modal-rapat{{ $item->id }}"
            data-modal-toggle="edit-modal-rapat{{ $item->id }}">
            Edit
        </button>
    </x-slot:trigger>

    {{-- Form Edit Rapat --}}
    <form id="formEditRapat-{{ $item->id }}" action="{{ route('rapat.update', $item->id) }}" method="POST"
        x-data="{
            startTime: '{{ old('waktu_mulai', $item->waktu_mulai) }}',
            endTime: '{{ old('waktu_selesai', $item->waktu_selesai) }}'
        }">
        @csrf
        @method('PUT')
        <div class="grid gap-4 grid-cols-2">
            {{-- Judul --}}
            <div class="col-span-2">
                <label for="judul-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    Judul
                </label>
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
                <label for="tanggal-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    Tanggal
                </label>
                <input type="date" name="tanggal" id="tanggal-{{ $item->id }}"
                    value="{{ old('tanggal', $item->tanggal) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- Waktu Mulai --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="waktu_mulai-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    Waktu Mulai
                </label>
                <input type="time" name="waktu_mulai" id="waktu_mulai-{{ $item->id }}" x-model="startTime"
                    step="300" {{-- step 5 menit --}}
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- Waktu Selesai --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="waktu_selesai-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    Waktu Selesai
                </label>
                <input type="time" name="waktu_selesai" id="waktu_selesai-{{ $item->id }}" x-model="endTime"
                    :min="startTime" step="300" {{-- step 5 menit --}}
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- PIC --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="pic_id-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    PIC
                </label>
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

            {{-- Error Waktu --}}
            <div class="col-span-2" x-show="endTime && startTime && endTime < startTime">
                <p class="text-xs text-red-600">
                    Waktu selesai tidak boleh lebih awal dari waktu mulai.
                </p>
            </div>

            {{-- Link Zoom --}}
            <div class="col-span-2">
                <label for="link_zoom-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    Link Zoom
                </label>
                <input type="url" name="link_zoom" id="link_zoom-{{ $item->id }}"
                    value="{{ old('link_zoom', $item->link_zoom) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="https://zoom.us/j/..." required>
            </div>

            {{-- Lokasi --}}
            <div class="col-span-2">
                <label for="lokasi-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    Lokasi
                </label>
                <input type="text" name="lokasi" id="lokasi-{{ $item->id }}"
                    value="{{ old('lokasi', $item->lokasi) }}"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan lokasi" required>
            </div>

            {{-- Status --}}
            <div class="col-span-2">
                <label for="status-{{ $item->id }}" class="block mb-2 text-sm font-medium text-gray-900">
                    Status
                </label>
                <select name="status" id="status-{{ $item->id }}" required
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="terjadwal" {{ old('status', $item->status) == 'terjadwal' ? 'selected' : '' }}>
                        Terjadwal
                    </option>
                    <option value="sedang berlangsung"
                        {{ old('status', $item->status) == 'sedang berlangsung' ? 'selected' : '' }}>
                        Sedang Berlangsung
                    </option>
                    <option value="selesai" {{ old('status', $item->status) == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>
                    <option value="dibatalkan" {{ old('status', $item->status) == 'dibatalkan' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>
                </select>
            </div>
        </div>
    </form>

    {{-- Footer Buttons --}}
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
