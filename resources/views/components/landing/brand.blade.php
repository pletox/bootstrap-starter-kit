@props(['light' => false])

<a {{ $attributes->merge(['class' => 'ks-brand text-decoration-none']) }} href="{{ route('landing') }}" aria-label="KumbhSnaan.com home">
    <span class="ks-brand-mark" aria-hidden="true">ॐ</span>
    <span>
        <strong class="ks-brand-name {{ $light ? 'text-white' : '' }}">KumbhSnaan<span>.com</span></strong>
        <small class="{{ $light ? 'text-white-50' : '' }}">A Digital Gateway to Nashik Simhastha Kumbh 2027</small>
    </span>
</a>
