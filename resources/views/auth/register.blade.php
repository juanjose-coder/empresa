@extends('layouts.app')

@section('content')
<div class="container">
    <div class="auth-wrap">
        <div class="auth-logo-plain"><img src="{{ asset('img/Laravel.svg') }}" alt="Logo"></div>
        <div class="auth-card">
            <h1 class="auth-title">{{ __('Register') }}</h1>
            <form method="POST" action="{{ route('register') }}">
                @csrf

            <div class="mb-3">
                <label for="name" class="auth-label">{{ __('Name') }}<span class="req">*</span></label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="auth-label">{{ __('Email Address') }}<span class="req">*</span></label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="auth-label">{{ __('Password') }}<span class="req">*</span></label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="auth-label">{{ __('Confirm Password') }}<span class="req">*</span></label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

                <div class="auth-actions between">
                    @if (Route::has('login'))
                        <a class="auth-link" href="{{ route('login') }}">{{ __('Already registered?') }}</a>
                    @else
                        <span></span>
                    @endif
                    <button type="submit" class="btn-auth">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
