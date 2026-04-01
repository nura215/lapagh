<?php

namespace App\Http\Controllers\pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updatePhoto(Request $request)
    {
        $validated = $request->validate([
            'foto_profile' => ['required', 'image', 'max:4096'],
        ]);

        $pegawai = $request->user()->pegawai;

        if (! $pegawai) {
            return back()->withErrors(['foto_profile' => 'Data pegawai belum lengkap.']);
        }

        if ($pegawai->foto_profile) {
            Storage::disk('public')->delete($pegawai->foto_profile);
        }

        $path = $request->file('foto_profile')->store('profile', 'public');

        $pegawai->update([
            'foto_profile' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
