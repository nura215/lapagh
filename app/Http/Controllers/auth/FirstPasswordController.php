<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FirstPasswordController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->is_first_login || $user->isAdmin()) {
            return $this->redirectByRole($user);
        }

        return view('auth.first-password');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->is_first_login || $user->isAdmin()) {
            return $this->redirectByRole($user);
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'is_first_login' => false,
            'temp_password' => null,
        ])->save();

        return $this->redirectByRole($user)->with('success', 'Password berhasil diganti.');
    }

    protected function redirectByRole($user)
    {
        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pegawai.dashboard');
    }
}
