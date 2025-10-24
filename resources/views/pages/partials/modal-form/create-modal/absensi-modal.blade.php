 {{-- Modal Tambah Absensi dengan Trigger Button --}}
 <x-modal-base title="Tambah Absensi" max-width="lg" :scrollable="false">
     <x-slot:trigger>
         <button
             class="mr-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
             type="button">
             Buat Absensi
         </button>
     </x-slot:trigger>

     {{-- Form Tambah Absensi --}}
     <form id="formTambahAbsensi" action="{{ route('absensi.store') }}" method="POST">
         @csrf
         <div class="grid gap-4 grid-cols-2">
             {{-- Absensi --}}
             <div class="col-span-2">
                 <label for="pic_id" class="block mb-2 text-sm font-medium text-gray-900">
                     Pilih Rapat
                 </label>
                 <select name="rapat_id" id="rapat_id" required
                     class="bg-gray-50 border border-gray-300 placeholder-gray-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                     <option value="">Pilih Rapat</option>
                     @foreach (\App\Models\Rapat::orderBy('tanggal', 'desc')->get() as $rapat)
                         <option value="{{ $rapat->id }}">
                             {{ $rapat->judul }} - {{ $rapat->tanggal }}
                         </option>
                     @endforeach
                 </select>
             </div>

         </div>
     </form>

     <div class="p-3 mt-3 bg-blue-50 border border-blue-200 rounded-lg">
         <p class="text-sm text-blue-800">
             <strong>Info:</strong> Absensi akan dibuat untuk semua pegawai dengan status default
             "Tidak Hadir"
         </p>
     </div>

     {{-- Footer Buttons --}}
     <x-slot:footer>
         <button type="button" @click="open = false"
             class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
             Batal
         </button>
         <button type="submit" form="formTambahAbsensi"
             class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
             Simpan Absensi
         </button>
     </x-slot:footer>
 </x-modal-base>
