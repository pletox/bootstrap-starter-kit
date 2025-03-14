@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium mb-4">Reset Password</h1>
    </div>

    @if (session('status'))
        <div class="my-4 text-center text-sm font-bold text-green-500">{{ session('status') }}</div>
    @endif

    <div class="">
        <x-form class="space-y-4" method="POST" action="{{ route('password.update') }}">

            <input type="hidden" name="token" value="{{ $token }}">

            <x-input label="Email Address" placeholder="email@example.com" type="email" name="email" id="email"
                     value="{{ request('email') }}"/>

            <x-input label="New Password" placeholder="New Password" type="password" name="password" id="password"/>

            <x-input label="Confirm New Password" placeholder="Confirm New Password" type="password"
                     name="password_confirmation" id="password_confirmation"/>

            <x-button type="submit" color="dark" class="w-100">Reset Password</x-button>

        </x-form>
    </div>
@endsection
