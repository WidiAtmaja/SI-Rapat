<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Kolom kiri --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Card Judul Statistik --}}
        <div class="bg-white p-3 rounded-md shadow-sm text-center border border-gray-50">
            <h3 class="font-medium text-3xl text-green-700">
                Statistik Populasi Ternak Gopala Dwi Amerta Sari
            </h3>
        </div>

        {{-- Card Chart Historical Data --}}
        <div class="bg-white p-6 rounded-md shadow-sm border border-gray-50">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-medium text-gray-500">Perkembangan Populasi Ternak</h4>
                <div class="relative w-56">
                    <select id="anggotaSelect"
                        class="w-full border border-gray-300 rounded-md px-3 py-1.5 bg-white text-gray-700 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="" selected>Semua Anggota</option>
                        @foreach ($semuaAnggota as $anggota)
                            <option value="{{ $anggota->id }}">
                                {{ $anggota->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Bar Chart Historical --}}
            <div class="h-80">
                <div id="chart-loading" class="flex items-center justify-center h-full text-gray-500"
                    style="display:none;">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-700 mx-auto mb-2"
                            id="spinner"></div>
                        <p id="loading-text">Memuat data...</p>
                    </div>
                </div>
                <canvas id="historicalChart" class="w-full h-full"></canvas>
            </div>

            {{-- Legenda Chart --}}
            <div class="flex flex-col space-y-1 mt-3" id="chart-legend">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-green-800 rounded-sm"></span>
                    <span class="text-gray-500 text-sm">Sapi</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-gray-800 rounded-sm"></span>
                    <span class="text-gray-500 text-sm">Kambing</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom kanan --}}
    <div class="bg-white rounded-md shadow-sm border border-gray-50 p-6 space-y-4">

        {{-- Total Harga Awal Ternak --}}
        <div class="border rounded-md p-4">
            <h4 class="text-md font-medium text-gray-500 mb-2">Total Harga Awal Ternak</h4>
            <p class="text-2xl font-extrabold text-green-700">Rp {{ number_format($totalHargaTernak, 0, ',', '.') }}</p>
        </div>

        {{-- Total Jumlah Ternak --}}
        <div class="border rounded-md p-4">
            <h4 class="text-md font-medium text-gray-500 mb-2">Total Jumlah Ternak</h4>
            <p class="text-2xl font-extrabold text-green-700">{{ $totalJumlahTernak }}</p>
        </div>

        {{-- Chart Total Ternak - Pie Chart --}}
        <div class="border rounded-md p-4">
            <h4 class="text-md font-medium text-gray-500 mb-4">Persebaran Lokasi Kandang</h4>
            <div class="h-48 relative">
                <canvas id="kandangPieChart" class="w-full h-full"></canvas>
            </div>

            {{-- Legend Dinamis untuk Chart Kandang --}}
            <div id="kandang-legend" class="mt-3 space-y-2">
                {{-- Legend tidak ditampilkan sesuai permintaan --}}
            </div>
        </div>
    </div>
</div>
