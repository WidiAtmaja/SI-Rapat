<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Cari Rapat, Absensi, dan Notulensi berdasarkan query.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        // Gunakan optional() agar lebih aman
        $userRole = optional($user)->role ?? 'guest';

        // Jika query kosong, kembalikan hasil kosong
        if (empty($query)) {
            return response()->json([
                'user_role' => $userRole,
                'rapat' => [],
                'absensis' => [], // <-- ▼▼▼ UBAH INI ▼▼▼
                'notulensi' => []
            ]);
        }

        // 1. Buat query dasar
        $baseQuery = Rapat::where(function ($q) use ($query) {
            $q->where('judul', 'LIKE', "%{$query}%")
                ->orWhere('lokasi', 'LIKE', "%{$query}%");
        })->latest();

        // 2. Kategori "Rapat" (Sudah Benar)
        $rapatResults = $baseQuery->clone()
            ->select('id', 'judul', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi', 'status')
            ->take(5)
            ->get();

        // 3. Kategori "Absensi"
        $absensiQuery = $baseQuery->clone()
            ->select('id', 'judul', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi');

        if ($user && $userRole == 'pegawai') {
            // ▼▼▼ UBAH INI ▼▼▼
            $absensiQuery->whereHas('absensis', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                // ▼▼▼ UBAH INI ▼▼▼
                ->with(['absensis' => function ($q) use ($user) {
                    $q->where('user_id', $user->id)->select('rapat_id', 'kehadiran');
                }]);
        } else {
            // ▼▼▼ UBAH INI ▼▼▼
            $absensiQuery->whereHas('absensis');
        }

        $absensiResults = $absensiQuery->take(5)->get();

        // 4. Kategori "Notulensi" (Sudah Benar)
        $notulensiResults = $baseQuery->clone()
            ->whereHas('notulensi')
            ->select('id', 'judul', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi')
            ->take(5)
            ->get();

        // 5. Kembalikan semua hasil dalam format JSON
        return response()->json([
            'user_role' => $userRole,
            'rapat' => $rapatResults,
            'absensis' => $absensiResults, // <-- ▼▼▼ UBAH INI ▼▼▼
            'notulensi' => $notulensiResults,
        ]);
    }
}
