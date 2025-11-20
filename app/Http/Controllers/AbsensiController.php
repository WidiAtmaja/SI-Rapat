<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Rapat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{

    //fungsi mengirim data absensi
    public function index(Request $request)
    {
        $user = Auth::user();
        $kehadiran = $request->query('kehadiran');
        $urutan = $request->query('urutan', 'terbaru');
        $sortDirection = ($urutan == 'terlama') ? 'asc' : 'desc';
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

        $query->orderBy('rapats.tanggal', $sortDirection);
        $query->join('rapats', 'absensis.rapat_id', '=', 'rapats.id')
            ->select('absensis.*');

        $absensis = $query->get();

        if ($user->peran === 'admin') {
            $absensis = $absensis->unique('rapat_id')->values();
        }

        $rapatIds = $absensis->pluck('rapat_id')->unique();

        if ($rapatIds->isEmpty()) {
            return view('pages.absensi', compact('absensis', 'user'));
        }

        //Ambil Statistik Kehadiran
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

        //TRANSFORMASI DATA (Statistik + Logika Waktu digabung DISINI)
        $absensis->transform(function ($absen) use ($statistics) {

            $stats = $statistics->get($absen->rapat_id);
            if ($stats) {
                $absen->totalPegawai = $stats->totalPegawai;
                $absen->hadir = $stats->hadir;
                $absen->izin = $stats->izin;
                $absen->tidakHadir = $stats->tidakHadir;
            }

            $now = Carbon::now();
            $rapat = $absen->rapat;

            // Parse tanggal
            $buka = $rapat->datetime_absen_buka ? Carbon::parse($rapat->datetime_absen_buka) : null;
            $tutup = $rapat->datetime_absen_tutup ? Carbon::parse($rapat->datetime_absen_tutup) : null;

            $absen->tutup = $tutup;

            // Hitung Status boolean
            $absen->isBelumBuka = $buka && $now->lessThan($buka);
            $absen->isSudahTutup = $tutup && $now->greaterThan($tutup);
            $absen->is_buka = !$absen->isBelumBuka && !$absen->isSudahTutup;

            // Hitung string sisa waktu
            $absen->sisa_waktu = ($absen->is_buka && $tutup)
                ? $tutup->diffForHumans($now, [
                    'parts' => 2,
                    'join' => true,
                    'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                ])
                : '';

            $absen->waktu_buka_formatted = $buka ? $buka->format('d M Y H:i') : '-';
            $absen->waktu_tutup_formatted = $tutup ? $tutup->format('d M Y H:i') : '-';
            $absen->waktu_tutup_lengkap = $tutup ? $tutup->format('d M Y H:i') : '-';

            return $absen;
        });

        return view('pages.absensi', compact('absensis', 'user'));
    }

    // Function store di 
    public function store(Request $request)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk membuat absensi.');
        }

        $validated = $request->validate([
            'rapat_id' => 'required|exists:rapats,id',
            'datetime_absen_buka' => 'required|date',
            'datetime_absen_tutup' => 'required|date|after:datetime_absen_buka',
        ]);

        $rapat_id = $validated['rapat_id'];

        // Cek apakah absensi sudah pernah dibuat
        $exists = Absensi::where('rapat_id', $rapat_id)->exists();
        if ($exists) {
            return back()->with('error', 'Absensi untuk rapat ini sudah pernah dibuat.');
        }

        $rapat = Rapat::with('perangkatDaerahs')->find($rapat_id);

        //UPDATE Waktu Absensi ke tabel Rapat
        $rapat->update([
            'datetime_absen_buka' => $validated['datetime_absen_buka'],
            'datetime_absen_tutup' => $validated['datetime_absen_tutup'],
        ]);

        $invitedPDIds = $rapat->perangkatDaerahs->pluck('id');
        if ($invitedPDIds->isEmpty()) {
            return back()->with('warning', 'Tidak ada Perangkat Daerah yang diundang. Absensi tidak dibuat.');
        }

        $pegawais = User::where('peran', 'pegawai')
            ->whereIn('perangkat_daerah_id', $invitedPDIds)
            ->pluck('id');

        if ($pegawais->isEmpty()) {
            return back()->with('warning', 'Tidak ada pegawai ditemukan. Absensi tidak dibuat.');
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
            ->with(
                'success',
                'Absensi berhasil dibuka dari ' .
                    Carbon::parse($validated['datetime_absen_buka'])->format('d M Y H:i') .
                    ' s/d ' .
                    Carbon::parse($validated['datetime_absen_tutup'])->format('d M Y H:i')
            );
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
        $absensi->load('rapat');
        $rapat = $absensi->rapat;

        // VALIDASI HAK AKSES DAN WAKTU
        if ($user->peran === 'pegawai') {
            if ($absensi->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki izin untuk mengubah absensi ini.');
            }

            //Cek jam buka/tutup absensi
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
        }
        // --- SELESAI VALIDASI ---

        //validasi absensi melalui field kehadiran
        $validated = $request->validate([
            'kehadiran' => 'required|in:hadir,izin,tidak hadir',
            'tanda_tangan' => 'nullable|string',
            'foto_wajah' => 'nullable|image|max:5120',
            'foto_zoom' => 'nullable|image|max:5120',
        ]);

        try {
            $absensi->kehadiran = $validated['kehadiran'];

            // Jika pegawai mengisi "Hadir"
            if ($validated['kehadiran'] === 'hadir') {
                if ($request->has('tanda_tangan')) {
                    $absensi->tanda_tangan = $request->input('tanda_tangan');
                }

                if ($request->hasFile('foto_wajah')) {
                    if ($absensi->foto_wajah && Storage::disk('public')->exists($absensi->foto_wajah)) {
                        Storage::disk('public')->delete($absensi->foto_wajah);
                    }
                    $path = $request->file('foto_wajah')->store('foto_wajah', 'public');
                    $absensi->foto_wajah = $path;
                }

                // 3. Simpan Foto Zoom
                if ($request->hasFile('foto_zoom')) {
                    if ($absensi->foto_zoom && Storage::disk('public')->exists($absensi->foto_zoom)) {
                        Storage::disk('public')->delete($absensi->foto_zoom);
                    }
                    $path = $request->file('foto_zoom')->store('ss_zoom', 'public');
                    $absensi->foto_zoom = $path;
                }
            } else {
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

    //fungsi hapus absensi dilakukan oleh admin(NON AKTIF)
    public function destroy(Rapat $rapat)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus absensi.');
        }

        return redirect()
            ->route('absensi.index')
            ->with('info', 'Fitur hapus absensi telah dinonaktifkan.');
    }

    //fungsi cetak absensi
    public function cetakAbsensiPDF(Rapat $rapat)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mencetak laporan ini.');
        }

        $rapat->load([
            'absensis' => function ($query) {
                $query->join('users', 'absensis.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('absensis.*');
            },
            'absensis.user.perangkatDaerah'
        ]);

        $absensisDikelompokkan = $rapat->absensis->groupBy(function ($absensi) {
            return $absensi->user->perangkatDaerah->nama_perangkat_daerah ?? 'Lainnya / Tidak Terdefinisi';
        });

        $absensisDikelompokkan = $absensisDikelompokkan->sortKeys();

        $data = [
            'rapat' => $rapat,
            'absensisDikelompokkan' => $absensisDikelompokkan,
        ];

        $pdf = Pdf::loadView('pages.pdf.cetak-absensi', $data);
        $pdf->setPaper('a4', 'landscape');
        $namaFile = 'daftar-hadir-' . Str::slug($rapat->nama_rapat) . '-' . $rapat->id . '.pdf';
        return $pdf->stream($namaFile);
    }
}
