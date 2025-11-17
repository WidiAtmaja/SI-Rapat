<?php

namespace App\Http\Controllers;

use App\Models\PerangkatDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerangkatDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = PerangkatDaerah::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_perangkat_daerah', 'like', '%' . $search . '%');
            });
        }

        $perangkat_daerah = $query->get();

        return view('pages.perangkat-daerah', compact('perangkat_daerah'));
    }

    public function create()
    {
        // Hanya admin boleh akses
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat menambah Perangkat Daerah.');
        }

        return view('pages.perangkat-daerah-edit');
    }

    public function store(Request $request)
    {
        // Hanya admin boleh akses
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat menambah Perangkat Daerah.');
        }

        try {
            $validated = $request->validate([
                'nama_perangkat_daerah' => 'required|string|max:255',
            ]);

            PerangkatDaerah::create($validated);

            return redirect()
                ->route('perangkat-daerah.index')
                ->with('success', 'Perangkat Daerah berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan Perangkat Daerah: ' . $e->getMessage());
        }
    }

    public function edit(PerangkatDaerah $perangkat_daerah)
    {
        // Hanya admin boleh akses
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit Perangkat Daerah.');
        }

        return view('pages.perangkat-daerah-edit', compact('perangkat_daerah'));
    }

    public function update(Request $request, PerangkatDaerah $perangkat_daerah)
    {
        // Hanya admin boleh akses
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat memperbarui Perangkat Daerah.');
        }

        try {
            $validated = $request->validate([
                'nama_perangkat_daerah' => 'required|string|max:255',
            ]);

            $perangkat_daerah->update($validated);

            return redirect()
                ->route('perangkat-daerah.index')
                ->with('success', 'Perangkat Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui Perangkat Daerah: ' . $e->getMessage());
        }
    }

    public function destroy(PerangkatDaerah $perangkat_daerah)
    {
        // Hanya admin boleh akses
        if (Auth::user()->peran !== 'admin') {
            abort(403, 'Hanya admin yang dapat menghapus Perangkat Daerah.');
        }

        try {
            $perangkat_daerah->delete();

            return redirect()
                ->route('perangkat-daerah.index')
                ->with('success', 'Perangkat Daerah berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->route('perangkat-daerah.index')
                ->with('error', 'Gagal menghapus Perangkat Daerah: ' . $e->getMessage());
        }
    }
}
