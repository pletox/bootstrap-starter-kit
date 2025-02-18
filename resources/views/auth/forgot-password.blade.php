@extends('layouts.auth')

@section('content')
    @session('status')
        <div class="alert alert-success">{{ session('status') }}</div>
    @endsession

    <div class="card shadow-sm border-0">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="card-body px-4">
                <div class="mb-3 text-small text-secondary">
                    Forgot your password? No problem. Just let us know your email address and we will email you a
                    password reset link that will allow you to choose a new one.
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"/>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>


                <div class="d-flex align-items-center justify-content-end gap-3">
                    <button class="btn btn-dark">EMAIL PASSWORD RESET LINK</button>
                </div>
            </div>
        </form>
    </div>
@endsection
