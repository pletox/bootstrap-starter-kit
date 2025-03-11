@extends('layouts.auth')

@section('content')
    <div class="card shadow-sm border-0">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="card-body px-4">

                <x-input label="E-mail Address" name="email" id="email"/>


                <x-input type="password" label="Password" name="password" id="password"/>

                <x-checkbox name="remember_me" id="remember_me" label="Remember Me"/>


                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-underline text-muted">Forgot your
                        password?</a>
                </div>

                <x-button type="submit" color="dark" class="w-100 mt-3">Log In</x-button>
            </div>
        </form>
    </div>
@endsection
