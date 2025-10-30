import Swal from 'sweetalert2';

// Fungsi untuk menampilkan alert berdasarkan session
window.showAlert = function() {
    // Cek success message
    const successMessage = document.querySelector('meta[name="success-message"]');
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage.content,
            confirmButtonColor: '#3085d6',
            timer: 2000,
            timerProgressBar: true
        });
    }

    // Cek error message
    const errorMessage = document.querySelector('meta[name="error-message"]');
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: errorMessage.content,
            confirmButtonColor: '#d33'
        });
    }

    // Cek warning message
    const warningMessage = document.querySelector('meta[name="warning-message"]');
    if (warningMessage) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: warningMessage.content,
            confirmButtonColor: '#f0ad4e'
        });
    }

    // Cek info message
    const infoMessage = document.querySelector('meta[name="info-message"]');
    if (infoMessage) {
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: infoMessage.content,
            confirmButtonColor: '#5bc0de'
        });
    }
};

// Fungsi untuk konfirmasi delete
window.confirmDelete = function(event, form) { // <-- 1. Tambahkan 'event' di sini
    event.preventDefault(); // <-- 2. 'event' sekarang sudah terdefinisi
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};

// Auto run ketika halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    const navigation = performance.getEntriesByType('navigation')[0];
    
    if (navigation && navigation.type === 'back_forward') {
        console.log('Page loaded from cache, skipping alert');
        return;
    }
    showAlert();
});

// Clear cache ketika pengguna meninggalkan halaman
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});