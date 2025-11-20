<x-modal-base id="edit-modal-absensi-{{ $absen->id }}" title="Absensi: {{ $absen->rapat->judul }}" max-width="lg"
    :scrollable="true">

    <x-slot:trigger>
        @php
            $now = \Carbon\Carbon::now();

            $buka = $absen->rapat->datetime_absen_buka
                ? \Carbon\Carbon::parse($absen->rapat->datetime_absen_buka)
                : null;

            $tutup = $absen->rapat->datetime_absen_tutup
                ? \Carbon\Carbon::parse($absen->rapat->datetime_absen_tutup)
                : null;

            $isOpen = true;
            $statusMessage = '';
            $statusClass = '';

            if ($buka && $now->lessThan($buka)) {
                $isOpen = false;
                $statusMessage = 'Belum Dibuka (' . $buka->format('H:i') . ')';
                $statusClass = 'bg-yellow-500 cursor-not-allowed';
            } elseif ($tutup && $now->greaterThan($tutup)) {
                $isOpen = false;
                $statusMessage = 'Absensi Ditutup';
                $statusClass = 'bg-red-500 cursor-not-allowed';
            }
        @endphp

        @if (!$isOpen)
            <button type="button" disabled
                class="mr-4 text-white rounded-lg px-5 py-2.5 text-sm font-medium {{ $statusClass }}">
                {{ $statusMessage }}
            </button>
        @else
            <button type="button" @click="open = true"
                class="mr-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg px-5 py-2.5 text-sm font-medium shadow-sm transition-colors">
                {{ $absen->kehadiran == 'tidak hadir' ? 'Isi Absensi' : 'Ubah Kehadiran' }}
            </button>
        @endif
    </x-slot:trigger>

    {{-- FORM Absensi --}}
    <form id="formEditAbsensi-{{ $absen->id }}" action="{{ route('absensi.update', $absen->id) }}" method="POST"
        enctype="multipart/form-data" x-data="absensiForm({
            kehadiran: '{{ old('kehadiran', $absen->kehadiran) }}',
            hasOldSignature: {{ $absen->tanda_tangan ? 'true' : 'false' }},
            showCanvas: {{ $absen->tanda_tangan ? 'false' : 'true' }},
            photoPreview: '{{ $absen->foto_wajah ? Storage::url($absen->foto_wajah) : '' }}',
            zoomPreview: '{{ $absen->foto_zoom ? Storage::url($absen->foto_zoom) : '' }}'
        })" @submit.prevent="prepareSubmission">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            {{-- ===== INFO RAPAT ===== --}}
            <div class="p-2 rounded-xl border border-gray-100">
                <div class="flex items-center text-sm my-2">
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
                    <span class="text-gray-600 mx-2">
                        {{ \Carbon\Carbon::parse($absen->rapat->tanggal)->format('d M Y') }}</span>
                </div>
                <div class="flex items-center text-sm my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#323232"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10 10-4.49 10-10S17.51 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8">
                        </path>
                        <path d="M13 6h-2v6c0 .18.05.35.13.5l3 5.2 1.73-1-2.87-4.96V6.01Z">
                        </path>
                    </svg>
                    <span class="text-gray-600 mx-2">
                        {{ \Carbon\Carbon::parse($absen->rapat->waktu_mulai)->translatedFormat('H.i') }} -
                        {{ \Carbon\Carbon::parse($absen->rapat->waktu_selesai)->translatedFormat('H.i') }}
                        WITA</span>
                </div>
                <div class="flex items-center text-sm my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#323232"
                        viewBox="0 0 24 24">
                        <path
                            d="M16 10c0-2.21-1.79-4-4-4s-4 1.79-4 4 1.79 4 4 4 4-1.79 4-4m-6 0c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2">
                        </path>
                        <path
                            d="M11.42 21.81c.17.12.38.19.58.19s.41-.06.58-.19c.3-.22 7.45-5.37 7.42-11.82 0-4.41-3.59-8-8-8s-8 3.59-8 8c-.03 6.44 7.12 11.6 7.42 11.82M12 4c3.31 0 6 2.69 6 6 .02 4.44-4.39 8.43-6 9.74-1.61-1.31-6.02-5.29-6-9.74 0-3.31 2.69-6 6-6">
                        </path>
                    </svg>
                    <span class="text-gray-600 mx-2">{{ $absen->rapat->lokasi }}</span>
                </div>

                <div class="border-t border-gray-200 my-3"></div>

                @if ($buka && $tutup)
                    <div class="flex items-center justify-between text-xs bg-white p-2 rounded border border-gray-200">
                        <span class="text-gray-500">Batas Absensi:</span>
                        <span class="font-medium text-red-600"> {{ $absen->waktu_tutup_formatted }} WITA</span>
                    </div>
                @endif
            </div>

            {{-- ===== INPUT KEHADIRAN ===== --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                <select name="kehadiran" x-model="kehadiran"
                    class="w-full p-2.5 border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <option value="tidak hadir">Tidak Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="hadir">Hadir</option>
                </select>
            </div>

            {{-- ===== FORM BUKTI (Hanya jika Hadir) ===== --}}
            <div x-show="kehadiran === 'hadir'" x-cloak x-transition.opacity.duration.300ms class="space-y-5">

                {{-- 1. FOTO WAJAH --}}
                <div x-data="{
                    photoPreview: '{{ $absen->foto_wajah ? Storage::url($absen->foto_wajah) : '' }}',
                    fileName: '',
                    activeInput: 'none',
                    handleFile(e, type) {
                        const file = e.target.files[0];
                        if (file) {
                            this.fileName = file.name;
                            this.photoPreview = URL.createObjectURL(file);
                            this.activeInput = type;
                            if (type === 'camera') this.$refs.fileInput.value = '';
                            else this.$refs.camInput.value = '';
                        }
                    }
                }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">1. Foto Wajah (Selfie)</label>
                    <input x-ref="camInput" type="file" accept="image/*" capture="user" class="hidden"
                        @change="handleFile($event, 'camera')" :name="activeInput === 'camera' ? 'foto_wajah' : ''">
                    <input x-ref="fileInput" type="file" accept="image/*" class="hidden"
                        @change="handleFile($event, 'file')"
                        :name="activeInput === 'file' || activeInput === 'none' ? 'foto_wajah' : ''">

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="$refs.camInput.click()"
                            class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-gray-400 group-hover:text-blue-600 mb-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-xs font-medium text-gray-600 group-hover:text-blue-700">Ambil Foto</span>
                        </button>
                        <button type="button" @click="$refs.fileInput.click()"
                            class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-purple-500 hover:bg-purple-50 transition group">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-gray-400 group-hover:text-purple-600 mb-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs font-medium text-gray-600 group-hover:text-purple-700">Pilih
                                File</span>
                        </button>
                    </div>

                    <div x-show="photoPreview" class="mt-3">
                        <p class="text-xs text-gray-500 mb-1">Preview Foto Wajah:</p>
                        <div class="relative w-full h-48 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </div>
                        <p x-text="fileName" class="text-xs text-gray-500 mt-1 truncate"></p>
                    </div>
                </div>

                {{-- 2. SS ZOOM --}}
                <div x-data="{ zoomPreview: '{{ $absen->foto_zoom ? Storage::url($absen->foto_zoom) : '' }}', fileName: '' }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">2. Screenshot Zoom</label>
                    <label
                        class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 mb-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-xs text-gray-500"><span class="font-semibold">Klik upload</span> bukti zoom
                            </p>
                        </div>
                        <input type="file" name="foto_zoom" class="hidden" accept="image/*"
                            @change="fileName = $event.target.files[0].name; zoomPreview = URL.createObjectURL($event.target.files[0])" />
                    </label>
                    <div x-show="zoomPreview" class="mt-3">
                        <p class="text-xs text-gray-500 mb-1">Preview Zoom:</p>
                        <div
                            class="relative w-full h-32 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                            <img :src="zoomPreview" class="w-full h-full object-cover">
                        </div>
                        <p x-text="fileName" class="text-xs text-gray-500 mt-1 truncate"></p>
                    </div>
                </div>

                {{-- 3. TANDA TANGAN DIGITAL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">3. Tanda Tangan Digital</label>

                    {{-- A. Preview Tanda Tangan Lama (Jika Ada) --}}
                    @if ($absen->tanda_tangan)
                        <div class="mb-3 p-2 bg-gray-50 border rounded-lg" x-show="!showCanvas">
                            <p class="text-xs text-gray-500 mb-1">Tanda Tangan Saat Ini:</p>
                            <img src="{{ $absen->tanda_tangan }}"
                                class="h-24 object-contain border border-gray-200 bg-white rounded">

                            <button type="button" @click="showCanvas = true"
                                class="mt-2 text-xs text-blue-600 hover:underline flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Ubah Tanda Tangan
                            </button>
                        </div>
                    @endif

                    {{-- B. Canvas Tanda Tangan --}}
                    <div x-show="!hasOldSignature || showCanvas" x-cloak>
                        <div class="w-full h-40 bg-white border-2 border-gray-200 border-dashed rounded-xl overflow-hidden touch-none relative"
                            @mouseenter="resizeCanvas" @touchstart.passive="resizeCanvas">

                            <canvas x-ref="signatureCanvas" class="w-full h-full cursor-crosshair"></canvas>

                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20"
                                x-show="isCanvasEmpty">
                                <span class="text-gray-400 text-sm">Goreskan tanda tangan disini</span>
                            </div>
                        </div>

                        <div class="flex justify-between mt-1">
                            <div>
                                <button type="button" x-show="hasOldSignature"
                                    @click="showCanvas = false; clearSignature();"
                                    class="text-xs text-gray-500 hover:text-gray-700">
                                    Batal Ubah
                                </button>
                            </div>

                            <button type="button" @click.prevent="clearSignature"
                                class="text-xs flex items-center gap-1 text-red-500 hover:text-red-700 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Bersihkan Canvas
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="tanda_tangan">
                </div>
            </div>
        </div>
    </form>

    <x-slot:footer>
        <div class="flex justify-end gap-2 w-full">
            <button type="button" @click="open = false"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 transition">
                Batal
            </button>
            <button type="submit" form="formEditAbsensi-{{ $absen->id }}"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 shadow-sm transition">
                Simpan Absensi
            </button>
        </div>
    </x-slot:footer>
</x-modal-base>
