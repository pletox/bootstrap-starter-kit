@extends('settings.layout')

@section('title', 'Permission settings')

@section('settings.content')
    @php
        $enabledPermissions = collect(config('starter-kit.settings.permissions', []));

        $browserPermissions = [
            'camera' => [
                'type' => 'camera',
                'title' => 'Camera access',
                'description' => 'Allow camera access when a feature needs to take or scan a photo.',
                'icon' => 'lucide-camera',
                'button' => 'Allow camera',
            ],
            'microphone' => [
                'type' => 'microphone',
                'title' => 'Microphone access',
                'description' => 'Allow microphone access when a feature needs audio input.',
                'icon' => 'lucide-mic',
                'button' => 'Allow microphone',
            ],
            'location' => [
                'type' => 'geolocation',
                'title' => 'Location access',
                'description' => 'Allow location access when a feature needs your current area.',
                'icon' => 'lucide-map-pin',
                'button' => 'Allow location',
            ],
        ];
    @endphp

    <section>
        <x-section-header
            title="Permissions"
            subtitle="Manage what this device allows the app to use."
        />

        <div class="d-grid gap-3">
            @if($enabledPermissions->contains('notifications'))
                <x-pwa-push-card :url="route('home')" />
            @endif

            @foreach($enabledPermissions as $permission)
                @isset($browserPermissions[$permission])
                    <x-browser-permission-card
                        :type="$browserPermissions[$permission]['type']"
                        :title="$browserPermissions[$permission]['title']"
                        :description="$browserPermissions[$permission]['description']"
                        :icon="$browserPermissions[$permission]['icon']"
                        :button="$browserPermissions[$permission]['button']"
                    />
                @endisset
            @endforeach
        </div>
    </section>
@endsection
