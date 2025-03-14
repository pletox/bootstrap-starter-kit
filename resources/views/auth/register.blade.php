@extends('layouts.auth')

@section('content')
    <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium">Create an account</h1>
        <p class="text-center text-sm text-muted mb-4">Enter your details below to create your account</p>
    </div>

    <div class="">
        <form method="POST" action="{{ route('register') }}">
             @csrf
            <div class="px-4">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"/>
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror"/>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"/>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control"/>
                    <span class="invalid-feedback"></span>
                </div>


                <div class="mt-4">
                    <button class="btn btn-dark w-100">Create Account</button>
                </div>

                <div class="text-center text-sm text-muted mt-4 authentication"> Already have an account?
                    <a tabindex="5" class=" text-decoration-underline text-gray-800" href="{{ route('login') }}">
                        Log in
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
