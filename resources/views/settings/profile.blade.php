@extends('layouts.app')

@section('title', 'Profile Setting')

@section('content')

    @include('settings.header')

    <div class="row ">
        <div class="col-md-3 ps-0">
            @include('settings.nav')
        </div>
        <div class="col-md-6 ps-0">
            <section>
                <h4 class="mb-1 text-base font-medium text-gray-700">Profile Information</h4>
                <p class="text-sm text-muted">Update your name and email address</p>
                <x-form id="profileForm" class="space-y-4">
                    <x-input name="name" id="name" label="Name" placeholder="Enter Name"
                             value="{{ auth()->user()->name }}"/>
                    <x-input name="email" id="email" label="Email address" placeholder="Enter Email"
                             value="{{ auth()->user()->email }}"/>
                    <x-button color="dark" type="submit">Save</x-button>
                </x-form>
            </section>
            <section>
                <h4 class="mb-1 text-base font-medium text-gray-700 mt-5">Delete Account</h4>
                <p class="text-sm text-muted">Delete your account and all of its resources</p>
                <div class="alert alert-danger bg-red-50" role="alert" style="border-color: #ffd9d9;">
                    <h6 class="alert-heading text-red-600 mb-1">Warning</h6>
                    <p class="text-red-600">Please proceed with caution, this cannot be undone.</p>
                    <a href="#" data-bs-toggle="#deleteUserModal" id="delete-user-btn"
                       class="btn bg-red-500 text-white">
                        Delete Account
                    </a>

                </div>

                <x-modal id="deleteUserModal" title="Are you sure you want to delete your account?">
                    <x-form id="deleteUserForm">
                        <x-modal.body class="space-y-4">
                            <p>
                                Once your account is deleted, all of its resources and data will also be permanently
                                deleted.
                                Please enter your password to confirm you would like to permanently delete your account.
                            </p>
                            <x-input type="password" name="password" id="password" placeholder="Password"/>
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

@endsection
@push('js')
    <script type="module">
        $(function () {


            $('#delete-user-btn').click(function () {
                $('#deleteUserModal').modal('show');
            });

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
                        if (response.data.status === 200) {
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
