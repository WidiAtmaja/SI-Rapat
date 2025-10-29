<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan
use Carbon\Carbon; // Tambahkan

class AbsensiController extends Controller
{
    /**
     * Menampilkan daftar absensi (Refactored).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Ambil Filter
        $kehadiran = $request->query('kehadiran');
        $urutan = $request->query('urutan', 'terbaru');
        $sortDirection = ($urutan == 'terlama') ? 'asc' : 'desc';

        // 2. Query Gabungan (Admin & Pegawai)
        $query = Absensi::with(['user', 'rapat'])
            // Pegawai hanya lihat absensi mereka
            ->when($user->peran !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            // Filter kehadiran (hanya berlaku untuk pegawai)
            ->when($user->peran !== 'admin' && $kehadiran && $kehadiran !== 'semua', function ($q) use ($kehadiran) {
                $q->where('kehadiran', $kehadiran);
            })
            ->orderBy('created_at', $sortDirection);

        // 3. Eksekusi Query
        $absensis = $query->get();

        // 4. Logika 'Unique' untuk Admin (dijalankan di collection)
        if ($user->peran === 'admin') {
            // Ini masih tidak efisien (menarik semua data lalu di-filter di PHP)
            // Tapi ini sesuai logika Anda sebelumnya.
            $absensis = $absensis->unique('rapat_id')->values();
        }

        // --- Logika Statistik (Refactored) ---
        // Daripada query N+1, kita ambil semua statistik dalam 1 query
        $rapatIds = $absensis->pluck('rapat_id')->unique();

        if ($rapatIds->isEmpty()) {
            return view('pages.absensi', compact('absensis'));
        }

        // Query ini sudah efisien
        $statistics = Absensi::whereIn('rapat_id', $rapatIds)
            ->select(
                'rapat_id',
                DB::raw('COUNT(*) as totalPegawai'),
                DB::raw('SUM(CASE WHEN kehadiran = "hadir" THEN 1 ELSE 0 END) as hadir'),
                DB::raw('SUM(CASE WHEN kehadiran = "izin" THEN 1 ELSE 0 END) as izin'),
                DB::raw('SUM(CASE WHEN kehadiran = "tidak hadir" THEN 1 ELSE 0 END) as tidakHadir')
            )
            ->groupBy('rapat_id')
            ->get()
            ->keyBy('rapat_id'); // Jadikan rapat_id sebagai key

        // Map statistik ke collection $absensis
        $absensis->transform(function ($absen) use ($statistics) {
            $stats = $statistics->get($absen->rapat_id);
            if ($stats) {
                $absen->totalPegawai = $stats->totalPegawai;
                $absen->hadir = $stats->hadir;
                $absen->izin = $stats->izin;
                $absen->tidakHadir = $stats->tidakHadir;
            }
            return $absen;
        });

        return view('pages.absensi', compact('absensis'));
    }

    /**
     * Buat absensi untuk semua pegawai (Refactored untuk Performa)
     */
    public function store(Request $request)
    {
        // Otorisasi sudah benar
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk membuat absensi.');
        }

        $validated = $request->validate([
            'rapat_id' => 'required|exists:rapats,id',
        ]);

        // Cek jika absensi sudah pernah dibuat untuk rapat ini
        $exists = Absensi::where('rapat_id', $validated['rapat_id'])->exists();
        if ($exists) {
            return back()->with('error', 'Absensi untuk rapat ini sudah pernah dibuat.');
        }

        $pegawais = User::where('peran', 'pegawai')->pluck('id'); // Ambil ID saja
        $now = Carbon::now();

        // Siapkan data untuk bulk insert
        $dataToInsert = $pegawais->map(function ($pegawaiId) use ($validated, $now) {
            return [
                'rapat_id'      => $validated['rapat_id'],
                'user_id'       => $pegawaiId,
                'kehadiran'     => 'tidak hadir', // Default
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        })->all();

        // Lakukan 1x Query Insert (Jauh lebih cepat dari foreach)
        Absensi::insert($dataToInsert);

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Absensi berhasil dibuat untuk semua pegawai.');
    }

    /**
     * Menampilkan detail absensi berdasarkan rapat
     * Gunakan Route Model Binding
     */
    public function show(Rapat $rapat)
    {
        // Eager load absensi & user di dalam absensi
        $rapat->load('absensis.user');

        // Hitung statistik langsung dari collection
        $absensis = $rapat->absensis;
        $total = $absensis->count();
        $hadir = $absensis->where('kehadiran', 'hadir')->count();
        $izin = $absensis->where('kehadiran', 'izin')->count();
        $tidakHadir = $absensis->where('kehadiran', 'tidak hadir')->count();

        return view('pages.partials.detail-absensi', compact(
            'rapat',
            'absensis',
            'total',
            'hadir',
            'izin',
            'tidakHadir'
        ));
    }

    /**
     * Update kehadiran pegawai
     * (Kode Anda sudah bagus - menggunakan R-M-B)
     */
    public function update(Request $request, Absensi $absensi)
    {
        // Otorisasi (bisa dipindah ke Policy)
        $user = Auth::user();
        if ($user->peran === 'pegawai' && $absensi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah absensi ini.');
        }

        $validated = $request->validate([
            'kehadiran' => 'required|in:hadir,izin,tidak hadir',
        ]);

        $absensi->update($validated);

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Kehadiran berhasil diperbarui.');
    }

    /**
     * Hapus semua absensi berdasarkan rapat (admin only)
     * Gunakan Route Model Binding (terhadap Rapat)
     */
    public function destroy(Rapat $rapat)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus absensi.');
        }

        // Model Rapat kita sudah di-setting untuk cascade delete
        // tapi itu hanya jika Rapat-nya dihapus.
        // Jika hanya absensinya, kita lakukan manual.
        $rapat->absensis()->delete();

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Semua absensi untuk rapat ini berhasil dihapus.');
    }
}
