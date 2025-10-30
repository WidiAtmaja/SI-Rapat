<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

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
        //validasi request sebelum rapat di post ke database
        $validated = $this->validateRapat($request);

        try {
            Rapat::create($validated);
            return redirect()->route('rapat.index')->with('success', 'Rapat berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan rapat. Silakan coba lagi.');
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

        try {
            $rapat->update($validated);
            return redirect()->route('rapat.index')->with('success', 'Rapat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui rapat. Silakan coba lagi.');
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
        ]);
    }
}
