@extends('layouts.auth')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="card-body px-4">
                <h5 class="mb-3">Reset Password</h5>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email',request('email')) }}"
                           class="form-control @error('email') is-invalid @enderror" required autofocus readonly />
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror" required />
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" required />
                </div>

                <div class="d-flex align-items-center justify-content-end gap-3">
                    <button class="btn btn-dark">RESET PASSWORD</button>
                </div>
            </div>
        </form>
    </div>
@endsection
