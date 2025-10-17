<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex p-7 justify-between items-center">
                    <h1 class="text-xl font-semibold">Notulensi</h1>
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
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <div
                            class="bg-white border-l-4 border-l-blue-500 rounded-xl shadow-sm border border-gray-50 overflow-hidden">
                            <!-- Header Section -->
                            <div class="flex p-4 border-b justify-between border-gray-100">
                                <h2 class="text-lg font-medium text-gray-900">Rapat Koordinator Bulanan</h2>
                                <h1
                                    class="px-2 py-0 text-xs rounded-lg bg-green-200 text-green-700 border border-green-400 font-bold">
                                    Hadir
                                </h1>
                            </div>
                            <!-- Content Section -->
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-4">
                                <!-- Info Section -->
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                                    <!-- Tanggal -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Rabu, 27 Agustus 2025</span>
                                    </div>

                                    <!-- Waktu -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">18:00</span>
                                    </div>

                                    <!-- Lokasi -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Online (Zoom)</span>
                                    </div>

                                    <!-- Peserta -->
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">4 Peserta</span>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="flex gap-2 mt-3 md:mt-0">
                                    <button type="button"
                                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                                        Lengkapi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
