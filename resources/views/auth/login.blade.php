@extends('layouts.auth')

@section('title', 'Login')

@section('content')

<div class="auth-wrapper">

    <!-- LEFT -->
    <div class="auth-left">

        <img src="{{ asset('images/logo.png') }}" class="logo-lapar" alt="Logo">

        <h2 class="title-lkh">
            LAPORAN PENGGAWEAN HARIAN <br>
            DINAS KOMUNIKASI DAN INFORMATIKA
            KOTA PAGAR ALAM
        </h2>

    </div>


    <!-- RIGHT -->
    <div class="auth-right">

        <div class="login-card">

            <h3 class="login-title">Sign In</h3>

            @if ($errors->any())
            <div class="login-alert" id="loginAlert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="form-group">
                        <label>Username</label>
                        <input
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukan NIP atau Username Anda"
                        autocomplete="username"
                        required
                        >
                    </div>

                    <div class="form-group">
                    <label>Password</label>

                    <div class="password-wrapper">
                        <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukan Kata Sandi Anda"
                        autocomplete="current-password"
                        required
                        >
                        <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
                    </div>

                </div>

                <button class="btn-login" type="submit">
                    Masuk
                </button>

            </form>

        </div>

    </div>

</div>


@endsection
