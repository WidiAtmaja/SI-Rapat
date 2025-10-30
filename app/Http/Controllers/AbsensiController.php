<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    //fungsi mengirim data absensi
    public function index(Request $request)
    {
        $user = Auth::user();
        $kehadiran = $request->query('kehadiran');
        $urutan = $request->query('urutan', 'terbaru');
        $sortDirection = ($urutan == 'terlama') ? 'asc' : 'desc';

        //menjalankan query absensi
        $query = Absensi::with(['user', 'rapat'])
            ->when($user->peran !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->when($user->peran !== 'admin' && $kehadiran && $kehadiran !== 'semua', function ($q) use ($kehadiran) {
                $q->where('kehadiran', $kehadiran);
            })
            ->orderBy('created_at', $sortDirection);
        $absensis = $query->get();

        if ($user->peran === 'admin') {
            $absensis = $absensis->unique('rapat_id')->values();
        }

        $rapatIds = $absensis->pluck('rapat_id')->unique();

        if ($rapatIds->isEmpty()) {
            return view('pages.absensi', compact('absensis'));
        }

        //melihat jumlah kehadiran pegawai
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
            ->keyBy('rapat_id');

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

    //fungsi post absensi ke database
    public function store(Request $request)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk membuat absensi.');
        }

        //validasi membuat absensi jika rapat sudah ada
        $validated = $request->validate([
            'rapat_id' => 'required|exists:rapats,id',
        ]);

        $exists = Absensi::where('rapat_id', $validated['rapat_id'])->exists();
        if ($exists) {
            return back()->with('error', 'Absensi untuk rapat ini sudah pernah dibuat.');
        }

        $pegawais = User::where('peran', 'pegawai')->pluck('id');
        $now = Carbon::now();

        //insert absensi pegawai
        $dataToInsert = $pegawais->map(function ($pegawaiId) use ($validated, $now) {
            return [
                'rapat_id'      => $validated['rapat_id'],
                'user_id'       => $pegawaiId,
                'kehadiran'     => 'tidak hadir',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        })->all();

        Absensi::insert($dataToInsert);
        return redirect()
            ->route('absensi.index')
            ->with('success', 'Absensi berhasil dibuat untuk semua pegawai.');
    }

    //fungsi melihat daftar absensi pegawai
    public function show(Rapat $rapat)
    {
        $rapat->load('absensis.user');
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

    //fungsi update absensi yang dapat dilakukan pegawai
    public function update(Request $request, Absensi $absensi)
    {
        $user = Auth::user();
        if ($user->peran === 'pegawai' && $absensi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah absensi ini.');
        }

        //validasi absensi melalui field kehadiran
        $validated = $request->validate([
            'kehadiran' => 'required|in:hadir,izin,tidak hadir',
        ]);

        $absensi->update($validated);
        return redirect()
            ->route('absensi.index')
            ->with('success', 'Kehadiran berhasil diperbarui.');
    }

    //fungsi hapus absensi dilakukan oleh admin
    public function destroy(Rapat $rapat)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus absensi.');
        }

        $rapat->absensis()->delete();

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Semua absensi untuk rapat ini berhasil dihapus.');
    }
}
