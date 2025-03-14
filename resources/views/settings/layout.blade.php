@extends('layouts.app')

@section('content')

    @include('settings.partials._header')

    <div class="row ">
        <div class="col-md-3 ps-0">
            @include('settings.partials._nav')
        </div>
        <div class="col-md-6 ps-0">
            @yield('settings.content')
        </div>
    </div>
@endsection
