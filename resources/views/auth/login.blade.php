@extends('layouts.auth')

@section('content')

    <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium">Log in to your account</h1>
        <p class="text-center text-sm text-muted mb-4">Enter your email and password below to log in</p>
    </div>
    <div class="">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="px-2 space-y-3">

                <x-input label="E-mail Address" name="email" id="email"/>

                <x-input type="password" label="Password" name="password" id="password"/>

                <x-checkbox name="remember_me" id="remember_me" label="Remember Me"/>

                <div class="d-flex align-items-center justify-content-end gap-3 authentication">
                    <a href="{{ route('password.request') }}" class="text-decoration-underline text-gray-800">Forgot your
                        password?</a>
                </div>

                <x-button type="submit" color="dark" class="w-100 mt-3">Log In</x-button>

                <div class="text-center text-sm text-muted mt-4 authentication"> Don't have an account?
                    <a tabindex="5" class=" text-decoration-underline text-gray-800" href="{{ route('register') }}">
                        Sign up
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
