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
    //fungsi mengirimkan data notulensi views
    public function index(Request $request)
    {
        $user = Auth::user();
        $urutan = $request->query('urutan', 'terbaru');
        $sortDirection = ($urutan == 'terlama') ? 'asc' : 'desc';
        $query = Notulen::with(['rapat']);

        if ($user->peran === 'admin') {
        } else {
            $perangkatDaerahId = $user->perangkat_daerah_id;

            if ($perangkatDaerahId) {
                $query->whereHas('rapat', function ($rapatQuery) use ($perangkatDaerahId) {
                    $rapatQuery->whereHas('perangkatDaerahs', function ($pdQuery) use ($perangkatDaerahId) {
                        $pdQuery->where('perangkat_daerahs.id', $perangkatDaerahId);
                    });
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $query->join('rapats', 'notulens.rapat_id', '=', 'rapats.id')
            ->select('notulens.*')
            ->orderBy('rapats.tanggal', $sortDirection);

        $notulens = $query->get();

        if ($user->peran === 'admin') {
            $notulens = $notulens->unique('rapat_id')->values();
        }

        return view('pages.notulensi', compact('notulens', 'user'));
    }

    //fungsi create
    public function create(Rapat $rapat)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat membuat notulensi.');
        }
        return view('pages.partials.create-notulensi', compact('rapat'));
    }

    //fungsi post data notulensi ke database oleh admin
    public function store(Request $request)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat menambahkan notulensi.');
        }
        //validasi setiap request sebelum di post ke database
        $validated = $request->validate([
            'rapat_id' => 'required|exists:rapats,id|unique:notulens,rapat_id',
            'ringkasan' => 'required|string',
            'lampiran_file' => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'rekaman' => 'nullable|url|max:255',
        ], [
            'rapat_id.unique' => 'Notulensi untuk rapat ini sudah pernah dibuat.'
        ]);

        try {
            $path = null;
            if ($request->hasFile('lampiran_file')) {
                $rapat = Rapat::findOrFail($validated['rapat_id']);
                $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rapat->judul);
                $extension = $request->file('lampiran_file')->getClientOriginalExtension();
                $fileName = "notulensi-{$safeName}." . $extension;
                $path = $request->file('lampiran_file')->storeAs('notulensi', $fileName, 'public');
            }

            //notulensi dibuat
            Notulen::create([
                'rapat_id' => $validated['rapat_id'],
                'user_id' => Auth::id(),
                'ringkasan' => $validated['ringkasan'],
                'lampiran_file' => $path,
                'rekaman' => $validated['rekaman'],
            ]);

            return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan notulensi: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan notulensi.');
        }
    }

    //fungsi menampilkan notulensi untuk pengguna
    public function show(Rapat $rapat)
    {
        $user = Auth::user();
        if ($user->peran !== 'admin') {
            $perangkatDaerahId = $user->perangkat_daerah_id;
            $rapat->load('perangkatDaerahs');
            $isInvited = $rapat->perangkatDaerahs->contains('id', $perangkatDaerahId);

            if (!$isInvited) {
                abort(403, 'Anda tidak memiliki izin untuk melihat notulensi rapat ini.');
            }
        } else {
            $rapat->load('perangkatDaerahs');
        }

        $notulen = Notulen::where('rapat_id', $rapat->id)->firstOrFail();
        $notulen->setRelation('rapat', $rapat);

        return view('pages.partials.detail-notulensi', compact('notulen'));
    }

    //fungsi edit
    public function edit(Notulen $notulen)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit notulensi.');
        }
        return view('pages.partials.edit-notulensi', compact('notulen'));
    }

    //fungsi update notulensi oleh admin
    public function update(Request $request, Notulen $notulen)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit notulensi.');
        }

        //validasi setiap request sebelum diupdate
        $validated = $request->validate([
            'ringkasan' => 'required|string',
            'lampiran_file' => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'rekaman' => 'nullable|url|max:255',
        ]);

        $notulen->ringkasan = $validated['ringkasan'];
        $notulen->rekaman = $validated['rekaman'];

        //jika ada file maka akan diganti atau diperbarui
        if ($request->hasFile('lampiran_file')) {
            if ($notulen->lampiran_file) {
                Storage::disk('public')->delete($notulen->lampiran_file);
            }

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

    //fungsi untuk menghapus notulensi oleh admin
    public function destroy(Notulen $notulen)
    {
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus notulensi.');
        }

        try {
            $notulen->delete();

            return redirect()->route('notulensi.index')->with('success', 'Notulensi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('notulensi.index')->with('error', 'Gagal menghapus notulensi.');
        }
    }

    //fungsi untuk mendownload lampiran file notulensi
    public function download(Notulen $notulen)
    {
        if (!$notulen->lampiran_file) {
            return redirect()->back()->with('error', 'Tidak ada file lampiran.');
        }

        $filePath = $notulen->lampiran_file;

        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File lampiran tidak ditemukan di server.');
        }

        $fileName = basename($filePath);
        return Storage::disk('public')->download($filePath, $fileName);
    }
}
