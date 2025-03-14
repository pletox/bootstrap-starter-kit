@extends('layouts.auth')

@section('content')
    <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium">Forgot password</h1>
        <p class="text-center text-sm text-muted mb-4">Enter your email to receive a password reset link</p>
    </div>

    @session('status')
    {{--        <div class="alert alert-success">{{ session('status') }}</div>--}}
    <div class="my-4 text-center text-sm font-bold text-green-500">A reset link will be sent if the account exists.</div>
    @endsession

    <div class="">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="px-4">
{{--                <div class="mb-3 text-small text-secondary">--}}
{{--                    Forgot your password? No problem. Just let us know your email address and we will email you a--}}
{{--                    password reset link that will allow you to choose a new one.--}}
{{--                </div>--}}

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="text" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"/>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <button class="btn btn-dark w-100">Email password reset link</button>
                </div>

                <div class="text-center text-sm text-muted mt-4 authentication" >Or, return to
                    <a tabindex="5" class=" text-decoration-underline text-gray-800" href="{{ route('login') }}">
                        log in
                    </a>
                </div>

            </div>
        </form>
    </div>
@endsection
