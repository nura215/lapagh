<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Imports\UsersImport;
use App\Exports\UserTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->toString();
        $sortBy = $request->string('sort')->trim()->toString();
        $sortDir = strtolower($request->string('dir')->toString()) === 'asc' ? 'asc' : 'desc';

        $sortableColumns = [
            'nama' => 'pegawai.nama',
            'nip' => 'pegawai.nip',
            'status_pegawai' => 'pegawai.status_pegawai',
            'jabatan' => 'pegawai.jabatan',
            'username' => 'users.username',
        ];

        if (! array_key_exists($sortBy, $sortableColumns)) {
            $sortBy = 'username';
            $sortDir = 'asc';
        }

        $users = User::query()
            ->select('users.*')
            ->with('pegawai')
            ->leftJoin('pegawai', 'pegawai.user_id', '=', 'users.id')
            ->where('role', User::ROLE_NON_ASN)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('username', 'like', "%{$search}%")
                        ->orWhereHas('pegawai', function ($pegawai) use ($search) {
                            $pegawai->where('nama', 'like', "%{$search}%")
                                ->orWhere('nip', 'like', "%{$search}%")
                                ->orWhere('status_pegawai', 'like', "%{$search}%")
                                ->orWhere('jabatan', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy($sortableColumns[$sortBy], $sortDir)
            ->orderBy('users.id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit(User $user)
    {
        $user->load('pegawai');
        return view('admin.users.edit', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:30', 'unique:pegawai,nip'],
            'status_pegawai' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
        ]);

        $nip = $validated['nip'] ?? null;
        $nip = $nip ? preg_replace('/\s+/', '', $nip) : null;

        // Jika ASN (ada NIP) dan sudah terdaftar, hentikan
        if ($nip && Pegawai::where('nip', $nip)->exists()) {
            return back()
                ->withErrors(['nip' => 'User dengan NIP tersebut sudah terdaftar.'])
                ->withInput();
        }

        // Jika non ASN (tanpa NIP) dan nama sudah ada, hentikan
        if (! $nip && Pegawai::whereRaw('LOWER(nama) = ?', [mb_strtolower($validated['nama'])])->exists()) {
            return back()
                ->withErrors(['nama' => 'User dengan nama tersebut sudah terdaftar.'])
                ->withInput();
        }

        if ($nip && User::where('username', $nip)->exists()) {
            return back()
                ->withErrors(['nip' => 'NIP sudah dipakai sebagai username.'])
                ->withInput();
        }

        $username = $nip ?: $this->generateUsername($validated['nama']);
        $plainPassword = $this->generatePassword();

        DB::transaction(function () use ($validated, $nip, $username, $plainPassword) {
            $user = User::create([
                'username' => $username,
                'role' => User::ROLE_NON_ASN,
                'password' => Hash::make($plainPassword),
                'temp_password' => $plainPassword,
                'is_first_login' => true,
            ]);

            Pegawai::create([
                'user_id' => $user->id,
                'nip' => $nip ?: null,
                'nama' => $validated['nama'],
                'status_pegawai' => $validated['status_pegawai'],
                'jabatan' => $validated['jabatan'],
            ]);
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User pegawai berhasil dibuat. Password sementara: ' . $plainPassword);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:30', 'unique:pegawai,nip,' . $user->pegawai?->id],
            'status_pegawai' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
        ]);

        $nip = $validated['nip'] ?? null;
        $nip = $nip ? preg_replace('/\s+/', '', $nip) : null;

        if ($nip && User::where('username', $nip)->where('id', '!=', $user->id)->exists()) {
            return back()
                ->withErrors(['nip' => 'NIP sudah dipakai sebagai username.'])
                ->withInput();
        }

        $username = $nip ?: $user->username;

        DB::transaction(function () use ($user, $validated, $nip, $username) {
            $user->update([
                'username' => $username,
                'role' => User::ROLE_NON_ASN,
            ]);

            $user->pegawai()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => $nip ?: null,
                    'nama' => $validated['nama'],
                    'status_pegawai' => $validated['status_pegawai'],
                    'jabatan' => $validated['jabatan'],
                ]
            );
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function upload()
    {
        return view('admin.users.upload-excel');
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:4096'],
        ]);

        try {
            Excel::import(new UsersImport, $validated['file']);
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Import gagal: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Import selesai. Data berhasil diproses.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new UserTemplateExport, 'template-import-user.xlsx');
    }

    public function downloadAccounts()
    {
        $users = User::with('pegawai')
            ->where('role', User::ROLE_NON_ASN)
            ->orderBy('username')
            ->get();

        $filename = 'akun-pegawai-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'NIP', 'Status Pegawai', 'Jabatan', 'Username', 'Password Sementara']);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->pegawai?->nama,
                    $user->pegawai?->nip,
                    $user->pegawai?->status_pegawai,
                    $user->pegawai?->jabatan,
                    $user->username,
                    $user->temp_password ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function resetPassword(User $user)
    {
        if (! $user->isNonAsn()) {
            abort(404);
        }

        $plainPassword = $this->generatePassword();
        $user->update([
            'password' => Hash::make($plainPassword),
            'temp_password' => $plainPassword,
            'is_first_login' => true,
        ]);

        return back()->with('success', 'Password berhasil direset. Password sementara: ' . $plainPassword);
    }

    public function destroy(User $user)
    {
        if (! $user->isNonAsn()) {
            abort(404);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    protected function generateUsername(string $name): string
    {
        $firstWord = Str::before($name, ' ');
        $base = Str::lower(preg_replace('/[^a-z0-9]/', '', $firstWord));
        $base = $base !== '' ? $base : 'user';

        for ($i = 0; $i < 30; $i++) {
            $rand = str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $candidate = $base . $rand;
            if (! User::where('username', $candidate)->exists()) {
                return $candidate;
            }
        }

        $counter = 1;
        $candidate = $base . $counter;
        while (User::where('username', $candidate)->exists()) {
            $counter++;
            $candidate = $base . $counter;
        }
        return $candidate;
    }

    protected function generatePassword(): string
    {
        return Str::random(6) . random_int(10, 99) . '!';
    }
}
