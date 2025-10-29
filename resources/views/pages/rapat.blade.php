<x-app-layout>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-4 lg:px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
                {{-- Kolom Kiri --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-50 p-6 h-screen flex flex-col">
                    <div class="flex justify-between items-center mb-10">
                        <h1 class="text-xl font-semibold">Jadwal Rapat</h1>
                        @if (auth()->user()->peran === 'admin')
                            @include('pages.partials.modal-form.create-modal.rapat-modal')
                        @endif
                    </div>


                    <div class="overflow-y-auto py-4 flex-1 space-y-4 items-center justify-center">
                        @include('pages.partials.rapat-card', ['rapat' => $rapat])
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="lg:col-span-3 space-y-4 ">
                    @include('pages.partials.kalender', ['rapat' => $rapat])
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
