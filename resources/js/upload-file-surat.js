document.addEventListener('DOMContentLoaded', function() {
    // ===============================
    // 🔹 FORM TAMBAH (satu area utama)
    // ===============================
    const dropArea = document.getElementById('drop-area-surat');
    const fileInput = document.getElementById('surat');
    const fileNameDisplay = document.getElementById('file-name-surat'); 

    if (dropArea && fileInput && fileNameDisplay) {
        setupFileUpload(dropArea, fileInput, fileNameDisplay, "File Surat");
    }

    // ===============================
    // 🔹 FORM EDIT (banyak area dinamis)
    // ===============================

    const editAreas = document.querySelectorAll('[id^="drop-area-surat-"]');
    editAreas.forEach((dropArea) => {
        const parent = dropArea.closest('.col-span-2') || dropArea.parentElement;
        const fileInput = parent.querySelector('.surat-file-edit');
        const fileNameDisplay = parent.querySelector('.file-name-surat-display-edit');
        const originalFileName = fileNameDisplay ? fileNameDisplay.textContent.trim() : '';

        if (fileInput && fileNameDisplay) {
            setupFileUpload(dropArea, fileInput, fileNameDisplay, "Surat baru", originalFileName);
        }
    });

    // ===============================
    // 🔧 Fungsi Reusable Upload File
    // ===============================
    function setupFileUpload(dropArea, fileInput, fileNameDisplay, label = "File", originalFileName = "") {
   
        dropArea.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => handleFileSelect());

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

        // Drop file langsung
        dropArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect();
            }
        });


        // Handler utama saat file dipilih / drop
        function handleFileSelect() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);

                if (file.size > 20 * 1024 * 1024) {
                    alert("Ukuran file terlalu besar! Maksimal 20 MB."); 
                    fileInput.value = "";
                    fileNameDisplay.textContent = originalFileName;
                    dropArea.classList.remove('border-green-500');
                    return;
                }

                const allowedExt = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']; 
                const ext = file.name.split('.').pop().toLowerCase();
                if (!allowedExt.includes(ext)) {
                    alert("Format file tidak didukung. Gunakan PDF, DOC, DOCX"); 
                    fileInput.value = "";
                    fileNameDisplay.textContent = originalFileName;
                    dropArea.classList.remove('border-green-500');
                    return;
                }

                fileNameDisplay.textContent = `${label}: ${file.name} (${fileSizeMB} MB)`;
                dropArea.classList.add('border-green-500');
                dropArea.classList.remove('border-blue-500');
            } else {
                fileNameDisplay.textContent = originalFileName;
                dropArea.classList.remove('border-green-500');
            }
        }

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
    }
});