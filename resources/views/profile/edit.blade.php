<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-semibold mb-4">Edit Profil</h2>

                    @if (session('status') === 'profile-updated')
                        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                            Profil berhasil diperbarui.
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                                <input id="nip" name="nip" type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('nip', $user->nip) }}" />
                                <x-input-error :messages="$errors->get('nip')" class="mt-1" />
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                                <input id="name" name="name" type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('name', $user->name) }}" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>

                            <div>
                                <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit
                                    Kerja</label>
                                <input id="unit_kerja" name="unit_kerja" type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('unit_kerja', $user->unit_kerja) }}" />
                                <x-input-error :messages="$errors->get('unit_kerja')" class="mt-1" />
                            </div>

                            <div>
                                <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                                <input id="jabatan" name="jabatan" type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('jabatan', $user->jabatan) }}" />
                                <x-input-error :messages="$errors->get('jabatan')" class="mt-1" />
                            </div>

                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input id="no_hp" name="no_hp" type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('no_hp', $user->no_hp) }}" />
                                <x-input-error :messages="$errors->get('no_hp')" class="mt-1" />
                            </div>

                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis
                                    Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-Laki"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>
                                        Laki-Laki</option>
                                    <option value="Perempuan"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-1" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="email" name="email" type="email"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('email', $user->email) }}" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru
                                    (opsional)</label>
                                <input id="password" name="password" type="password"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <x-primary-button>Simpan</x-primary-button>
                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-gray-600">Profil berhasil disimpan.</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
