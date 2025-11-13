// ===============================
// 🔹 UTAMA: Upload + Validasi File (Form Tambah)
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('lampiran_file');
    const fileNameDisplay = document.getElementById('file-name');

    if (dropArea && fileInput && fileNameDisplay) {

        // Klik pada area upload
        dropArea.addEventListener('click', () => fileInput.click());

        // Saat file dipilih
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);

                // Validasi ukuran max 10 MB
                if (file.size > 10 * 1024 * 1024) {
                    alert("Ukuran file terlalu besar! Maksimal 10 MB.");
                    fileInput.value = "";
                    fileNameDisplay.textContent = "";
                    dropArea.classList.remove('border-green-500');
                    return;
                }

                fileNameDisplay.textContent = `File: ${file.name} (${fileSizeMB} MB)`;
                dropArea.classList.add('border-green-500');
                dropArea.classList.remove('border-blue-500');
            } else {
                fileNameDisplay.textContent = "";
            }
        });

        // Drag & Drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.add('border-blue-500', 'bg-blue-50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.remove('border-blue-500', 'bg-blue-50');
            }, false);
        });

        dropArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
    }

    // ===============================
    // 🔹 FORM EDIT: Upload File Edit
    // ===============================
    const allEditDropAreas = document.querySelectorAll('.drop-area-edit');
    allEditDropAreas.forEach(dropArea => {

        const parent = dropArea.parentElement;
        const fileInput = parent.querySelector('.lampiran-file-edit');
        const fileNameDisplay = parent.querySelector('.file-name-display-edit');
        const originalFileName = fileNameDisplay ? fileNameDisplay.textContent : '';

        if (!fileInput || !fileNameDisplay) return;

        // Klik untuk pilih file
        dropArea.addEventListener('click', () => fileInput.click());

        // Saat file baru dipilih
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);

                if (file.size > 10 * 1024 * 1024) {
                    alert("Ukuran file terlalu besar! Maksimal 10 MB.");
                    fileInput.value = "";
                    fileNameDisplay.textContent = originalFileName;
                    dropArea.classList.remove('border-green-500');
                    return;
                }

                fileNameDisplay.textContent = `File baru: ${file.name} (${fileSizeMB} MB)`;
                dropArea.classList.add('border-green-500');
                dropArea.classList.remove('border-blue-500');
            } else {
                fileNameDisplay.textContent = originalFileName;
                dropArea.classList.remove('border-green-500');
            }
        });

        // Drag & Drop di form edit
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.add('border-blue-500', 'bg-blue-50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.remove('border-blue-500', 'bg-blue-50');
            }, false);
        });

        dropArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
});
