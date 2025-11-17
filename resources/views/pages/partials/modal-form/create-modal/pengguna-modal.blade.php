{{-- Modal Tambah Pengguna --}}
<x-modal-base title="Tambah Pengguna Baru" max-width="lg" :scrollable="true" :show="$errors->hasAny(['nip', 'name', 'email', 'password', 'peran', 'jabatan'])">

    <x-slot:trigger>
        <button type="button"
            class="flex items-center justify-center w-full text-blue-600 hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#3161f3" viewBox="0 0 24 24">
                <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path>
                <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8
                    8-8 8 3.59 8 8-3.59 8-8 8">
                </path>
            </svg>
            Tambah Manual
        </button>
    </x-slot:trigger>

    <form id="formTambahPengguna" action="{{ route('user.store') }}" method="POST">
        @csrf

        <div class="grid gap-4 grid-cols-2">

            {{-- NIP --}}
            <div class="col-span-2">
                <label for="nip_tambah" class="block mb-2 text-sm font-medium text-gray-900">NIP</label>
                <input type="text" name="nip" id="nip_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('nip') border-red-500 @enderror"
                    placeholder="Masukkan NIP pengguna">
                @error('nip')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div class="col-span-2">
                <label for="nama_tambah" class="block mb-2 text-sm font-medium text-gray-900">Nama</label>
                <input type="text" name="name" id="nama_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama Pengguna" required>
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Perangkat Daerah --}}
            <div class="col-span-2">
                <label for="perangkat_daerah_tambah" class="block mb-2 text-sm font-medium text-gray-900">
                    Perangkat Daerah
                </label>

                <select name="perangkat_daerah_id" id="perangkat_daerah_tambah" required
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Perangkat Daerah</option>

                    @foreach ($perangkat_daerah as $pd)
                        <option value="{{ $pd->id }}">{{ $pd->nama_perangkat_daerah }}</option>
                    @endforeach
                </select>
                @error('perangkat_daerah_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Unit Kerja --}}
            <div class="col-span-2">
                <label for="unit_kerja_tambah" class="block mb-2 text-sm font-medium text-gray-900">Unit Kerja</label>
                <input type="text" name="unit_kerja" id="unit_kerja_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('unit_kerja') border-red-500 @enderror"
                    placeholder="Masukan unit kerja">
                @error('unit_kerja')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div class="col-span-2">
                <label for="jabatan_tambah" class="block mb-2 text-sm font-medium text-gray-900">Jabatan</label>
                <input type="text" name="jabatan" id="jabatan_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('jabatan') border-red-500 @enderror"
                    placeholder="Masukan jabatan">
                @error('jabatan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor HP --}}
            <div class="col-span-2">
                <label for="no_hp_tambah" class="block mb-2 text-sm font-medium text-gray-900">Nomor HP</label>
                <input type="text" name="no_hp" id="no_hp_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('no_hp') border-red-500 @enderror"
                    placeholder="Masukan Nomor HP">
                @error('no_hp')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div class="col-span-2">
                <label for="jenis_kelamin_tambah" class="block mb-2 text-sm font-medium text-gray-900">Jenis
                    Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('jenis_kelamin') border-red-500 @enderror">
                    <option value="Laki-Laki">Laki-Laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="col-span-2">
                <label for="email_tambah" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" name="email" id="email_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('email') border-red-500 @enderror"
                    placeholder="Masukan Email" required>
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="col-span-2">
                <label for="password_tambah" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" name="password" id="password_tambah"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('password') border-red-500 @enderror"
                    placeholder="Masukan Password" required>
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="col-span-2">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi
                    Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    placeholder="Ketik ulang password" required>
            </div>

            {{-- Peran --}}
            <div class="col-span-2">
                <label for="peran_tambah" class="block mb-2 text-sm font-medium text-gray-900">Peran Pengguna</label>
                <select name="peran" id="peran_tambah" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('peran') border-red-500 @enderror">
                    <option value="pegawai">Pegawai</option>
                    <option value="admin">Admin</option>
                </select>
                @error('peran')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
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
