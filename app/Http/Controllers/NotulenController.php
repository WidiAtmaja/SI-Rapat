<?php

namespace App\Http\Controllers;

use App\Models\Notulen;
use App\Models\Rapat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NotulenController extends Controller
{

    /**
     * Menampilkan daftar notulensi (Refactored).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $urutan = $request->query('urutan', 'terbaru');
        $sortDirection = ($urutan == 'terlama') ? 'asc' : 'desc';

        $query = Notulen::with(['rapat'])
            // Pegawai hanya lihat notulen dari rapat yang mereka hadiri
            ->when($user->peran !== 'admin', function ($q) use ($user) {
                $q->whereHas('rapat.absensis', function ($subQ) use ($user) {
                    $subQ->where('user_id', $user->id);
                });
            })
            ->orderBy('created_at', $sortDirection);

        $notulens = $query->get();

        // Logika 'Unique' untuk Admin (dijalankan di collection)
        if ($user->peran === 'admin') {
            // Ini masih tidak efisien, tapi sesuai logika Anda
            $notulens = $notulens->unique('rapat_id')->values();
        }

        return view('pages.notulensi', compact('notulens'));
    }

    /**
     * Tampilkan form create
     */
    public function create(Rapat $rapat) // <-- Gunakan R-M-B
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat membuat notulensi.');
        }
        return view('pages.partials.create-notulensi', compact('rapat'));
    }

    /**
     * Simpan notulen baru (Refactored File Upload).
     */
    public function store(Request $request)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat menambahkan notulensi.');
        }

        $validated = $request->validate([
            'rapat_id' => 'required|exists:rapats,id|unique:notulens,rapat_id',
            'ringkasan' => 'required|string',
            'lampiran_file' => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
        ], [
            'rapat_id.unique' => 'Notulensi untuk rapat ini sudah pernah dibuat.'
        ]);

        try {
            $path = null;

            if ($request->hasFile('lampiran_file')) {
                $rapat = Rapat::findOrFail($validated['rapat_id']);

                // Bersihkan nama rapat dari karakter ilegal untuk nama file
                $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rapat->judul);

                // Ambil ekstensi asli file
                $extension = $request->file('lampiran_file')->getClientOriginalExtension();

                // Bentuk nama file yang rapi
                $fileName = "notulensi-{$safeName}." . $extension;

                // Simpan file tanpa hash
                $path = $request->file('lampiran_file')->storeAs('notulensi', $fileName, 'public');
            }

            Notulen::create([
                'rapat_id' => $validated['rapat_id'],
                'user_id' => Auth::id(),
                'ringkasan' => $validated['ringkasan'],
                'lampiran_file' => $path,
            ]);

            return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan notulensi: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan notulensi.');
        }
    }


    /**
     * Tampilkan detail notulen
     */
    public function show(Rapat $rapat)
    {
        // Cari notulen berdasarkan rapat_id, atau 404
        $notulen = Notulen::with('rapat')->where('rapat_id', $rapat->id)->firstOrFail();
        return view('pages.partials.detail-notulensi', compact('notulen'));
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Notulen $notulen)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit notulensi.');
        }
        return view('pages.partials.edit-notulensi', compact('notulen'));
    }

    /**
     * Update notulen (Refactored File Upload).
     */
    public function update(Request $request, Notulen $notulen)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit notulensi.');
        }

        $validated = $request->validate([
            'ringkasan' => 'required|string',
            'lampiran_file' => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
        ]);

        $notulen->ringkasan = $validated['ringkasan'];

        if ($request->hasFile('lampiran_file')) {
            // Hapus file lama
            if ($notulen->lampiran_file) {
                Storage::disk('public')->delete($notulen->lampiran_file);
            }

            // Gunakan nama rapat untuk nama file
            $rapat = $notulen->rapat;
            $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rapat->judul);
            $extension = $request->file('lampiran_file')->getClientOriginalExtension();
            $fileName = "notulensi-{$safeName}." . $extension;

            $path = $request->file('lampiran_file')->storeAs('notulensi', $fileName, 'public');
            $notulen->lampiran_file = $path;
        }

        $notulen->save();

        return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil diperbarui.');
    }

    /**
     * Hapus notulen (Refactored).
     */
    public function destroy(Notulen $notulen)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus notulensi.');
        }

        try {
            // Cukup panggil delete().
            // Model Notulen akan otomatis menghapus file di storage.
            $notulen->delete();

            return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil dihapus.');
        } catch (\Exception $e) {
            // Log::error('Gagal hapus notulen: ' . $e->getMessage());
            return redirect()->route('notulensi.index')->with('error', 'Gagal menghapus notulensi.');
        }
    }

    /**
     * Download lampiran (Kode Anda sudah bagus).
     */
    public function download(Notulen $notulen)
    {
        if (!$notulen->lampiran_file) {
            return redirect()->back()->with('error', 'Tidak ada file lampiran.');
        }

        $filePath = $notulen->lampiran_file; // Path sudah benar dari DB

        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File lampiran tidak ditemukan di server.');
        }

        // Ambil nama file asli, atau gunakan nama dari path
        $fileName = basename($filePath);
        return Storage::disk('public')->download($filePath, $fileName);
    }
}
