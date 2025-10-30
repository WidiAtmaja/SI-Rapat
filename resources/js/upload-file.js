// Elemen utama
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('lampiran_file');
const fileNameDisplay = document.getElementById('file-name');

// Fungsi klik & drag-drop untuk form utama
if (dropArea && fileInput && fileNameDisplay) {
    dropArea.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = `File: ${fileInput.files[0].name}`;
            dropArea.classList.add('border-green-500');
            dropArea.classList.remove('border-blue-500');
        }
    });

    // Handle Drag-over (saat file diseret di atas area)
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropArea.classList.add('border-blue-500', 'bg-gray-100');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropArea.classList.remove('border-blue-500', 'bg-gray-100');
        });
    });

    // Handle Drop (saat file dilepas di area)
    dropArea.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileNameDisplay.textContent = `File: ${files[0].name}`;
            dropArea.classList.add('border-green-500');
        }
    });
}

// Script untuk lampiran file di form Edit
document.addEventListener('DOMContentLoaded', function() {
    const allEditDropAreas = document.querySelectorAll('.drop-area-edit');
    allEditDropAreas.forEach(dropArea => {

        const parentContainer = dropArea.parentElement;
        const fileInput = parentContainer.querySelector('.lampiran-file-edit');
        const fileNameDisplay = parentContainer.querySelector('.file-name-display-edit');
        const originalFileName = fileNameDisplay ? fileNameDisplay.textContent : '';

        if (!fileInput || !fileNameDisplay) {
            console.error("Tidak dapat menemukan input file atau display nama file untuk", dropArea);
            return;
        }

        dropArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Tampilkan nama file jika dipilih via dialog
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = `File baru: ${fileInput.files[0].name}`;
                dropArea.classList.add('border-green-500');
                dropArea.classList.remove('border-blue-500');
            } else {
                fileNameDisplay.textContent = originalFileName;
                dropArea.classList.remove('border-green-500');
            }
        });

        // Handle Drag-over
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropArea.classList.add('border-blue-500', 'bg-gray-100');
            });
        });

        // Handle Drag-leave
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropArea.classList.remove('border-blue-500', 'bg-gray-100');
            });
        });

        // Handle Drop
        dropArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                fileNameDisplay.textContent = `File baru: ${files[0].name}`;
                dropArea.classList.add('border-green-500');
            }
        });
    });
});