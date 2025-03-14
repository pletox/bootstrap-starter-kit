@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium mb-4">Email Verification</h1>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="my-4 text-center text-sm font-bold text-red-500">A new email verification link has been emailed to
            you!
        </div>
    @endif


    <div class="px-4">
        <p class="text-muted">
            Please verify your email address by clicking the link we just emailed to you.
            If you didn't receive the email, we will gladly send you another.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div class="mt-4">
                <button class="btn btn-dark w-100">Resend verification email</button>
            </div>

        </form>
    </div>

@endsection
