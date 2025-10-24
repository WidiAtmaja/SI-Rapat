<?php

namespace App\Http\Controllers;

use App\Models\Rapat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RapatController extends Controller
{
    public function index()
    {
        $users = User::all();
        $rapat = Rapat::all();
        return view('pages.rapat', compact('rapat', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nama_perangkat_daerah' => 'required|string|max:255',
            'pic_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'link_zoom' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:terjadwal,sedang berlangsung,selesai,dibatalkan',
        ]);

        Rapat::create($request->all());
        return redirect()->route('rapat.index')->with('success', 'Rapat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $rapat = Rapat::findOrFail($id);
        $users = User::all();
        return view('pages.rapat-edit', compact('rapat', 'users'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nama_perangkat_daerah' => 'required|string|max:255',
            'pic_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'link_zoom' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:terjadwal,sedang berlangsung,selesai,dibatalkan',
        ]);

        $rapat = Rapat::findOrFail($id);
        $rapat->update($request->all());

        return redirect()->route('rapat.index')->with('success', 'Rapat berhasil diperbarui!');
    }

    public function show($id)
    {
        $rapat = Rapat::with('pic')->findOrFail($id);
        return view('pages.partials.detail-rapat', compact('rapat'));
    }

    public function destroy($id): RedirectResponse
    {
        $rapat = Rapat::findOrFail($id);
        $rapat->delete();

        return redirect()->route('rapat.index')->with('success', 'Rapat berhasil dihapus!');
    }
}
