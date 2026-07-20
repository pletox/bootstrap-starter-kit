@extends('layouts.landing')

@section('title', 'KumbhSnaan.com — Experience the Divine from Anywhere')

@php
    $packages = [
        ['name' => 'Basic', 'price' => '₹999', 'features' => ['Ritual by Priest', 'Video Recording', 'Digital Certificate', 'Holy Water (250 ml)', 'Standard Delivery']],
        ['name' => 'Premium', 'price' => '₹1,499', 'popular' => true, 'features' => ['Everything in Basic, plus', 'HD Video', 'Priority Ritual', 'Holy Water (500 ml)', 'Express Delivery']],
        ['name' => 'Family Package', 'price' => '₹2,999', 'features' => ['Up to 5 Family Members', 'Video Recording', 'Digital Certificates', 'Holy Water (1 Litre)', 'Express Delivery']],
        ['name' => 'NRI Package', 'price' => '$59 / ₹4,999', 'features' => ['Worldwide Digital Delivery', 'HD Video', 'Digital Certificate', 'International Shipping of Holy Water']],
    ];
    $steps = [
        ['icon' => 'bi-cloud-arrow-up', 'title' => 'Upload Photo & Sankalp', 'copy' => 'Submit your photo and prayer on our website.'],
        ['icon' => 'bi-calendar2-check', 'title' => 'Choose Snaan Date', 'copy' => 'Select a preferred sacred date from available events.'],
        ['icon' => 'bi-person-hearts', 'title' => 'Priest Performs Holy Ritual', 'copy' => 'A verified priest performs your Snaan at the holy kund.'],
        ['icon' => 'bi-play-btn', 'title' => 'Receive Video, Certificate & Holy Water', 'copy' => 'Your ritual proof and sacred water are delivered to you.'],
    ];
@endphp

