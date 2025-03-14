@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium">Create an account</h1>
        <p class="text-center text-sm text-muted mb-4">Enter your details below to create your account</p>
    </div>

    <div class="">
        <x-form class="space-y-4" method="POST" action="{{ route('register') }}">

            <x-input placeholder="Full name" label="Name" name="name" id="name"/>

            <x-input placeholder="email@example.com" type="email" label="E-mail Address" name="email" id="email"/>

            <x-input placeholder="Password" type="password" label="Password" name="password" id="password"/>

            <x-input label="Confirm Password" placeholder="Confirm New Password" type="password"
                     name="password_confirmation" id="password_confirmation"/>


            <x-button type="submit" color="dark" class="w-100 mt-4">Create Account</x-button>


            <div class="text-center text-sm text-muted mt-4 authentication"> Already have an account?
                <a wire:navigate class="text-decoration-underline text-gray-800" href="{{ route('login') }}">
                    Log in
                </a>
            </div>

        </x-form>
    </div>
@endsection
