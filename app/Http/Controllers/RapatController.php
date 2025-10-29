<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class RapatController extends Controller
{
    /**
     * Menampilkan daftar rapat (sudah termasuk filter).
     */
    public function index(Request $request)
    {
        $users = User::all(); // Untuk form/modal 'create'

        $status = $request->query('status');

        $query = Rapat::with(['pic'])
            // Gunakan when() agar lebih bersih
            ->when($status && $status !== 'semua', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest(); // Urutkan terbaru

        $rapat = $query->get();

        // Cek jika ini permintaan AJAX (untuk filter)
        if ($request->ajax()) {
            return view('pages.partials.rapat-list', compact('rapat'))->render();
        }

        // Jika bukan AJAX, load halaman penuh
        return view('pages.rapat', compact('rapat', 'users'));
    }

    /**
     * Simpan rapat baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRapat($request);

        try {
            Rapat::create($validated);
            return redirect()->route('rapat.index')->with('success', 'Rapat berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Sebaiknya log errornya juga
            // Log::error('Gagal simpan rapat: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan rapat. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan form edit.
     * Gunakan Route Model Binding
     */
    public function edit(Rapat $rapat)
    {
        $users = User::all();
        return view('pages.rapat-edit', compact('rapat', 'users'));
    }

    /**
     * Update data rapat.
     * Gunakan Route Model Binding
     */
    public function update(Request $request, Rapat $rapat): RedirectResponse
    {
        $validated = $this->validateRapat($request);

        try {
            $rapat->update($validated);
            return redirect()->route('rapat.index')->with('success', 'Rapat berhasil diperbarui!');
        } catch (\Exception $e) {
            // Log::error('Gagal update rapat: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui rapat. Silakan coba lagi.');
        }
    }

    /**
     * Detail rapat.
     * Gunakan Route Model Binding
     */
    public function show(Rapat $rapat)
    {
        // Eager load relasi
        $rapat->load(['pic', 'notulensi', 'absensis']);
        return view('pages.partials.detail-rapat', compact('rapat'));
    }

    /**
     * Hapus rapat beserta relasinya.
     * Gunakan Route Model Binding
     */
    public function destroy(Rapat $rapat): RedirectResponse
    {
        // Transaksi DB tidak lagi diperlukan di sini,
        // karena model event sudah menanganinya.
        try {
            // Cukup panggil delete().
            // Model Rapat akan otomatis menghapus absensi & notulensi.
            // Model Notulensi akan otomatis menghapus file di storage.
            $rapat->delete();

            return redirect()->route('rapat.index')->with('success', 'Rapat dan data terkait berhasil dihapus!');
        } catch (\Exception $e) {
            // Log::error('Gagal hapus rapat: ' . $e->getMessage());
            return redirect()->route('rapat.index')->with('error', 'Gagal menghapus rapat.');
        }
    }

    /**
     * Validasi input rapat (agar DRY).
     */
    private function validateRapat(Request $request): array
    {
        // Validasi Anda sudah bagus.
        return $request->validate([
            'judul' => 'required|string|max:255',
            'nama_perangkat_daerah' => 'required|string|max:255',
            'pic_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'link_zoom' => 'nullable|url|max:255', // Ganti ke 'url' agar lebih ketat
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:terjadwal,sedang berlangsung,selesai,dibatalkan',
        ]);
    }

    // Method filterRapat() bisa dihapus karena logikanya sudah digabung ke index()
    // public function filterRapat(Request $request) { ... }
}
