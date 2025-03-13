@extends('layouts.auth')

@section('content')
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            A new email verification link has been emailed to you!
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body px-4">
            <h5 class="mb-3">Email Verification</h5>
            <p class="text-muted">
                Please verify your email address by clicking the link we just emailed to you.
                If you didn't receive the email, we will gladly send you another.
            </p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <button class="btn btn-dark">Resend Verification Email</button>
                </div>
            </form>
        </div>
    </div>
@endsection
