<x-modal-base title="Tambah Pengguna Baru" max-width="lg" :scrollable="true" {{-- Ini akan otomatis membuka modal jika ada error validasi dari form store --}} :show="$errors->hasAny(['nip', 'name', 'email', 'password', 'peran', 'jabatan'])">
    <x-slot:trigger>
        <button
            class="text-white w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            type="button">
            Tambah Pengguna Baru
        </button>
    </x-slot:trigger>

    <form id="formTambahPengguna" action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="grid gap-4 grid-cols-2">

            <div class="col-span-2">
                <label for="nip_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    NIP
                </label>
                <input type="text" name="nip" id="nip_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('nip') border-red-500 @enderror"
                    placeholder="Masukkan NIP pengguna" value="{{ old('nip') }}">
                @error('nip')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label for="nama_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Nama
                </label>
                <input type="text" name="name" id="nama_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama Pengguna" value="{{ old('name') }}" required>
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label for="unit_kerja_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Unit Kerja
                </label>
                <input type="text" name="unit_kerja" id="unit_kerja_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('unit_kerja') border-red-500 @enderror"
                    placeholder="Masukan unit kerja" value="{{ old('unit_kerja') }}">
                @error('unit_kerja')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label for="jabatan_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Jabatan
                </label>
                <input type="text" name="jabatan" id="jabatan_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('jabatan') border-red-500 @enderror"
                    placeholder="Masukan jabatan" value="{{ old('jabatan') }}">
                @error('jabatan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- [PERBAIKAN] Field PERAN ditambahkan (WAJIB) -->
            <div class="col-span-2">
                <label for="peran_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Peran Pengguna
                </label>
                <select name="peran" id="peran_tambah" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('peran') border-red-500 @enderror">
                    <option value="pegawai" {{ old('peran') == 'pegawai' ? 'selected' : '' }}>
                        Pegawai</option>
                    <option value="admin" {{ old('peran') == 'admin' ? 'selected' : '' }}>Admin
                    </option>
                </select>
                @error('peran')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label for="no_hp_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Nomor HP
                </label>
                <input type="text" name="no_hp" id="no_hp_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('no_hp') border-red-500 @enderror"
                    placeholder="Masukan Nomor HP" value="{{ old('no_hp') }}">
                @error('no_hp')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label for="jenis_kelamin_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Jenis Kelamin
                </label>
                <select name="jenis_kelamin" id="jenis_kelamin_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('jenis_kelamin') border-red-500 @enderror">
                    <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki
                    </option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                    </option>
                </select>
                @error('jenis_kelamin')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label for="email_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Email
                </label>
                <input type="email" name="email" id="email_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('email') border-red-500 @enderror"
                    placeholder="Masukan Email" value="{{ old('email') }}" required>
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label for="password_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Password
                </label>
                <input type="password" name="password" id="password_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('password') border-red-500 @enderror"
                    placeholder="Masukan Password" required>
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- [PERBAIKAN] Field KONFIRMASI PASSWORD ditambahkan (WAJIB) -->
            <div class="col-span-2">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">
                    Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Ketik ulang password" required>
            </div>

        </div>
    </form>

    <x-slot:footer>
        <button type="button" @click="open = false"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
            Batal
        </button>
        <button type="submit" form="formTambahPengguna"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
            Simpan Pengguna
        </button>
    </x-slot:footer>
</x-modal-base>
