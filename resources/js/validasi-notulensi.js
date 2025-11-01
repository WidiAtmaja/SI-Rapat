// Script Validasi Notulensi File
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('lampiran_file');
    const fileName = document.getElementById('file-name');

    // Klik pada area drop untuk membuka dialog file
    dropArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
            fileName.textContent = `File terpilih: ${file.name} (${fileSizeMB} MB)`;
            fileName.classList.add('text-green-600');
        } else {
            fileName.textContent = '';
        }
    });

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

    dropArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    }, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
});
