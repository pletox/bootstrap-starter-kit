@props(['name', 'price', 'features', 'popular' => false])

<article {{ $attributes->merge(['class' => 'ks-package-card h-100 position-relative']) }}>
    @if($popular)
        <span class="ks-popular">Popular</span>
    @endif
    <h3>{{ $name }}</h3>
    <p class="ks-package-price">{{ $price }}</p>
    <ul>
        @foreach($features as $feature)
            <li>{{ $feature }}</li>
        @endforeach
    </ul>
    <a href="#contact" class="btn ks-btn-primary mt-auto">Book Now</a>
</article>
