<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
                {{-- Kolom Kiri --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-50 p-6 h-screen flex flex-col">
                    <div class="flex justify-between items-center mb-10">
                        <h1 class="text-xl font-semibold">Rapat</h1>
                        <!-- Modal membuat rapat -->
                        @if (auth()->user()->peran === 'admin')
                            @include('pages.partials.modal-form.create-modal.rapat-modal')
                        @endif
                    </div>
                    <div class="overflow-y-auto py-4 flex-1 space-y-4 items-center justify-center">
                        <!-- Card Rapat -->
                        @include('pages.partials.rapat-card', ['rapat' => $rapat])
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="lg:col-span-3 space-y-4 ">
                    <!-- Kalender -->
                    @include('pages.partials.kalender', ['rapat' => $rapat])
                </div>
            </div>

            <!-- Modal Detail Rapat -->
            <div id="detailRapatModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
                        <h2 class="text-lg font-bold text-gray-900">Detail Rapat</h2>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div id="modalContent" class="p-6">
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
