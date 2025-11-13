<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header -->
                <!-- Header -->
                <div class="p-7 border-b border-gray-200">
                    <h1 class="text-xl font-semibold text-gray-800 mb-4">Manajemen Pengguna</h1>

                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                        <!-- Kiri: Filter dan Search -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Filter Role Pengguna -->
                            <form action="{{ route('user.index') }}" method="GET" class="inline-block">
                                <input type="hidden" name="peran" value="{{ request('admin', 'pegawai') }}">
                                <div class="relative inline-block">
                                    <select name="peran" onchange="this.form.submit()"
                                        class="appearance-none w-auto max-w-48 pl-3 pr-8 py-2.5 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50 cursor-pointer transition">
                                        <option value="semua" @selected(request('peran') == 'semua' || !request('peran'))>Semua</option>
                                        <option value="admin" @selected(request('peran') == 'admin')>Admin</option>
                                        <option value="pegawai" @selected(request('peran') == 'pegawai')>Pegawai</option>
                                    </select>
                                </div>
                            </form>

                            <!-- Form Pencarian Pengguna -->
                            <form method="GET" action="{{ route('user.index') }}" class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="search" id="search" name="search"
                                    class="block w-64 rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-9 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ request('search') }}" placeholder="Cari Pengguna" />
                            </form>
                        </div>

                        <!-- Kanan: Tombol Aksi -->
                        <div class="flex justify-end mt-3 sm:mt-0">
                            <!-- Modal Tambah Pengguna -->
                            @include('pages.partials.modal-form.create-modal.pengguna-modal')
                            @include('pages.partials.modal-form.create-modal.pengguna-excel-modal')
                            <!-- Tombol Export Excel -->
                            <a href="{{ route('user.export') }}"
                                class="flex items-center text-green-800 hover:bg-green-10 focus:ring-4 focus:outline-none focus:ring-green-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#029C46"
                                    viewBox="0 0 24 24">
                                    <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4z"></path>
                                    <path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10
                                 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8
                                 s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                                    </path>
                                </svg>
                                <span class="ml-2">Export Excel</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Tabel -->
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full text-sm text-gray-600">
                            <thead class="bg-gray-100 text-gray-500 uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">No</th>
                                    <th class="px-4 py-3 text-left">NIP</th>
                                    <th class="px-4 py-3 text-left">Nama</th>
                                    <th class="px-4 py-3 text-left">Unit Kerja</th>
                                    <th class="px-4 py-3 text-left">Jabatan</th>
                                    <th class="px-4 py-3 text-left">No. HP</th>
                                    <th class="px-4 py-3 text-left">Jenis Kelamin</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Peran</th>
                                    <th class="px-4 py-3 text-left">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($users as $index => $pengguna)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">{{ $pengguna->nip }}</td>
                                        <td class="px-4 py-3">{{ $pengguna->name }}</td>
                                        <td class="px-4 py-3">{{ $pengguna->unit_kerja }}</td>
                                        <td class="px-4 py-3">{{ $pengguna->jabatan }}</td>
                                        <td class="px-4 py-3">{{ $pengguna->no_hp }}</td>
                                        <td class="px-4 py-3">{{ $pengguna->jenis_kelamin }}</td>
                                        <td class="px-4 py-3">{{ $pengguna->email }}</td>
                                        <td class="px-4 py-3 text-left">
                                            @if ($pengguna->peran == 'admin')
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Admin
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Pegawai
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex justify-start space-x-3">
                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('user.destroy', $pengguna) }}" method="POST"
                                                    onsubmit="return confirm('Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="font-medium text-red-600 hover:text-red-800">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data pengguna yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
