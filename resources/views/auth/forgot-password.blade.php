@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium">Forgot password</h1>
        <p class="text-center text-sm text-muted mb-4">Enter your email to receive a password reset link</p>
    </div>

    @session('status')
    <div class="my-4 text-center text-sm font-bold text-green-500">{{ session('status') }}</div>
    @endsession

    <div class="">
        <x-form class="space-y-4" method="POST" action="{{ route('password.email') }}">
            <x-input label="Email Address" type="email" placeholder="email@example.com" name="email" id="email"/>

            <x-button type="submit" color="dark" class="w-100">Email password reset link</x-button>

            <div class="text-center text-sm text-muted mt-4 authentication">Or, return to
                <a wire:navigate class="text-decoration-underline text-gray-800" href="{{ route('login') }}">
                    log in
                </a>
            </div>
        </x-form>
    </div>
@endsection
