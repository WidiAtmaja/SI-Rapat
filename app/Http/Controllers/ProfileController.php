<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PerangkatDaerah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    //fungsi edit profil
    public function edit(Request $request): View
    {
        // 2. AMBIL DATA PERANGKAT DAERAH
        $perangkat_daerah = PerangkatDaerah::orderBy('nama_perangkat_daerah', 'asc')->get();

        // 3. KIRIM DATANYA KE VIEW
        return view('profile.edit', [
            'user' => $request->user(),
            'perangkat_daerah' => $perangkat_daerah, // <-- 4. TAMBAHKAN INI
        ]);
    }

    //fungsi untuk mengupdate profile
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        //validasi setiap request sebelum update
        $validated = $request->validate([
            'nip' => 'nullable|string|max:50|unique:users,nip,' . $user->id,
            'name' => 'required|string|max:255',
            'perangkat_daerah_id' => [
                Rule::requiredIf($user->peran !== 'admin'),
                'nullable',
                'exists:perangkat_daerahs,id'
            ],
            'unit_kerja' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->fill(array_diff_key($validated, array_flip(['password'])));

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        return back()->with('status', 'profile-updated');
    }

    //fungsi menghapus profile
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
