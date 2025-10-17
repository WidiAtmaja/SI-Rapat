 {{--  Card --}}
 <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm">
     <div class="p-5">
         <div class="flex justify-between items-center">
             <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">
                 Lorem Ipsum
             </h5>
             <button id="dropdownDefaultButton1" data-dropdown-toggle="dropdown2" type="button"
                 class="p-2 text-gray-70 hover:bg-gray-100  rounded-md inline-flex items-center justify-center">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 32 32"
                     class="w-5 h-5 text-gray-700">
                     <path d="M13,16c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,14.346,13,16z"></path>
                     <path d="M13,26c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,24.346,13,26z"></path>
                     <path d="M13,6c0,1.654,1.346,3,3,3s3-1.346,3-3s-1.346-3-3-3S13,4.346,13,6z"></path>
                 </svg>
             </button>


             <!-- Dropdown menu -->
             <div id="dropdown2"
                 class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 border border-gray-200">
                 <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton1">
                     <li>
                         <a href="#" class="block px-4 py-2 hover:bg-gray-100">Edit</a>
                     </li>
                     <li>
                         <a href="#" class="block px-4 py-2 hover:bg-gray-100">Hapus</a>
                     </li>
                 </ul>
             </div>


         </div>

         <h1 class="text-xs">Akan Datang</h1>

         <div class="py-4">
             <div class="flex items-center gap-1.5">
                 <svg class="w-4 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                 </svg>
                 <span class="text-sm text-gray-600">Rabu, 27 Agustus 2025</span>
             </div>

             <!-- Pukul -->
             <div class="flex items-center gap-1.5">
                 <svg class="w-4 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                 </svg>
                 <span class="text-sm text-gray-600">18:00 Wita</span>
             </div>

             <!-- Lokasi -->
             <div class="flex items-center gap-1.5">
                 <svg class="w-4 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                 </svg>
                 <span class="text-sm text-gray-600">Online (Zoom)</span>
             </div>

         </div>



         <div class="flex justify-between items-center">
             <a href="#"
                 class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-blue-700 rounded-lg hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300">
                 Detail

             </a>
             <a href="#"
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
