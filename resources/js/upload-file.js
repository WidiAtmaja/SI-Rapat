
        // 1. Ambil elemen-elemen yang kita butuhkan
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('lampiran_file');
        const fileNameDisplay = document.getElementById('file-name');

        // Cek apakah elemen-elemen ada (penting jika modal dimuat dinamis)
        if (dropArea && fileInput && fileNameDisplay) {
            // 2. Fungsikan klik pada 'dropArea'
            // Saat area abu-abu diklik, kita trigger input file yang tersembunyi
            dropArea.addEventListener('click', () => {
                fileInput.click();
            });

            // 3. Tampilkan nama file jika dipilih via dialog (setelah klik)
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    fileNameDisplay.textContent = `File: ${fileInput.files[0].name}`;
                    dropArea.classList.add('border-green-500'); // Umpan balik sukses
                    dropArea.classList.remove('border-blue-500'); 
                }
            });

            // 4. Handle Drag-over (saat file diseret di atas area)
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, (e) => {
                    e.preventDefault(); // Wajib agar event 'drop' bisa berfungsi
                    dropArea.classList.add('border-blue-500', 'bg-gray-100'); // Umpan balik visual
                });
            });

            // 5. Handle Drag-leave (saat file ditarik keluar dari area)
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropArea.classList.remove('border-blue-500', 'bg-gray-100'); // Hapus umpan balik
                });
            });

            // 6. Handle Drop (saat file dilepas di area)
            dropArea.addEventListener('drop', (e) => {
                // Ambil file dari event 'drop'
                const files = e.dataTransfer.files;

                if (files.length > 0) {
                    // **INI KUNCINYA:** Masukkan file yang di-drop ke dalam input form
                    fileInput.files = files; 
                    
                    // Tampilkan nama file
                    fileNameDisplay.textContent = `File: ${files[0].name}`;
                    dropArea.classList.add('border-green-500'); // Umpan balik sukses
                }
            });
        }



   
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Ambil SEMUA drop area 'edit' menggunakan class
        const allEditDropAreas = document.querySelectorAll('.drop-area-edit');

        // 2. Loop setiap drop area yang ditemukan
        allEditDropAreas.forEach(dropArea => {
            
            // 3. Temukan elemen input & display nama file yang SPESIFIK untuk drop area ini
            // Kita gunakan querySelector DARI 'dropArea' dan 'parentElement' nya
            const parentContainer = dropArea.parentElement;
            const fileInput = parentContainer.querySelector('.lampiran-file-edit');
            const fileNameDisplay = parentContainer.querySelector('.file-name-display-edit');
            
            // Simpan nama file asli untuk reset
            const originalFileName = fileNameDisplay ? fileNameDisplay.textContent : '';

            // Cek jika elemen ada
            if (!fileInput || !fileNameDisplay) {
                console.error("Tidak dapat menemukan input file atau display nama file untuk", dropArea);
                return; // Lanjut ke drop area berikutnya
            }

            // 4. Fungsikan klik pada 'dropArea'
            dropArea.addEventListener('click', () => {
                fileInput.click();
            });

            // 5. Tampilkan nama file jika dipilih via dialog (setelah klik)
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    fileNameDisplay.textContent = `File baru: ${fileInput.files[0].name}`;
                    dropArea.classList.add('border-green-500'); 
                    dropArea.classList.remove('border-blue-500');
                } else {
                    // Jika user batal pilih file, kembalikan ke nama file asli
                    fileNameDisplay.textContent = originalFileName;
                    dropArea.classList.remove('border-green-500');
                }
            });

            // 6. Handle Drag-over
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, (e) => {
                    e.preventDefault(); 
                    dropArea.classList.add('border-blue-500', 'bg-gray-100'); 
                });
            });

            // 7. Handle Drag-leave
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropArea.classList.remove('border-blue-500', 'bg-gray-100'); 
                });
            });

            // 8. Handle Drop
            dropArea.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    // Masukkan file ke input
                    fileInput.files = files; 
                    
                    // Tampilkan nama file baru
                    fileNameDisplay.textContent = `File baru: ${files[0].name}`;
                    dropArea.classList.add('border-green-500'); 
                }
            });

        });
    });


  
