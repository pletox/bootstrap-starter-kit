@extends('layouts.app')

@section('content')

    <div>
        <ul class="nav nav-tabs" id="myTabs">
            <li class="nav-item">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button">Home</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button">Profile</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button">Contact</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="home-tab-pane">Home Content</div>
            <div class="tab-pane fade" id="profile-tab-pane"></div>
            <div class="tab-pane fade" id="contact-tab-pane"></div>
        </div>

    </div>

@endsection

@push('js')
    <script type="module">
        $('#myTabs').jpTabs({
            ajax: {
                'profile-tab': route('tabs.profile'),   // loads into #profile-tab-pane
                'contact-tab': route('tabs.contact')   // loads into #contact-tab-pane
            },
            onTabChange: function (tabId, prevTab) {
                console.log("Switched to:", tabId, "from", prevTab);
            }
        });
    </script>
@endpush
