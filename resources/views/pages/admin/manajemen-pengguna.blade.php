<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex p-7 justify-between items-center">
                    <h1 class="text-xl font-semibold">Manajemen Pengguna</h1>
                    <div class="items-center flex">
                        <div class="flex-1 px-6 max-w-sm mr-auto sm:ml-8">
                            <form class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="search" id="default-search"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-9 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Cari..." />
                            </form>
                        </div>
                        <x-buttondropdown />
                    </div>

                </div>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs text-gray-600 border border-gray-200">
                            <thead class="bg-gray-100 text-gray-500 text-sm">
                                <tr>
                                    <th class="w-10 px-2 py-3 border border-gray-200 text-center">No</th>
                                    <th class="w-32 px-2 py-3 border border-gray-200 text-left">Nama</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">NIP</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Email</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Unit Kerja</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Password</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Jabatan</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Nomor Hp</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Jenis Kelamin</th>
                                    <th class="w-20 px-2 py-3 border border-gray-200 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-3 border border-gray-100 text-center">1</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">Widi Atmaja</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">1987877289</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">admin@admin.com</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">Persandian</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">**********</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">Penyelia</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">081529079865</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">Laki-Laki</td>
                                    <td class="px-2 py-3 border border-gray-100 text-center">Edit</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
