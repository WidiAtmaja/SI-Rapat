//validasi form rapat
document.addEventListener("DOMContentLoaded", () => {
    // Ambil semua form edit & tambah rapat
    const forms = document.querySelectorAll('[id^="formEditRapat-"], [id^="formTambahRapat"]');

    forms.forEach(form => {
        form.addEventListener("submit", (e) => {
            let id = '';
            let waktuMulaiEl;
            let waktuSelesaiEl;

            // Jika form edit (ada tanda "-")
            if (form.id.includes('-')) {
                id = form.id.split("-")[1];
                waktuMulaiEl = document.getElementById(`waktu_mulai-${id}`);
                waktuSelesaiEl = document.getElementById(`waktu_selesai-${id}`);
            } else {
                // Form tambah (tanpa tanda "-")
                waktuMulaiEl = document.getElementById('waktu_mulai');
                waktuSelesaiEl = document.getElementById('waktu_selesai');
            }

            if (!waktuMulaiEl || !waktuSelesaiEl) return;

            const waktuMulai = waktuMulaiEl.value;
            const waktuSelesai = waktuSelesaiEl.value;

            if (waktuMulai && waktuSelesai && waktuSelesai < waktuMulai) {
                e.preventDefault();
                alert("⚠️ Waktu selesai tidak boleh lebih awal dari waktu mulai!");
            }
        });
    });
});
