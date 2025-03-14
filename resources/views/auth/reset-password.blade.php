@extends('layouts.auth')

@section('content')
    <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium mb-4">Reset Password</h1>
    </div>

    @if (session('status'))
        {{--        <div class="alert alert-success">{{ session('status') }}</div>     --}}
        <div class="my-4 text-center text-sm font-bold text-green-500">{{ session('status') }}</div>
    @endif

    <div class="">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="px-4">
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

                <div class="mt-4">
                    <button class="btn btn-dark w-100">Reset Password</button>
                </div>
            </div>
        </form>
    </div>
@endsection
