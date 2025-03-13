@extends('layouts.app')

@section('title', 'Password Setting')

@section('content')

        @include('settings.header')

        <div class="row">
            <div class="col-md-3 ps-0">
                @include('settings.nav')
            </div>
            <div class="col-md-6 ps-0">
                <section>
                    <h4 class="mb-1 text-base font-medium text-gray-700">Update password</h4>
                    <p class="text-sm text-muted">Ensure your account is using a long, random password to stay secure</p>
                    <x-form id="profilePasswordForm" class="space-y-4">
                        <x-input name="current_password"  type="password" id="current_password" label="Current password" placeholder="Current password" autocomplete="off"/>
                        <x-input name="password" type="password" id="new_password" label="New password" placeholder="New password" autocomplete="off"/>
                        <x-input name="password_confirmation" type="password" id="password_confirmation" label="Confirm password" placeholder="Confirm password" autocomplete="off"/>

                        <x-button color="dark" type="submit">Save Password</x-button>
                    </x-form>
                </section>
            </div>
        </div>

@endsection
@push('js')
    <script type="module">
        $(function () {
            $('#profilePasswordForm').on('submit', function (e) {
                e.preventDefault();
                var data = new FormData($('#profilePasswordForm')[0]);
                $.easyAjax({
                    url: "{{ route('user-password.update') }}",
                    container: '#profilePasswordForm',
                    type: "PUT",
                    disableButton: true,
                    blockUI: true,
                    data: data,
                    onComplete: (response) => {
                        $('#profilePasswordForm')[0].reset();
                    }
                })
            });
        });
    </script>
@endpush
