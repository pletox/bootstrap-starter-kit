@extends('layouts.auth')

@section('content')
    <div class="card shadow-sm border-0">
        <form method="POST" action="{{ route('register') }}">
             @csrf
            <div class="card-body px-4">
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


                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="login.html" class="text-decoration-underline text-muted">Already Registered?</a>
                    <button class="btn btn-dark">REGISTER</button>
                </div>
            </div>
        </form>
    </div>
@endsection
