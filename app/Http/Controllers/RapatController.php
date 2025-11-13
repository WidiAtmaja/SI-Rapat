<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RapatController extends Controller
{
    //fungsi untuk mengirimkan data rapat ke views
    public function index(Request $request)
    {
        $users = User::all();
        $status = $request->query('status');
        $query = Rapat::with(['pic'])
            ->when($status && $status !== 'semua', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest();

        $rapat = $query->get();

        if ($request->ajax()) {
            return view('pages.partials.rapat-list', compact('rapat'))->render();
        }

        return view('pages.rapat', compact('rapat', 'users'));
    }

    //fungsi untuk post data rapat ke database
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRapat($request);

        // Definisikan $safeJudul di luar, agar bisa dipakai kedua file
        $judul = $validated['judul'];
        $safeJudul = preg_replace('#[\\/?:*!"<>|]#', '-', $judul);

        try {
            // Cek jika ada file materi di request
            if ($request->hasFile('materi')) {
                $file = $request->file('materi');
                $extension = $file->getClientOriginalExtension();

                $newFileName = $safeJudul . "-materi." . $extension;
                $path = $file->storeAs('materi', $newFileName, 'public');
                $validated['materi'] = $path;
            }

            // 🟢 PERBAIKAN: Tambahkan logika untuk file SURAT
            if ($request->hasFile('surat')) {
                $file = $request->file('surat');
                $extension = $file->getClientOriginalExtension();

                // Format nama file untuk surat
                $newFileName = $safeJudul . "-surat." . $extension;
                // Simpan di folder 'surat'
                $path = $file->storeAs('surat', $newFileName, 'public');
                $validated['surat'] = $path;
            }

            Rapat::create($validated);

            return redirect()->route('rapat.index')->with('success', 'Rapat berhasil ditambahkan!');
        } catch (\Exception $e) {
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

        try {
            // == Logika Update Materi (Sudah Benar) ==
            if ($request->hasFile('materi')) {

                // 1. Hapus file lama (jika ada)
                if ($rapat->materi && Storage::disk('public')->exists($rapat->materi)) {
                    Storage::disk('public')->delete($rapat->materi);
                }

                $file = $request->file('materi');
                $extension = $file->getClientOriginalExtension();

                $newFileName = $safeJudul . "-materi." . $extension;
                $path = $file->storeAs('materi', $newFileName, 'public');
                $validated['materi'] = $path;
            }

            // 🟢 PERBAIKAN: Tambahkan logika untuk file SURAT
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

                // 3. Update path di array validated
                $validated['surat'] = $path;
            }

            $rapat->update($validated);
            return redirect()->route('rapat.index')->with('success', 'Rapat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui rapat: ' . $e->getMessage());
        }
    }

    //fungsi menampilkan rapat untuk pengguna
    public function show(Rapat $rapat)
    {
        $rapat->load(['pic', 'notulensi', 'absensis']);
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

            // 🟢 PERBAIKAN: Tambahkan logika hapus file SURAT
            if ($rapat->surat && Storage::disk('public')->exists($rapat->surat)) {
                Storage::disk('public')->delete($rapat->surat);
            }
            // ========================================================

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
            'nama_perangkat_daerah' => 'required|string|max:255',
            'pic_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'link_zoom' => 'nullable|url|max:255',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:terjadwal,sedang berlangsung,selesai,dibatalkan',
            'materi' => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'surat' => 'nullable|file|max:20480|mimes:pdf,doc,docx',
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
