    @php
    $appName = config('app.name', 'PletoxStarter');
    $themeColor = '#212529';
@endphp

<meta name="theme-color" content="{{ $themeColor }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="{{ $appName }}">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<link rel="manifest" href="{{ route('pwa.manifest') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('pwa/icons/icon-16x16.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('pwa/icons/icon-32x32.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('pwa/icons/apple-touch-icon-180x180.png') }}">

<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-640x1136.png') }}" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-750x1334.png') }}" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-828x1792.png') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1125x2436.png') }}" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1170x2532.png') }}" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1179x2556.png') }}" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1242x2208.png') }}" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1242x2688.png') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1284x2778.png') }}" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1290x2796.png') }}" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1536x2048.png') }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1668x2224.png') }}" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-1668x2388.png') }}" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="{{ asset('pwa/splash/apple-splash-2048x2732.png') }}" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)">
