<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-50">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Kalender Rapat</h2>
        <div class="flex justify-between">
            {{-- dropdown Filter Button --}}
            <form action="{{ route('rapat.index') }}" method="GET">
                <div class="relative inline-block pr-3">
                    <select name="status" onchange="this.form.submit()"
                        class="appearance-none w-auto max-w-44 pl-3 pr-8 py-2.5 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50 cursor-pointer transition">
                        <option value="semua" @selected(request('status') == 'semua' || request('status') == '')>Semua Status</option>
                        <option value="terjadwal" @selected(request('status') == 'terjadwal')>Terjadwal</option>
                        <option value="sedang berlangsung" @selected(request('status') == 'sedang berlangsung')>Sedang Berlangsung</option>
                        <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                        <option value="dibatalkan" @selected(request('status') == 'dibatalkan')>Dibatalkan</option>
                    </select>
                </div>
            </form>

            <button id="prevBtn"
                class="p-2 text-gray-500 rounded transition-all duration-300 hover:bg-gray-100 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16"
                    fill="none">
                    <path d="M10.0002 11.9999L6 7.99971L10.0025 3.99719" stroke="currentcolor" stroke-width="1.3"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
            <button id="nextBtn"
                class="p-2 text-gray-500 rounded transition-all duration-300 hover:bg-gray-100 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16"
                    fill="none">
                    <path d="M6.00236 3.99707L10.0025 7.99723L6 11.9998" stroke="currentcolor" stroke-width="1.3"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
    </div>

    <section class="relative">
        <div class="w-full">
            <!-- Header Navigation -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 mb-5">
                <div class="flex items-center gap-4">
                    <h5 id="currentMonthYear" class="text-xl leading-8 font-semibold text-gray-900">Loading...</h5>
                </div>
                <div class="flex justify-between">
                    <button id="todayBtn"
                        class="py-2 px-6 mr-3 rounded-xl bg-gray-50 border border-gray-300 flex items-center gap-1.5 text-xs font-medium text-gray-900 transition-all duration-300 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#323232"
                            viewBox="0 0 24 24">
                            <path
                                d="m19,4h-2v-2h-2v2h-6v-2h-2v2h-2c-1.1,0-2,.9-2,2v14c0,1.1.9,2,2,2h14c1.1,0,2-.9,2-2V6c0-1.1-.9-2-2-2ZM5,20v-12h14v-2,14s-14,0-14,0Z">
                            </path>
                            <path d="M7 11H9V13H7z"></path>
                            <path d="M11 11H13V13H11z"></path>
                            <path d="M15 11H17V13H15z"></path>
                            <path d="M7 15H9V17H7z"></path>
                            <path d="M11 15H13V17H11z"></path>
                            <path d="M15 15H17V17H15z"></path>
                        </svg>
                        Hari Ini
                    </button>
                    <!-- View Switcher -->
                    <div class="flex py-2 px-2 items-center gap-px p-1 rounded-xl bg-gray-100">
                        <button data-view="day"
                            class="view-btn py-3 px-4 rounded-lg bg-gray-100 text-xs font-medium text-gray-900 transition-all duration-300 hover:bg-white">
                            Hari
                        </button>
                        <button data-view="week"
                            class="view-btn py-3 px-4 rounded-lg bg-gray-100 text-xs font-medium text-gray-900 transition-all duration-300 hover:bg-white">
                            Minggu
                        </button>
                        <button data-view="month"
                            class="view-btn py-3 px-4 rounded-lg bg-white text-xs font-medium text-gray-900 transition-all duration-300 hover:bg-white">
                            Bulan
                        </button>
                    </div>
                </div>

            </div>

            <!-- Calendar Container -->
            <div id="calendarContainer" class="overflow-x-auto">
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-gray-600">Memuat kalender...</span>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    window.rapatData = @json($rapat ?? []);
    console.log('Rapat data loaded:', window.rapatData);
</script>