@section('content')
    <header class="ks-header sticky-top">
        <nav class="navbar navbar-expand-lg container-xl py-2">
            <x-landing.brand/>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav" aria-controls="landingNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>
            <div class="collapse navbar-collapse" id="landingNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#digital-snaan">Digital Snaan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#packages">Packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="#holy-water">Holy Water</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kumbh">Kumbh 2027</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <a class="ks-phone ms-lg-3" href="https://wa.me/917387762735"><i class="bi bi-whatsapp"></i> +91 73877 62735</a>
                <a class="btn ks-btn-primary ms-lg-2" href="#packages">Book Now</a>
            </div>
        </nav>
    </header>

    <main>
        <section id="home" class="ks-hero">
            <div class="container-xl position-relative h-100 d-flex align-items-center">
                <div class="row w-100 align-items-center gy-4 py-5">
                    <div class="col-lg-6 col-xl-5 text-white">
                        <p class="ks-eyebrow">Nashik Simhastha Kumbh 2027</p>
                        <h1>Experience the Sacred <span>Kumbh, Wherever You Are</span></h1>
                        <p class="ks-hero-copy">Participate in the holy Nashik Simhastha Kumbh even if you cannot travel. We perform the sacred Snaan on your behalf at Ramkund / Kushavarta Kund with complete devotion.</p>
                        <div class="row g-3 ks-hero-features my-3">
                            <div class="col-6 col-sm-3"><i class="bi bi-camera"></i><span>Sacred Ritual<br>by Verified Priests</span></div>
                            <div class="col-6 col-sm-3"><i class="bi bi-camera-video"></i><span>Personalized<br>Video Proof</span></div>
                            <div class="col-6 col-sm-3"><i class="bi bi-patch-check"></i><span>Digital<br>Certificate</span></div>
                            <div class="col-6 col-sm-3"><i class="bi bi-bottle"></i><span>Holy Godavari Water<br>Delivered to You</span></div>
                        </div>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#packages" class="btn ks-btn-primary btn-lg">Book Your Digital Snaan</a>
                            <a href="#digital-snaan" class="btn ks-btn-outline btn-lg"><i class="bi bi-play-circle me-2"></i>Watch Sample Ritual</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-5 ms-auto">
                        <div class="ks-countdown" data-countdown="2028-10-31T00:00:00+05:30">
                            <p>Nashik Simhastha Kumbh 2027</p>
                            <span>Countdown Begins</span>
                            <div class="ks-countdown-grid">
                                @foreach(['days' => 'Days', 'hours' => 'Hours', 'minutes' => 'Minutes', 'seconds' => 'Seconds'] as $unit => $label)
                                    <strong><b data-countdown-unit="{{ $unit }}">000</b><small>{{ $label }}</small></strong>
                                @endforeach
                            </div>
                            <small>31 Oct 2026 – 24 Jul 2028</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ks-trust-strip">
            <div class="container-xl"><div class="row g-0">
                @foreach([['shield-check','100% Authentic Rituals','Performed as per Vedic Vidhi'],['person-standing-dress','Verified Priests','At Ramkund & Kushavarta Kund'],['hand-thumbs-up','Trusted by Thousands','Devotees Across the World'],['truck','Pan India Delivery','Secure & Timely'],['lock','Secure Payments','100% Safe & Trusted']] as [$icon, $title, $copy])
                    <div class="col-6 col-lg ks-trust-item"><i class="bi bi-{{ $icon }}"></i><span><strong>{{ $title }}</strong><small>{{ $copy }}</small></span></div>
                @endforeach
            </div></div>
        </section>

        <section id="digital-snaan" class="ks-section">
            <div class="container-xl"><div class="row g-4">
                <div class="col-xl-5">
                    <div class="ks-panel h-100">
                        <h2 class="ks-section-title">How It Works</h2>
                        <div class="row g-3">
                            @foreach($steps as $index => $step)
                                <div class="col-6 col-md-3 col-xl-6 ks-step">
                                    <span class="ks-icon-circle"><i class="bi {{ $step['icon'] }}"></i><b>{{ $index + 1 }}</b></span>
                                    <h3>{{ $step['title'] }}</h3><p>{{ $step['copy'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="packages" class="col-xl-7">
                    <h2 class="ks-section-title">Our Packages</h2>
                    <div class="row g-3">
                        @foreach($packages as $package)
                            <div class="col-md-6 col-xl-3">
                                <x-landing.package-card :name="$package['name']" :price="$package['price']" :features="$package['features']" :popular="$package['popular'] ?? false"/>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div></div>
        </section>

        <section class="pb-5"><div class="container-xl"><div class="row g-4">
            <div class="col-lg-4"><div class="ks-panel h-100"><h2 class="ks-section-title">What You Will Receive</h2><div class="ks-receive-grid">
                @foreach([['person-video3','Ritual Dip of your Photo'],['camera-video','Personalized Video'],['award','Digital Certificate'],['droplet','Holy Godavari Water'],['flower1','Sacred Prasad']] as [$icon,$label])
                    <div><span><i class="bi bi-{{ $icon }}"></i></span><small>{{ $label }}</small></div>
                @endforeach
            </div></div></div>
            <div id="holy-water" class="col-lg-4"><div class="ks-panel h-100"><h2 class="ks-section-title">Holy Godavari Water</h2><div class="ks-products">
                @foreach([['250 ml','₹299'],['500 ml','₹499'],['1 Litre','₹799'],['Gift Box','₹999']] as [$name,$price])
                    <div><i class="bi bi-bottle"></i><strong>{{ $name }}</strong><small>{{ $price }}</small></div>
                @endforeach
            </div><a href="#contact" class="btn ks-btn-dark w-100 mt-3">View All Products</a></div></div>
            <div id="gallery" class="col-lg-4"><div class="ks-panel h-100"><h2 class="ks-section-title">Trusted by Devotees</h2><div class="ks-testimonial"><div class="ks-stars">★★★★★</div><p>“My elderly parents were not able to travel, but through Kumbhsnaan, they felt the divine blessings. The video, certificate and holy water were received on time.”</p><strong>— Rohit Patil, Pune</strong></div></div></div>
        </div></div></section>

        <section id="kumbh" class="ks-kumbh py-5"><div class="container-xl text-center"><p class="ks-eyebrow">The world’s largest spiritual gathering</p><h2>Nashik Simhastha Kumbh 2027</h2><p class="mx-auto">Join millions of devotees in faith, ritual, and renewal—wherever you are in the world.</p><a href="#packages" class="btn ks-btn-primary mt-2">Reserve Your Sacred Date</a></div></section>

        <section id="faq" class="ks-section"><div class="container-xl"><div class="row justify-content-center"><div class="col-lg-9"><h2 class="ks-section-title">Frequently Asked Questions</h2><div class="accordion ks-accordion" id="faqAccordion">
            @foreach(['What is Digital Snaan?' => 'A verified priest performs the sacred ritual on your behalf using your submitted photo and sankalp.', 'How will I receive my ritual video?' => 'Your personalized video and digital certificate are delivered securely to your registered contact details.', 'Is holy water delivered safely?' => 'Yes. Holy Godavari water is sealed, carefully packed, and shipped with tracking.', 'Can I book this service from abroad?' => 'Yes. The NRI package includes worldwide digital delivery and international holy-water shipping where available.'] as $question => $answer)
                <div class="accordion-item"><h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $loop->iteration }}">{{ $question }}</button></h3><div id="faq{{ $loop->iteration }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ $answer }}</div></div></div>
            @endforeach
        </div></div></div></div></section>
    </main>

    <footer id="contact" class="ks-footer"><div class="container-xl py-4"><div class="row align-items-center gy-4">
        <div class="col-lg-3"><x-landing.brand light/></div>
        <div class="col-sm-6 col-lg-3 ks-footer-contact"><i class="bi bi-whatsapp"></i><span>Need Help? Chat on WhatsApp<strong>+91 73877 62735</strong></span></div>
        <div class="col-sm-6 col-lg-3 ks-footer-contact"><i class="bi bi-envelope"></i><span>Email Us<strong>support@kumbhsnaan.com</strong></span></div>
        <div class="col-lg-3 text-lg-end"><small>Follow Us</small><div class="ks-social"><a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a><a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a><a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a></div></div>
    </div></div></footer>
@endsection
