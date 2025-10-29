<?php

namespace App\Http\Controllers;

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
    /**
     * Menampilkan daftar semua pengguna (pegawai).
     * Admin bisa filter berdasarkan peran atau mencari.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Pencarian Sederhana
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%')
                    ->orWhere('jabatan', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan Peran
        if ($request->has('peran') && $request->peran != '' && $request->peran !== 'semua') {
            $query->where('peran', $request->peran);
        }


        // Ambil data dengan urutan terbaru dan pagination
        $users = $query->get();

        // Anda perlu membuat view 'pages.manajemen-user'
        return view('pages.manajemen-pengguna', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        // Anda perlu membuat view 'pages.partials.create-user'
        return view('pages.partials.create-user');
    }

    /**
     * Menyimpan user baru ke database.
     */
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
            // 1️⃣ Buat user baru
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

            // 2️⃣ Jika user adalah pegawai, otomatis buat absensi default
            if ($user->peran === 'pegawai') {
                // Pastikan modelnya di-import:
                // use App\Models\Rapat;
                // use App\Models\Absensi;
                // use Carbon\Carbon;

                $rapatIds = \App\Models\Rapat::pluck('id');
                $now = \Carbon\Carbon::now();

                $data = $rapatIds->map(fn($rapatId) => [
                    'rapat_id' => $rapatId,
                    'user_id' => $user->id,
                    'kehadiran' => 'tidak hadir',
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->toArray();

                if (count($data)) {
                    \App\Models\Absensi::insert($data);
                }
            }

            return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan dan absensi otomatis dibuat.');
        } catch (\Exception $e) {
            \Log::error('Gagal simpan user: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        // Anda perlu membuat view 'pages.partials.edit-user'
        return view('pages.partials.edit-user', compact('user'));
    }

    /**
     * Update data user di database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan email unik, KECUALI untuk user ini sendiri
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            // Password opsional, hanya jika ingin diubah
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
            // Update data dasar
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->nip = $validated['nip'];
            $user->unit_kerja = $validated['unit_kerja'];
            $user->jabatan = $validated['jabatan'];
            $user->peran = $validated['peran'];
            $user->no_hp = $validated['no_hp'];
            $user->jenis_kelamin = $validated['jenis_kelamin'];

            // Update password HANYA JIKA diisi
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

    /**
     * Hapus user dari database.
     */
    public function destroy(User $user)
    {
        try {
            // Opsi: Jangan biarkan admin menghapus diri sendiri
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

    public function importExcel(Request $request)
    {
        $request->validate([
            'lampiran_file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        try {
            // Ambil data Excel
            $data = Excel::toArray([], $request->file('lampiran_file'))[0];

            if (empty($data) || count($data) <= 1) {
                return back()->with('error', 'File Excel kosong atau tidak sesuai format.');
            }

            // Lewati header
            $rows = array_slice($data, 1);

            foreach ($rows as $row) {
                if (count($row) < 10) continue; // Pastikan kolom lengkap

                // Simpan / update user
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

                // Tambahkan absensi otomatis jika pegawai baru
                if ($user->peran === 'pegawai') {
                    $rapatIds = \App\Models\Rapat::pluck('id');
                    $now = \Carbon\Carbon::now();

                    // Cek apakah absensi untuk user & rapat sudah ada
                    foreach ($rapatIds as $rapatId) {
                        $exists = \App\Models\Absensi::where('user_id', $user->id)
                            ->where('rapat_id', $rapatId)
                            ->exists();

                        if (!$exists) {
                            \App\Models\Absensi::create([
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
