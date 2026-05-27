@extends('layouts.app')

@section('content')
    <div class="container-fluid settings-shell">
        @include('settings.partials._header')

        <div class="row settings-layout">
            <div class="col-md-3 ps-0 settings-nav-col">
                @include('settings.partials._nav')
            </div>
            <div class="col-md-6 ps-0 settings-content-col">
                @yield('settings.content')
            </div>
        </div>
    </div>
@endsection
