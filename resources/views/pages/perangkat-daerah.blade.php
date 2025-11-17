<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col md:flex-row p-7 md:justify-between md:items-center gap-4">
                    <h1 class="text-xl font-semibold">Notulensi</h1>
                    <div class="items-center flex">


                        <!-- Form Pencarian Pengguna -->
                        <form method="GET" action="{{ route('perangkat-daerah.index') }}" class="relative mr-3">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="search" name="search"
                                class="block w-64 rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-9 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                value="{{ request('search') }}" placeholder="Cari Perangkat Daerah" />
                        </form>

                        {{-- Modal membuat notulensi --}}
                        @include('pages.partials.modal-form.create-modal.perangkat-daerah-modal')


                    </div>
                </div>

                <div class="p-6">
                    <!-- Tabel -->
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full text-sm text-gray-600">
                            <thead class="bg-gray-100 text-gray-500 uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">No</th>
                                    <th class="px-4 py-3 text-left">Nama Perangkat Daerah</th>
                                    <th class="px-4 py-3 text-left">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($perangkat_daerah as $perangkat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">{{ $perangkat->nama_perangkat_daerah }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex justify-start space-x-3">
                                                @include('pages.partials.modal-form.edit-modal.perangkat-daerah-modal')
                                                <form action="{{ route('perangkat-daerah.destroy', $perangkat) }}"
                                                    method="POST" onsubmit="return confirmDelete(event, this)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="font-medium text-red-600 hover:text-red-800">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data perangkat daerah yang ditemukan.
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
