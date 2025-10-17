<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
                {{-- Kolom Kiri --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-50 p-6 h-screen flex flex-col">
                    <div class="flex justify-between items-center mb-10">
                        <h1 class="text-xl font-semibold">Jadwal Rapat</h1>
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                            Buat Rapat
                        </button>
                    </div>
                    <x-buttondropdown />

                    <div class="overflow-y-auto flex-1 space-y-4">
                        <x-card />
                        <x-card />
                        <x-card />
                        <x-card />
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="lg:col-span-3 space-y-4 ">
                    {{-- Section Atas --}}
                    <div
                        class="bg-white border-l-4 border-l-blue-500 rounded-xl shadow-sm border border-gray-50 overflow-hidden">
                        <!-- Header Section -->
                        <div class="p-4 border-b border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Agenda Sebelumnya</p>
                            <h2 class="text-lg font-semibold text-gray-900">Rapat Koordinator Bulanan</h2>
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
                                    class="text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                                    Salin Link
                                </button>
                                <button type="button"
                                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                                    Bergabung
                                </button>
                            </div>
                        </div>
                    </div>

                    <x-calendar />
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
