<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        $header = $rows->shift()->map(function ($v) {
            $v = strtolower((string) $v);
            $v = preg_replace('/\s*\(.*?\)/', '', $v);
            $v = str_replace(' ', '_', $v);
            return preg_replace('/[^a-z0-9_]/', '', $v);
        })->toArray();

        $idx = array_flip($header);
        $required = ['nama', 'status_pegawai', 'jabatan'];
        foreach ($required as $col) {
            if (! isset($idx[$col])) {
                throw new \RuntimeException("Kolom '{$col}' wajib ada di header.");
            }
        }

        foreach ($rows as $row) {
            if ($row->filter(fn ($v) => trim((string) $v) !== '')->isEmpty()) {
                continue;
            }

            $nama = trim((string) ($row[$idx['nama']] ?? ''));
            $nip = isset($idx['nip']) ? trim((string) ($row[$idx['nip']] ?? '')) : '';
            $status = trim((string) ($row[$idx['status_pegawai']] ?? ''));
            $jabatan = trim((string) ($row[$idx['jabatan']] ?? ''));

            if ($nama === '' || $status === '' || $jabatan === '') {
                continue;
            }

            $nip = $nip !== '' ? preg_replace('/\s+/', '', $nip) : null;

            if ($nip && (User::where('username', $nip)->exists() || Pegawai::where('nip', $nip)->exists())) {
                continue;
            }

            if (! $nip && Pegawai::whereRaw('LOWER(nama) = ?', [mb_strtolower($nama)])->exists()) {
                continue;
            }

            $username = $nip ?: $this->generateUsername($nama);
            $plainPassword = $this->generatePassword();

            DB::transaction(function () use ($nama, $nip, $status, $jabatan, $username, $plainPassword) {
                $user = User::create([
                    'username' => $username,
                    'role' => User::ROLE_NON_ASN,
                    'password' => Hash::make($plainPassword),
                    'temp_password' => $plainPassword,
                    'is_first_login' => true,
                ]);

                Pegawai::create([
                    'user_id' => $user->id,
                    'nip' => $nip,
                    'nama' => $nama,
                    'status_pegawai' => $status,
                    'jabatan' => $jabatan,
                ]);
            });
        }
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
