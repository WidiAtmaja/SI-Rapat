<?php

namespace App\Http\Controllers;

use App\Models\Notulen;
use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotulenController extends Controller
{
    public function index()
    {
        $user =  Auth::user();

        if ($user->peran === 'admin') {
            $notulens = Notulen::with(['rapat'])
                ->orderByDesc('created_at')
                ->get()
                ->unique('rapat_id')
                ->values();
        } else {
            $notulens = Notulen::with(['rapat'])
                ->whereHas('rapat.absensis', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderByDesc('created_at')
                ->get();
        }

        return view('pages.notulensi', compact('notulens'));
    }

    public function create($rapatId)
    {
        $user = Auth::user();

        // Hanya admin yang bisa membuat notulensi
        if ($user->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk membuat notulensi.');
        }

        $rapat = \App\Models\Rapat::findOrFail($rapatId);

        return view('pages.partials.create-notulensi', compact('rapat'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat menambahkan notulensi.');
        }

        $validated = $request->validate([
            'rapat_id' => 'required|exists:rapats,id',
            'ringkasan' => 'required|string',
            'lampiran_file' => 'nullable|file|max:2048|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);

        $fileName = null;


        $rapat = Rapat::findOrFail($validated['rapat_id']);

        if ($request->hasFile('lampiran_file')) {
            $extension = $request->file('lampiran_file')->getClientOriginalExtension();
            $safeTitle = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rapat->judul);
            $fileName = 'Notulensi_' . $safeTitle . '_' . date('Y-m-d') . '.' . $extension;

            $request->file('lampiran_file')->storeAs('notulensi', $fileName, 'public');
        }


        Notulen::create([
            'rapat_id' => $validated['rapat_id'],
            'user_id' => $user->id,
            'ringkasan' => $validated['ringkasan'],
            'lampiran_file' => $fileName,
        ]);


        return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil ditambahkan.');
    }


    public function show($rapatId)
    {
        $notulen = Notulen::with('rapat')->where('rapat_id', $rapatId)->firstOrFail();

        return view('pages.partials.detail-notulensi', compact('notulen'));
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->peran !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus notulensi.');
        }

        $notulen = Notulen::findOrFail($id);

        if ($notulen->lampiran_file && Storage::disk('public')->exists('notulensi/' . $notulen->lampiran_file)) {
            Storage::disk('public')->delete('notulensi/' . $notulen->lampiran_file);
        }

        $notulen->delete();

        return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil dihapus.');
    }

    public function download($id)
    {
        $notulen = Notulen::findOrFail($id);

        // Pastikan ada file lampiran
        if (!$notulen->lampiran_file) {
            return redirect()->back()->with('error', 'Tidak ada file lampiran untuk notulensi ini.');
        }

        $filePath = 'notulensi/' . $notulen->lampiran_file;

        // Pastikan file benar-benar ada di storage
        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File lampiran tidak ditemukan di server.');
        }

        // Kembalikan file sebagai download
        return Storage::disk('public')->download($filePath, $notulen->lampiran_file);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit notulensi.');
        }

        $notulen = Notulen::findOrFail($id);

        // Validasi input
        $request->validate([
            'ringkasan' => 'required|string',
            'lampiran_file' => 'nullable|file|max:2048|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);

        // Jika ada file baru diupload, hapus file lama
        if ($request->hasFile('lampiran_file')) {
            if ($notulen->lampiran_file && file_exists(public_path('storage/' . $notulen->lampiran_file))) {
                unlink(public_path('storage/' . $notulen->lampiran_file));
            }

            $file = $request->file('lampiran_file');
            $path = $file->store('notulensi', 'public');
            $notulen->lampiran_file = $path;
        }

        $notulen->ringkasan = $request->ringkasan;
        $notulen->save();

        return redirect()->back()->with('success', 'Notulensi berhasil diperbarui.');
    }
}
