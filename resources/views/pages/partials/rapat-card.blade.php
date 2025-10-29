@forelse ($rapat as $item)
    {{--  Card --}}
    <div class="max-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5">
            <div class="flex justify-between items-center">
                <h5 class="mb-2 text-lg font-semibold tracking-tight text-gray-900">
                    {{ $item->judul }}
                </h5>
                @if (auth()->user()->peran === 'admin')
                    <div class="relative inline-block text-left">
                        {{--  Dropdown --}}
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <div>
                                <button @click="open = !open" type="button"
                                    class="p-2 text-gray-700 hover:bg-gray-100 rounded-md inline-flex items-center justify-center"
                                    id="dropdownButton-{{ $item->id }}" aria-haspopup="true"
                                    :aria-expanded="open.toString()"> <svg xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 32 32" class="w-5 h-5 text-gray-700">
                                        <path d="M13,16c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,14.346,13,16z">
                                        </path>
                                        <path d="M13,26c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,24.346,13,26z">
                                        </path>
                                        <path d="M13,6c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,4.346,13,6z">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-44 z-10 origin-top-right bg-white divide-y divide-gray-100 rounded-lg shadow-sm border border-gray-200"
                                role="menu" aria-orientation="vertical"
                                aria-labelledby="dropdownButton-{{ $item->id }}" x-cloak>

                                <ul class="py-2 text-sm text-gray-700" role="none">
                                    <li>
                                        @include('pages.partials.modal-form.edit-modal.rapat-modal')
                                    </li>

                                    <li>
                                        <form action="{{ route('rapat.destroy', $item->id) }}" method="POST"
                                            onsubmit="confirmDelete(event,this)" role="none">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" @click="open = false"
                                                class="w-full font-semibold text-left px-4 py-2 hover:bg-gray-100 text-red-600"
                                                role="menuitem">
                                                Hapus
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <h1 class="text-xs">{{ $item->status }}</h1>

            <div class="py-4">
                <!-- Tanggal -->
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                </div>

                <!-- Pukul -->
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="#898989"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                        </path>
                        <path
                            d="M11 8.27 9.87 6.31l-1.73 1 3 5.2a1.016 1.016 0 0 0 1.13.47c.44-.12.74-.51.74-.97v-6h-2v2.27Z">
                        </path>
                    </svg>
                    <span class="text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->translatedFormat('H.i') }}
                        -
                        {{ \Carbon\Carbon::parse($item->waktu_selesai)->translatedFormat('H.i') }}
                        WITA</span>
                </div>

                <!-- Lokasi -->
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#898989"
                        viewBox="0 0 24 24">
                        <!--Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free-->
                        <path
                            d="M16 10c0-2.21-1.79-4-4-4s-4 1.79-4 4 1.79 4 4 4 4-1.79 4-4m-6 0c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2">
                        </path>
                        <path
                            d="M11.42 21.81c.17.12.38.19.58.19s.41-.06.58-.19c.3-.22 7.45-5.37 7.42-11.82 0-4.41-3.59-8-8-8s-8 3.59-8 8c-.03 6.44 7.12 11.6 7.42 11.82M12 4c3.31 0 6 2.69 6 6 .02 4.44-4.39 8.43-6 9.74-1.61-1.31-6.02-5.29-6-9.74 0-3.31 2.69-6 6-6">
                        </path>
                    </svg>
                    <span class="text-sm text-gray-600">{{ $item->lokasi }}</span>
                </div>

            </div>


            <div class="flex justify-between items-center">
                <a href="{{ route('rapat.show', $item->id) }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-blue-700 rounded-lg hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300">
                    Detail

                </a>
                <a href="{{ $item->link_zoom }}" target="_blank"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                    Bergabung
                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
@empty
    <h1 class="text-gray-500 text-sm text-center">
        Tidak ada rapat
    </h1>
@endforelse
