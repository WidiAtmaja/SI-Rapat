<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
        $query = Absensi::with(['user', 'rapat']);

        if ($user->peran === 'admin') {
        } else {
            $perangkatDaerahId = $user->perangkat_daerah_id;
            $query->where('user_id', $user->id);

            if ($perangkatDaerahId) {
                $query->whereHas('rapat', function ($rapatQuery) use ($perangkatDaerahId) {
                    $rapatQuery->whereHas('perangkatDaerahs', function ($pdQuery) use ($perangkatDaerahId) {
                        $pdQuery->where('perangkat_daerahs.id', $perangkatDaerahId);
                    });
                });
            } else {

                $query->whereRaw('1 = 0');
            }

            $query->when($kehadiran && $kehadiran !== 'semua', function ($q) use ($kehadiran) {
                $q->where('kehadiran', $kehadiran);
            });
        }

        // Terapkan urutan
        $query->orderBy('rapats.tanggal', $sortDirection);
        $query->join('rapats', 'absensis.rapat_id', '=', 'rapats.id')
            ->select('absensis.*');

        $absensis = $query->get();

        if ($user->peran === 'admin') {
            // Terapkan filter unik untuk admin DI SINI
            $absensis = $absensis->unique('rapat_id')->values();
        }

        $rapatIds = $absensis->pluck('rapat_id')->unique();

        if ($rapatIds->isEmpty()) {
            // Tambahkan compact('user') untuk view jika perlu info user
            return view('pages.absensi', compact('absensis', 'user'));
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

        return view('pages.absensi', compact('absensis', 'user'));
    }

    //fungsi post absensi ke database
    public function store(Request $request)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk membuat absensi.');
        }

        $validated = $request->validate([
            'rapat_id' => 'required|exists:rapats,id',
        ]);

        $rapat_id = $validated['rapat_id']; // Simpan ID rapat

        $exists = Absensi::where('rapat_id', $rapat_id)->exists();
        if ($exists) {
            return back()->with('error', 'Absensi untuk rapat ini sudah pernah dibuat.');
        }

        $rapat = Rapat::with('perangkatDaerahs')->find($rapat_id);
        if (!$rapat) {
            return back()->with('error', 'Rapat tidak ditemukan.');
        }
        $invitedPDIds = $rapat->perangkatDaerahs->pluck('id');

        if ($invitedPDIds->isEmpty()) {
            return back()->with('warning', 'Tidak ada Perangkat Daerah yang diundang ke rapat ini. Absensi tidak dibuat.');
        }

        //Ambil ID pegawai HANYA dari Perangkat Daerah yang diundang
        $pegawais = User::where('peran', 'pegawai')
            ->whereIn('perangkat_daerah_id', $invitedPDIds)
            ->pluck('id');

        if ($pegawais->isEmpty()) {
            return back()->with('warning', 'Tidak ada pegawai yang ditemukan di Perangkat Daerah yang diundang. Absensi tidak dibuat.');
        }

        $now = Carbon::now();
        $dataToInsert = $pegawais->map(function ($pegawaiId) use ($rapat_id, $now) {
            return [
                'rapat_id'      => $rapat_id,
                'user_id'       => $pegawaiId,
                'kehadiran'     => 'tidak hadir',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        })->all();

        Absensi::insert($dataToInsert);

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Absensi berhasil dibuat untuk ' . $pegawais->count() . ' pegawai terkait.');
    }

    //fungsi melihat daftar absensi pegawai
    public function show(Rapat $rapat)
    {
        $rapat->load(['absensis.user.perangkatDaerah', 'perangkatDaerahs']);

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

        // Load relasi rapat untuk cek waktu
        $absensi->load('rapat');
        $rapat = $absensi->rapat;

        // --- VALIDASI HAK AKSES DAN WAKTU ---
        if ($user->peran === 'pegawai') {
            // 1. Cek kepemilikan absensi
            if ($absensi->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki izin untuk mengubah absensi ini.');
            }

            // 2. Cek jam buka/tutup absensi
            $now = Carbon::now();
            $buka = $rapat->datetime_absen_buka ? Carbon::parse($rapat->datetime_absen_buka) : null;
            $tutup = $rapat->datetime_absen_tutup ? Carbon::parse($rapat->datetime_absen_tutup) : null;

            // Jika admin mengatur waktunya
            if ($buka && $tutup) {
                if ($now->isBefore($buka)) {
                    return back()->with('error', 'Absensi untuk rapat ini belum dibuka.');
                }
                if ($now->isAfter($tutup)) {
                    return back()->with('error', 'Absensi untuk rapat ini sudah ditutup.');
                }
            }
            // Jika admin tidak mengatur, anggap selalu terbuka
        }
        // --- SELESAI VALIDASI ---

        //validasi absensi melalui field kehadiran
        $validated = $request->validate([
            'kehadiran' => 'required|in:hadir,izin,tidak hadir',
            'tanda_tangan' => 'nullable|string',
            'foto_wajah' => 'nullable|image|max:5120', // Max 5MB
            'foto_zoom' => 'nullable|image|max:5120', // Max 5MB
        ]);

        try {
            // Update kehadiran
            $absensi->kehadiran = $validated['kehadiran'];

            // Jika pegawai mengisi "Hadir"
            if ($validated['kehadiran'] === 'hadir') {

                // 1. Simpan Tanda Tangan (Base64)
                if ($request->filled('tanda_tangan')) {
                    $absensi->tanda_tangan = $request->tanda_tangan;
                }

                // 2. Simpan Foto Wajah
                if ($request->hasFile('foto_wajah')) {
                    // Hapus file lama jika ada
                    if ($absensi->foto_wajah && Storage::disk('public')->exists($absensi->foto_wajah)) {
                        Storage::disk('public')->delete($absensi->foto_wajah);
                    }
                    // Simpan file baru
                    $path = $request->file('foto_wajah')->store('foto_wajah', 'public');
                    $absensi->foto_wajah = $path;
                }

                // 3. Simpan Foto Zoom
                if ($request->hasFile('foto_zoom')) {
                    // Hapus file lama jika ada
                    if ($absensi->foto_zoom && Storage::disk('public')->exists($absensi->foto_zoom)) {
                        Storage::disk('public')->delete($absensi->foto_zoom);
                    }
                    // Simpan file baru
                    $path = $request->file('foto_zoom')->store('ss_zoom', 'public');
                    $absensi->foto_zoom = $path;
                }
            } else {
                // Jika "Izin" atau "Tidak Hadir", hapus bukti jika ada
                if ($absensi->foto_wajah && Storage::disk('public')->exists($absensi->foto_wajah)) {
                    Storage::disk('public')->delete($absensi->foto_wajah);
                }
                if ($absensi->foto_zoom && Storage::disk('public')->exists($absensi->foto_zoom)) {
                    Storage::disk('public')->delete($absensi->foto_zoom);
                }
                $absensi->foto_wajah = null;
                $absensi->foto_zoom = null;
                $absensi->tanda_tangan = null;
            }

            $absensi->save();

            return redirect()
                ->route('absensi.index')
                ->with('success', 'Kehadiran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
        }
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
