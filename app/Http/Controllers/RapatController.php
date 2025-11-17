<?php

namespace App\Http\Controllers;

use App\Models\PerangkatDaerah;
use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RapatController extends Controller
{
    //fungsi untuk mengirimkan data rapat ke views
    public function index(Request $request)
    {

        $perangkat_daerah = PerangkatDaerah::orderBy('nama_perangkat_daerah')->get();
        $users = User::where('peran', 'pegawai')->get();
        $status = $request->query('status');
        $user = Auth::user();
        $query = Rapat::with(['pic']);

        if ($user->peran == 'admin') {
            $query->when($status && $status !== 'semua', function ($q) use ($status) {
                $q->where('status', $status);
            });
        } else {
            $perangkatDaerahId = $user->perangkat_daerah_id;
            if ($perangkatDaerahId) {
                $query->whereHas('perangkatDaerahs', function ($q) use ($perangkatDaerahId) {
                    $q->where('perangkat_daerahs.id', $perangkatDaerahId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }

            // Filter status untuk pegawai
            $query->when($status && $status !== 'semua', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $rapat = $query->latest()->get();

        if ($request->ajax()) {
            return view('pages.partials.rapat-list', compact('rapat'))->render();
        }

        return view('pages.rapat', compact('rapat', 'users', 'perangkat_daerah'));
    }

    //fungsi untuk post data rapat ke database
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi request
        $validated = $this->validateRapat($request);

        $judul = $validated['judul'];
        $safeJudul = preg_replace('#[\\/?:*!"<>|]#', '-', $judul);

        DB::beginTransaction();
        try {
            // 2. Pisahkan data Rapat dari data relasi
            $rapatData = collect($validated)->except(['perangkat_daerah_ids', 'perangkat_daerah_custom'])->toArray();

            // 3. Handle file Materi
            if ($request->hasFile('materi')) {
                $file = $request->file('materi');
                $extension = $file->getClientOriginalExtension();
                $newFileName = $safeJudul . "-materi." . $extension;
                $path = $file->storeAs('materi', $newFileName, 'public');
                $rapatData['materi'] = $path; // Masukkan path ke data Rapat
            }

            // 4. Handle file Surat
            if ($request->hasFile('surat')) {
                $file = $request->file('surat');
                $extension = $file->getClientOriginalExtension();
                $newFileName = $safeJudul . "-surat." . $extension;
                $path = $file->storeAs('surat', $newFileName, 'public');
                $rapatData['surat'] = $path; // Masukkan path ke data Rapat
            }

            $rapat = Rapat::create($rapatData);

            $allPerangkatDaerahIds = $request->input('perangkat_daerah_ids', []);

            // Handle Kustom
            if ($request->has('perangkat_daerah_custom')) {
                foreach ($request->perangkat_daerah_custom as $namaCustom) {
                    if (!empty($namaCustom)) {
                        // Cari, jika tidak ada, buat baru
                        $perangkatDaerah = PerangkatDaerah::firstOrCreate(
                            ['nama_perangkat_daerah' => $namaCustom]
                        );
                        $allPerangkatDaerahIds[] = $perangkatDaerah->id;
                    }
                }
            }

            $rapat->perangkatDaerahs()->sync(array_unique($allPerangkatDaerahIds));

            DB::commit();

            return redirect()->route('rapat.index')->with('success', 'Rapat berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan rapat: ' . $e->getMessage());
        }
    }

    //fungsi edit
    public function edit(Rapat $rapat)
    {
        $users = User::all();
        return view('pages.rapat-edit', compact('rapat', 'users'));
    }

    //fungsi update rapat oleh admin
    public function update(Request $request, Rapat $rapat): RedirectResponse
    {
        //validasi setiap request sebelum update
        $validated = $this->validateRapat($request);

        // Definisikan $safeJudul di luar, agar bisa dipakai kedua file
        $judul = $validated['judul'];
        $safeJudul = preg_replace('#[\\/?:*!"<>|]#', '-', $judul);

        DB::beginTransaction();
        try {
            $rapatData = collect($validated)->except([
                'perangkat_daerah_ids',
                'perangkat_daerah_custom'
            ])->toArray();

            if ($request->hasFile('materi')) {
                if ($rapat->materi && Storage::disk('public')->exists($rapat->materi)) {
                    Storage::disk('public')->delete($rapat->materi);
                }
                $file = $request->file('materi');
                $extension = $file->getClientOriginalExtension();
                $newFileName = $safeJudul . "-materi." . $extension;
                $path = $file->storeAs('materi', $newFileName, 'public');

                $rapatData['materi'] = $path;
            }

            //Tambahkan logika untuk file SURAT
            if ($request->hasFile('surat')) {
                // 1. Hapus file surat lama (jika ada)
                if ($rapat->surat && Storage::disk('public')->exists($rapat->surat)) {
                    Storage::disk('public')->delete($rapat->surat);
                }
                // 2. Simpan file surat baru
                $file = $request->file('surat');
                $extension = $file->getClientOriginalExtension();
                $newFileName = $safeJudul . "-surat." . $extension;
                $path = $file->storeAs('surat', $newFileName, 'public');

                $rapatData['surat'] = $path;
            }

            // 2. Update data Rapat (tabel 'rapats')
            $rapat->update($rapatData);

            // 3. Proses Perangkat Daerah (SAMA SEPERTI STORE)
            $allPerangkatDaerahIds = $request->input('perangkat_daerah_ids', []);

            // Handle Kustom
            if ($request->has('perangkat_daerah_custom')) {
                foreach ($request->perangkat_daerah_custom as $namaCustom) {
                    if (!empty($namaCustom)) {
                        $perangkatDaerah = PerangkatDaerah::firstOrCreate(
                            ['nama_perangkat_daerah' => $namaCustom]
                        );
                        $allPerangkatDaerahIds[] = $perangkatDaerah->id;
                    }
                }
            }

            $rapat->perangkatDaerahs()->sync(array_unique($allPerangkatDaerahIds));

            DB::commit();

            return redirect()->route('rapat.index')->with('success', 'Rapat berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui rapat: ' . $e->getMessage());
        }
    }

    //fungsi menampilkan rapat untuk pengguna
    public function show(Rapat $rapat)
    {
        $rapat->load(['pic', 'notulensi', 'absensis', 'perangkatDaerahs']);
        return view('pages.partials.detail-rapat', compact('rapat'));
    }

    //fungsi menghapus rapat oleh admin
    public function destroy(Rapat $rapat): RedirectResponse
    {
        try {
            // Hapus file materi
            if ($rapat->materi && Storage::disk('public')->exists($rapat->materi)) {
                Storage::disk('public')->delete($rapat->materi);
            }

            //Tambahkan logika hapus file SURAT
            if ($rapat->surat && Storage::disk('public')->exists($rapat->surat)) {
                Storage::disk('public')->delete($rapat->surat);
            }

            $rapat->delete();
            return redirect()->route('rapat.index')->with('success', 'Rapat dan data terkait berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('rapat.index')->with('error', 'Gagal menghapus rapat.');
        }
    }

    //fungsi validasi rapat
    private function validateRapat(Request $request): array
    {
        //validasi request
        return $request->validate([
            'judul' => 'required|string|max:255',
            'pic_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'link_zoom' => 'nullable|url|max:255',
            'lokasi' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'status' => 'required|in:terjadwal,sedang berlangsung,selesai,dibatalkan',
            'materi' => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'surat' => 'nullable|file|max:20480|mimes:pdf,doc,docx',

            // --- TAMBAHAN VALIDASI WAKTU ABSEN ---
            'datetime_absen_buka' => 'nullable|date',
            'datetime_absen_tutup' => 'nullable|date|after_or_equal:datetime_absen_buka',

            // Validasi Baru
            'perangkat_daerah_ids' => 'nullable|array',
            'perangkat_daerah_ids.*' => 'exists:perangkat_daerahs,id',
            'perangkat_daerah_custom' => 'nullable|array',
            'perangkat_daerah_custom.*' => 'nullable|string|max:255',
        ]);
    }

    public function downloadRapat(Rapat $rapat)
    {
        if (!$rapat->materi) {
            return redirect()->back()->with('error', 'Tidak ada materi');
        }

        $filePathMateri = $rapat->materi;

        if (!Storage::disk('public')->exists($filePathMateri)) {
            return redirect()->back()->with('error', 'File materi tidak ditemukan di server.');
        }

        $fileNameMateri = basename($filePathMateri);
        return Storage::disk('public')->download($filePathMateri, $fileNameMateri);
    }

    public function downloadSurat(Rapat $rapat)
    {
        if (!$rapat->surat) {
            return redirect()->back()->with('error', 'Tidak ada surat');
        }

        $filePathSurat = $rapat->surat;

        if (!Storage::disk('public')->exists($filePathSurat)) {
            return redirect()->back()->with('error', 'File surat tidak ditemukan di server.');
        }

        $fileNameSurat = basename($filePathSurat);
        return Storage::disk('public')->download($filePathSurat, $fileNameSurat);
    }
}
