@extends('layouts.auth')

@section('content')
<div class="auth-wrapper">
    <div class="auth-left">
        <img src="{{ asset('images/logo.png') }}" class="logo-lapar" alt="Logo">
        <h2 class="title-lkh">Ganti Password Pertama</h2>
        <p class="desc-lkh">
            Demi keamanan, silakan buat password baru sebelum melanjutkan ke dasbor.
        </p>
    </div>

    <div class="auth-right">
        <div class="login-card">
            <h3 class="login-title">Password Baru</h3>

            @if ($errors->any())
                <div class="login-alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (auth()->user()?->temp_password)
                <div class="form-group" style="margin-top:-6px;">
                    <label>Password sementara</label>
                    <div class="password-wrapper">
                        <input type="text" value="{{ auth()->user()->temp_password }}" readonly>
                    </div>
                    <small style="color:#6f6f6f;">Gunakan untuk login jika diperlukan, lalu ganti di bawah.</small>
                </div>
            @endif

            <form method="POST" action="{{ route('first-password.update') }}">
                @csrf
                <div class="form-group">
                    <label>Password baru</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            autocomplete="new-password"
                        >
                        <i class="fa-solid fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Konfirmasi password</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi password baru"
                            required
                            autocomplete="new-password"
                        >
                        <i class="fa-solid fa-eye toggle-password"></i>
                    </div>
                </div>

                <button class="btn-login" type="submit">Simpan & Masuk</button>
            </form>
        </div>
    </div>
</div>
@endsection
