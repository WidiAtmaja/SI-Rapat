<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Rapat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    //fungsi index pengguna dan pencarian pengguna
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%')
                    ->orWhere('jabatan', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('peran') && $request->peran != '' && $request->peran !== 'semua') {
            $query->where('peran', $request->peran);
        }

        $users = $query->get();
        return view('pages.manajemen-pengguna', compact('users'));
    }

    public function create()
    {
        return view('pages.partials.create-user');
    }

    //fungsi membuat akun pengguna
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'nip' => 'nullable|string|max:50|unique:users',
            'unit_kerja' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'peran' => 'required|in:admin,pegawai',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'nip' => $validated['nip'],
                'unit_kerja' => $validated['unit_kerja'],
                'jabatan' => $validated['jabatan'],
                'peran' => $validated['peran'],
                'no_hp' => $validated['no_hp'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
            ]);

            if ($user->peran === 'pegawai') {

                $rapatIds = Rapat::pluck('id');
                $now = Carbon::now();

                $data = $rapatIds->map(fn($rapatId) => [
                    'rapat_id' => $rapatId,
                    'user_id' => $user->id,
                    'kehadiran' => 'tidak hadir',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->toArray();

                if (count($data)) {
                    Absensi::insert($data);
                }
            }

            return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan dan absensi otomatis dibuat.');
        } catch (\Exception $e) {
            \Log::error('Gagal simpan user: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
        }
    }

    //fungsi edit
    public function edit(User $user)
    {
        return view('pages.partials.edit-user', compact('user'));
    }

    //fungsi update pengguna
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user->id),
            ],
            'unit_kerja' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'peran' => 'required|in:admin,pegawai',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
        ]);

        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->nip = $validated['nip'];
            $user->unit_kerja = $validated['unit_kerja'];
            $user->jabatan = $validated['jabatan'];
            $user->peran = $validated['peran'];
            $user->no_hp = $validated['no_hp'];
            $user->jenis_kelamin = $validated['jenis_kelamin'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update user: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui data pengguna. Silakan coba lagi.');
        }
    }

    //fungsi hapus pengguna
    public function destroy(User $user)
    {
        try {
            if ($user->id === Auth::id()) {
                return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            $user->delete();
            return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus user: ' . $e->getMessage());
            return redirect()->route('user.index')->with('error', 'Gagal menghapus pengguna.');
        }
    }

    //fungsi buat pengguna menggunakan excell
    public function importExcel(Request $request)
    {
        $request->validate([
            'lampiran_file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $data = Excel::toArray([], $request->file('lampiran_file'))[0];
            if (empty($data) || count($data) <= 1) {
                return back()->with('error', 'File Excel kosong atau tidak sesuai format.');
            }
            $rows = array_slice($data, 1);
            foreach ($rows as $row) {
                if (count($row) < 10) continue;

                $user = User::updateOrCreate(
                    ['email' => $row[7]],
                    [
                        'nip' => $row[1] ?? null,
                        'name' => $row[2] ?? '-',
                        'unit_kerja' => $row[3] ?? null,
                        'jabatan' => $row[4] ?? null,
                        'no_hp' => $row[5] ?? null,
                        'jenis_kelamin' => $row[6] ?? null,
                        'email' => $row[7],
                        'password' => Hash::make($row[8] ?? '12345678'),
                        'peran' => $row[9] ?? 'pegawai',
                    ]
                );

                if ($user->peran === 'pegawai') {
                    $rapatIds = Rapat::pluck('id');
                    $now = Carbon::now();

                    foreach ($rapatIds as $rapatId) {
                        $exists = Absensi::where('user_id', $user->id)
                            ->where('rapat_id', $rapatId)
                            ->exists();

                        if (!$exists) {
                            Absensi::create([
                                'rapat_id' => $rapatId,
                                'user_id' => $user->id,
                                'kehadiran' => 'tidak hadir',
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);
                        }
                    }
                }
            }

            return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diimpor dari Excel dan absensi otomatis dibuat.');
        } catch (\Exception $e) {
            Log::error('Gagal impor Excel: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data. Pastikan format Excel sesuai.');
        }
    }
}
