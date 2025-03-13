@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3">
               @include('settings.nav')
            </div>
            <div class="col-lg-9">
                <section>
                    <h4 class="mb-3">Profile Information</h4>
                    <p class="text-muted">Update your name and email address</p>
                    <x-form id="profileForm" class="space-y-4">
                        <x-input name="name" id="name" label="Name" placeholder="Enter Name" value="{{ auth()->user()->name }}"/>
                        <x-input name="email" id="email" label="Email" placeholder="Enter Email" value="{{ auth()->user()->email }}"/>
                        <x-button color="dark" type="submit">Save</x-button>
                    </x-form>
                </section>
                <hr class="my-5">
                <section>
                    <h4 class="mb-3 text-danger">Delete Account</h4>
                    <p class="text-muted">Delete your account and all of its resources</p>
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Warning</h4>
                        <p>Please proceed with caution, this cannot be undone.</p>
                    </div>
                    <a href="#" data-bs-toggle="#deleteUserModal" id="delete-user-btn" class="btn btn-danger">
                       Delete Account
                    </a>

                    <x-modal id="deleteUserModal" title="Are you sure you want to delete your account?">
                        <x-form id="deleteUserForm" >
                            <x-modal.body class="space-y-4">
                                <p>
                                    Once your account is deleted, all of its resources and data will also be permanently deleted.
                                    Please enter your password to confirm you would like to permanently delete your account.
                                </p>
                                <x-input name="password" id="password" placeholder="Password"/>
                            </x-modal.body>

                            <x-modal.footer>
                                <x-button color="secondary" data-bs-dismiss="modal">Cancel</x-button>
                                <x-button color="danger" type="submit">Delete Account</x-button>
                            </x-modal.footer>
                        </x-form>

                    </x-modal>
                </section>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script type="module">
        $(function () {

            $('#deleteUserForm').on('submit', function (e) {
                e.preventDefault();
                var data = new FormData($('#deleteUserForm')[0]);
                $.easyAjax({
                    url: "{{ route('settings.profile.delete') }}",
                    container: '#deleteUserForm',
                    type: "POST",
                    disableButton: true,
                    blockUI: true,
                    data: data,
                    onComplete: (response) => {
                        $('#deleteUserForm')[0].reset();
                        if(response.data.status === 200){
                            window.location.reload();
                        }
                    }
                })
            });

            $('#profileForm').on('submit', function (e) {
                e.preventDefault();
                var data = new FormData($('#profileForm')[0]);
                $.easyAjax({
                    url: "{{ route('user-profile-information.update') }}",
                    container: '#profileForm',
                    type: "PUT",
                    disableButton: true,
                    blockUI: true,
                    data: data,
                    onComplete: () => {
                    }
                })
            });
        });
    </script>
@endpush
