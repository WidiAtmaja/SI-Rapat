<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    //fungsi search global
    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();
        $userRole = optional($user)->role ?? 'guest';
        if (empty($query)) {
            return response()->json([
                'user_role' => $userRole,
                'rapat' => [],
                'absensis' => [],
                'notulensi' => []
            ]);
        }

        //mengambil query dari rapat
        $baseQuery = Rapat::where(function ($q) use ($query) {
            $q->where('judul', 'LIKE', "%{$query}%")
                ->orWhere('lokasi', 'LIKE', "%{$query}%");
        })->latest();

        $rapatResults = $baseQuery->clone()
            ->select('id', 'judul', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi', 'status')
            ->take(5)
            ->get();

        $absensiQuery = $baseQuery->clone()
            ->select('id', 'judul', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi');

        if ($user && $userRole == 'pegawai') {
            $absensiQuery->whereHas('absensis', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->with(['absensis' => function ($q) use ($user) {
                    $q->where('user_id', $user->id)->select('rapat_id', 'kehadiran');
                }]);
        } else {
            $absensiQuery->whereHas('absensis');
        }
        $absensiResults = $absensiQuery->take(5)->get();
        $notulensiResults = $baseQuery->clone()
            ->whereHas('notulensi')
            ->select('id', 'judul', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'lokasi')
            ->take(5)
            ->get();

        return response()->json([
            'user_role' => $userRole,
            'rapat' => $rapatResults,
            'absensis' => $absensiResults,
            'notulensi' => $notulensiResults,
        ]);
    }
}
