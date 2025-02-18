@extends('layouts.auth')

@section('content')
    <div class="card shadow-sm border-0">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="card-body px-4">
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"/>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"/>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                    <label class="form-check-label" for="remember_me">
                        Remember Me
                    </label>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-underline text-muted">Forgot your password?</a>
                    <button class="btn btn-dark">LOG IN</button>
                </div>
            </div>
        </form>
    </div>
@endsection
