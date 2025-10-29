{{-- Modal Tambah Rapat dengan Trigger Button --}}
<x-modal-base title="Tambah Rapat" max-width="lg" :scrollable="true">
    <x-slot:trigger>
        {{-- Button trigger Anda tidak berubah --}}
        <button
            class="text-white w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            type="button">
            Buat Rapat
        </button>
    </x-slot:trigger>

    <form id="formTambahRapat" action="{{ route('rapat.store') }}" method="POST" enctype="multipart/form-data"
        x-data="{ startTime: '', endTime: '' }">
        @csrf
        <div class="grid gap-4 grid-cols-2">
            {{-- Judul (Tidak berubah) --}}
            <div class="col-span-2">
                <label for="judul" class="block mb-2 text-sm font-medium text-gray-900">
                    Judul
                </label>
                <input type="text" name="judul" id="judul"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan judul rapat" required>
            </div>

            {{-- Nama Perangkat Daerah (Tidak berubah) --}}
            <div class="col-span-2">
                <label for="nama_perangkat_daerah" class="block mb-2 text-sm font-medium text-gray-900">
                    Nama Perangkat Daerah
                </label>
                <input type="text" name="nama_perangkat_daerah" id="nama_perangkat_daerah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan perangkat daerah" required>
            </div>

            {{-- Tanggal (Tidak berubah) --}}
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
                <input type="time" name="waktu_mulai" id="waktu_mulai" step="300"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- Waktu Selesai --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="waktu_selesai" class="block mb-2 text-sm font-medium text-gray-900">
                    Waktu Selesai
                </label>
                <input type="time" name="waktu_selesai" id="waktu_selesai" :min="startTime" step="300"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            {{-- PIC (Tidak berubah) --}}
            <div class="col-span-2 sm:col-span-1">
                <label for="pic_id" class="block mb-2 text-sm font-medium text-gray-900">
                    PIC
                </label>
                <select name="pic_id" id="pic_id" required
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih PIC</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pesan Error (Bonus) --}}
            <div class="col-span-2" x-show="endTime && startTime && endTime < startTime">
                <p class="text-xs text-red-600">
                    Waktu selesai tidak boleh lebih awal dari waktu mulai.
                </p>
            </div>

            {{-- Link Zoom (Tidak berubah) --}}
            <div class="col-span-2">
                <label for="link_zoom" class="block mb-2 text-sm font-medium text-gray-900">
                    Link Zoom
                </label>
                <input type="url" name="link_zoom" id="link_zoom"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="https://zoom.us/j/..." required>
            </div>

            {{-- Lokasi (Tidak berubah) --}}
            <div class="col-span-2">
                <label for="lokasi" class="block mb-2 text-sm font-medium text-gray-900">
                    Lokasi
                </label>
                <input type="text" name="lokasi" id="lokasi"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Masukkan lokasi" required>
            </div>

            {{-- Status (Tidak berubah) --}}
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
        </div>
    </form>

    {{-- Footer Buttons (Tidak berubah) --}}
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
