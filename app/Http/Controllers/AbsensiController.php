<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Menampilkan daftar absensi
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->peran === 'admin') {
            $absensis = Absensi::with(['user', 'rapat'])
                ->orderByDesc('created_at')
                ->get()
                ->unique('rapat_id');
        } else {
            $absensis = Absensi::with(['user', 'rapat'])
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        $rapatIds = $absensis->pluck('rapat_id')->unique();

        $statistics = Absensi::whereIn('rapat_id', $rapatIds)
            ->selectRaw('
                rapat_id,
                COUNT(*) as totalPegawai,
                SUM(CASE WHEN kehadiran = "hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN kehadiran = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN kehadiran = "tidak hadir" THEN 1 ELSE 0 END) as tidakHadir
            ')
            ->groupBy('rapat_id')
            ->get()
            ->keyBy('rapat_id');

        $absensis->each(function ($absen) use ($statistics) {
            $stats = $statistics->get($absen->rapat_id);
            $absen->totalPegawai = $stats ? $stats->totalPegawai : 0;
            $absen->hadir = $stats ? $stats->hadir : 0;
            $absen->izin = $stats ? $stats->izin : 0;
            $absen->tidakHadir = $stats ? $stats->tidakHadir : 0;
        });

        return view('pages.absensi', compact('absensis'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk membuat absensi.');
        }

        $validated = $request->validate([
            'rapat_id'      => 'required|exists:rapats,id',
        ]);

        $pegawais = User::where('peran', 'pegawai')->get();
        foreach ($pegawais as $pegawai) {
            Absensi::create([
                'rapat_id'      => $validated['rapat_id'],
                'user_id'       => $pegawai->id,
                'kehadiran'     => 'tidak hadir',
            ]);
        }

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Absensi berhasil dibuat untuk semua pegawai.');
    }

    /**
     * Menampilkan detail absensi berdasarkan rapat
     */
    public function show($rapatId)
    {
        $rapat = Rapat::findOrFail($rapatId);

        $absensis = Absensi::with('user')
            ->where('rapat_id', $rapatId)
            ->get();

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
     */
    public function update(Request $request, Absensi $absensi)
    {
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
     */
    public function destroy($rapatId)
    {
        $user = Auth::user();

        if ($user->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus absensi.');
        }

        Absensi::where('rapat_id', $rapatId)->delete();

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Absensi berhasil dihapus.');
    }
}
