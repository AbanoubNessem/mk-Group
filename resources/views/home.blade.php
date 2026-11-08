<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $siteName = 'MK Group';
        $siteUrl = url('/');
        $defaultTitle = app()->getLocale() === 'ar' ? 'MK Group - شركة التصميم الداخلي' : 'MK Group - Interior Design Company';
        $defaultDescription = app()->getLocale() === 'ar' 
            ? 'شركة MK Group متخصصة في التصميم الداخلي والديكور. نقدم خدمات تصميم مبتكرة وعصرية لتحويل مساحاتك إلى أماكن استثنائية.'
            : 'MK Group specializes in interior design and decoration. We offer innovative and modern design services to transform your spaces into exceptional places.';
        $defaultImage = asset('assets/images/logo.jpg');
        
        // Get hero slide image if available
        if (isset($heroSlides) && $heroSlides->isNotEmpty() && $heroSlides->first()->image) {
            $defaultImage = asset('storage/' . $heroSlides->first()->image);
        } elseif (isset($homeSections['hero']) && isset($homeSections['hero']->background_image) && $homeSections['hero']->background_image) {
            $defaultImage = asset('storage/' . $homeSections['hero']->background_image);
        }
    @endphp
    
    <!-- Primary Meta Tags -->
    <title>{{ $defaultTitle }}</title>
    <meta name="title" content="{{ $defaultTitle }}">
    <meta name="description" content="{{ $defaultDescription }}">
    <meta name="keywords" content="{{ app()->getLocale() === 'ar' ? 'تصميم داخلي, ديكور, MK Group, تصميم منازل, تصميم مكاتب' : 'interior design, decoration, MK Group, home design, office design' }}">
    <meta name="author" content="MK Group">
    <meta name="robots" content="index, follow">
    <meta name="language" content="{{ app()->getLocale() }}">
    <link rel="canonical" href="{{ $siteUrl }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $siteUrl }}">
    <meta property="og:title" content="{{ $defaultTitle }}">
    <meta property="og:description" content="{{ $defaultDescription }}">
    <meta property="og:image" content="{{ $defaultImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $siteName }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_AR' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ app()->getLocale() === 'ar' ? 'en_US' : 'ar_AR' }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $siteUrl }}">
    <meta name="twitter:title" content="{{ $defaultTitle }}">
    <meta name="twitter:description" content="{{ $defaultDescription }}">
    <meta name="twitter:image" content="{{ $defaultImage }}">
    <meta name="twitter:image:alt" content="{{ $siteName }}">
    
    <!-- Additional Meta Tags -->
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $siteName }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.jpg') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Open+Sans:wght@300;400;500;600&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    
    <!-- Google Analytics -->
    @if(config('services.google_analytics_id'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config("services.google_analytics_id") }}', {
            'page_title': '{{ $defaultTitle }}',
            'page_location': '{{ $siteUrl }}'
        });
    </script>
    @endif
    
    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    @php
        $schemaData = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => $siteName,
            "url" => $siteUrl,
            "logo" => asset('assets/images/logo.jpg'),
            "description" => $defaultDescription,
            "address" => [
                "@type" => "PostalAddress",
                "addressCountry" => app()->getLocale() === 'ar' ? 'EG' : 'EG'
            ]
        ];
        if (isset($contactInfo) && $contactInfo->email) {
            $schemaData["email"] = $contactInfo->email;
        }
    @endphp
    {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="{{ url('/') }}" class="logo"><img src="{{ asset('assets/images/logo.jpg') }}" alt="MK Group" class="logo-image" onerror="this.style.display='none'">
                </a>
                <a href="{{ url('/') }}" class="logo">MK Group</a>
                <ul class="nav-links">
                    <li><a href="#home" data-translate="home">Home</a></li>
                    <li><a href="#about" data-translate="about">About</a></li>
                    <li><a href="#services" data-translate="services">Services</a></li>
                    <li><a href="#portfolio" data-translate="portfolio">Portfolio</a></li>
                    <li><a href="#contact" data-translate="contact">Contact</a></li>
                </ul>
                <div class="nav-right">
                    <div class="language-switcher">
                        <button class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" data-lang="en">EN</button>
                        <button class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}" data-lang="ar">AR</button>
                    </div>
                    <a href="#contact" class="cta-button" data-translate="getStarted">Get Started</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        @php
            // Use hero slides if available, otherwise fallback to old system
            $slides = $heroSlides ?? collect([]);
            if ($slides->isEmpty()) {
                // Fallback to old hero section system
                $hero = $homeSections['hero'] ?? $homeSections['home'] ?? null;
                $heroImages = $hero && $hero->images ? $hero->images : [];
                if ($hero && $hero->background_image) {
                    array_unshift($heroImages, $hero->background_image);
                }
                if (empty($heroImages)) {
                    $heroImages = ['hero-slide-1', 'hero-slide-2', 'hero-slide-3'];
                }
            }
        @endphp
        
        @if($slides->isNotEmpty())
            <!-- Slider Backgrounds -->
            <div class="hero-slider">
                @foreach($slides as $index => $slide)
                    <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" 
                         data-slide-index="{{ $index }}"
                         @if($slide->image) style="background-image: url('{{ asset('storage/' . $slide->image) }}');" @endif>
                    </div>
                @endforeach
            </div>
            
            <!-- Slider Arrows -->
            <div class="slider-arrow prev">
                <i>‹</i>
            </div>
            <div class="slider-arrow next">
                <i>›</i>
            </div>
            
            <!-- Slider Navigation Dots -->
            <div class="slider-nav">
                @foreach($slides as $index => $slide)
                    <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></div>
                @endforeach
            </div>
            
            <div class="container">
                <div class="hero-content">
                    @foreach($slides as $index => $slide)
                        <div class="hero-slide-content {{ $index === 0 ? 'active' : '' }}" data-content-index="{{ $index }}">
                            @if($slide->title || $slide->title_ar)
                                <h1 class="hero-title">
                                    {{ app()->getLocale() === 'ar' ? ($slide->title_ar ?: $slide->title) : ($slide->title ?: $slide->title_ar) }}
                                </h1>
                            @endif
                            @if($slide->subtitle || $slide->subtitle_ar)
                                <p class="hero-subtitle">
                                    {{ app()->getLocale() === 'ar' ? ($slide->subtitle_ar ?: $slide->subtitle) : ($slide->subtitle ?: $slide->subtitle_ar) }}
                                </p>
                            @endif
                            @if($slide->button_text || $slide->button_text_ar)
                                <a href="{{ $slide->button_link ?: '#services' }}" class="hero-button">
                                    {{ app()->getLocale() === 'ar' ? ($slide->button_text_ar ?: $slide->button_text) : ($slide->button_text ?: $slide->button_text_ar) }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Fallback to old system -->
            <!-- Slider Backgrounds -->
            <div class="hero-slider">
                @foreach($heroImages as $index => $image)
                    <div class="hero-slide {{ is_string($image) && !str_contains($image, '/') ? $image : '' }} {{ $index === 0 ? 'active' : '' }}" 
                         @if(str_contains($image, '/')) style="background-image: url('{{ asset('storage/' . $image) }}');" @endif></div>
                @endforeach
            </div>
            
            <!-- Slider Arrows -->
            <div class="slider-arrow prev">
                <i>‹</i>
            </div>
            <div class="slider-arrow next">
                <i>›</i>
            </div>
            
            <!-- Slider Navigation Dots -->
            <div class="slider-nav">
                @foreach($heroImages as $index => $image)
                    <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></div>
                @endforeach
            </div>
            
            <div class="container">
                <div class="hero-content">
                    @php
                        $hero = $homeSections['hero'] ?? $homeSections['home'] ?? null;
                    @endphp
                    @if($hero)
                        <h1 data-translate="heroTitle">{{ app()->getLocale() === 'ar' ? ($hero->title_ar ?: $hero->title) : $hero->title }}</h1>
                        <p data-translate="heroSubtitle">{{ app()->getLocale() === 'ar' ? ($hero->content_ar ?: $hero->content) : $hero->content }}</p>
                        @if($hero->button_text || $hero->button_text_ar)
                            <a href="{{ $hero->button_link ?: '#services' }}" class="hero-button" data-translate="discoverButton">
                                {{ app()->getLocale() === 'ar' ? ($hero->button_text_ar ?: $hero->button_text) : $hero->button_text }}
                            </a>
                        @endif
                    @else
                        {{-- لا توجد بيانات افتراضية - سيتم عرض المحتوى من hero section أو لا شيء --}}
                    @endif
                </div>
            </div>
        @endif
    </section>

     <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            @php
                $aboutSection = $aboutSections['main'] ?? $aboutSections['about'] ?? null;
            @endphp
            <div class="about-content">
                <div class="about-text">
                    @if($aboutSection)
                        <h2>{{ app()->getLocale() === 'ar' ? ($aboutSection->title_ar ?: $aboutSection->title) : $aboutSection->title }}</h2>
                        @if($aboutSection->content)
                            <div>{!! nl2br(e(app()->getLocale() === 'ar' ? ($aboutSection->content_ar ?: $aboutSection->content) : $aboutSection->content)) !!}</div>
                        @endif
                    @else
                        <h2 data-translate="aboutTitle">About Studio Elite</h2>
                        <p data-translate="aboutText1">Studio Elite is a multidisciplinary creative firm founded on the principle that exceptional design has the power to transform not just spaces, but lives. Our approach combines classical design wisdom with contemporary innovation.</p>
                        <p data-translate="aboutText2">With over a decade of experience, we've had the privilege of working with discerning clients who value quality, craftsmanship, and timeless appeal. Every project is an opportunity to create something extraordinary.</p>
                        <p data-translate="aboutText3">Our team of passionate designers, architects, and craftspeople are united by a shared commitment to excellence and an unwavering attention to detail.</p>
                    @endif
                </div>
                <div class="about-image-wrapper">
                    @if($aboutSection && $aboutSection->main_image)
                        <img src="{{ asset('storage/' . $aboutSection->main_image) }}" 
                             alt="{{ app()->getLocale() === 'ar' ? ($aboutSection->title_ar ?: $aboutSection->title) : $aboutSection->title }}"
                             class="about-image"
                             onerror="this.style.display='none'; this.parentElement.classList.add('no-image');">
                    @else
                        <div class="about-image no-image"></div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            @php
                $servicesSection = $homeSections['services'] ?? null;
            @endphp
            <div class="section-title">
                <h2 data-translate="servicesTitle">
                    @if($servicesSection)
                        {{ app()->getLocale() === 'ar' ? ($servicesSection->title_ar ?: $servicesSection->title) : $servicesSection->title }}
                    @else
                        Our Services
                    @endif
                </h2>
                <p data-translate="servicesSubtitle">
                    @if($servicesSection)
                        {{ app()->getLocale() === 'ar' ? ($servicesSection->subtitle_ar ?: $servicesSection->subtitle) : $servicesSection->subtitle }}
                    @else
                        Comprehensive solutions tailored to your vision
                    @endif
                </p>
            </div>
            <div class="services-grid">
                @forelse($services as $service)
                    <div class="service-card">
                        @if($service->image)
                            <div class="service-image" style="background-image: url('{{ asset('storage/' . $service->image) }}');"></div>
                        @else
                            <div class="service-image service-{{ $loop->index + 1 }}"></div>
                        @endif
                        <!-- <div class="service-icon">{{ $service->icon ?: '🏠' }}</div> -->
                        <h3>{{ app()->getLocale() === 'ar' ? ($service->name_ar ?: $service->name) : $service->name }}</h3>
                        <p>{{ app()->getLocale() === 'ar' ? ($service->description_ar ?: $service->description) : $service->description }}</p>
                    </div>
                @empty
                    <!-- Fallback to default services if none in database -->
                    <div class="service-card">
                        <div class="service-image service-1"></div>
                        <div class="service-icon">🏠</div>
                        <h3 data-translate="interiorDesign">Interior Design</h3>
                        <p data-translate="interiorDesignDesc">Transform your space with our signature aesthetic that blends sophistication with comfort, creating environments that reflect your unique style.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-image service-2"></div>
                        <div class="service-icon">📐</div>
                        <h3 data-translate="architecture">Architecture</h3>
                        <p data-translate="architectureDesc">From concept to completion, we design structures that stand as monuments to innovation while honoring timeless principles of beauty.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-image service-3"></div>
                        <div class="service-icon">🎨</div>
                        <h3 data-translate="brandIdentity">Brand Identity</h3>
                        <p data-translate="brandIdentityDesc">Develop a distinctive visual identity that captures the essence of your brand and resonates with your target audience.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

   

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                @forelse($stats as $stat)
                    <div class="stat-item">
                        <h3>{{ $stat->value }}</h3>
                        <p>{{ app()->getLocale() === 'ar' ? $stat->label_ar : $stat->label }}</p>
                    </div>
                @empty
                    <!-- Fallback to default stats if none in database -->
                    <div class="stat-item">
                        <h3>500+</h3>
                        <p data-translate="projectsCompleted">Projects Completed</p>
                    </div>
                    <div class="stat-item">
                        <h3>15</h3>
                        <p data-translate="yearsExperience">Years Experience</p>
                    </div>
                    <div class="stat-item">
                        <h3>200+</h3>
                        <p data-translate="happyClients">Happy Clients</p>
                    </div>
                    <div class="stat-item">
                        <h3>50+</h3>
                        <p data-translate="awardsWon">Awards Won</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Countries Bar Section -->
    @if(isset($countries) && $countries->count() > 0)
    <section class="countries-bar">
        <div class="container">
            <div class="countries-bar-content">
                <h3 data-translate="filterByCountry" style="margin-bottom: 20px; text-align: center; color: #2e394b; font-size: 24px;">
                    {{ app()->getLocale() === 'ar' ? 'تصفية حسب الدولة' : 'Filter by Country' }}
                </h3>
                <div class="countries-flags" id="countries-flags">
                    <button class="country-flag-btn {{ !request('country') ? 'active' : '' }}" 
                            data-country-id="" 
                            onclick="filterByCountry('')"
                            style="margin: 0 10px;">
                        <span style="font-size: 32px;">🌍</span>
                        <span style="display: block; margin-top: 5px; font-size: 14px;">{{ app()->getLocale() === 'ar' ? 'الكل' : 'All' }}</span>
                    </button>
                    @foreach($countries as $country)
                        <button class="country-flag-btn {{ request('country') == $country->id ? 'active' : '' }}" 
                                data-country-id="{{ $country->id }}" 
                                onclick="filterByCountry({{ $country->id }})"
                                style="margin: 0 10px;">
                            <span style="font-size: 32px;">{{ $country->flag_emoji ?: '🏳️' }}</span>
                            <span style="display: block; margin-top: 5px; font-size: 14px;">
                                {{ app()->getLocale() === 'ar' ? $country->name_ar : $country->name }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Portfolio Section -->
    <section class="portfolio" id="portfolio">
        <div class="container">
            <div class="section-title">
                <h2 data-translate="portfolioTitle">Our Portfolio</h2>
                <p data-translate="portfolioSubtitle">A showcase of our finest work and creative achievements</p>
            </div>
            <div class="portfolio-grid" id="portfolio-grid">
                @if(isset($projects) && $projects->count() > 0)
                    @foreach($projects as $project)
                        <div class="portfolio-item fade-in-up" onclick="window.location.href='/project/{{ $project->id }}'" style="cursor: pointer;">
                            @if($project->thumbnail)
                                <div style="background-image: url('{{ asset('storage/' . $project->thumbnail) }}'); background-size: cover; background-position: center; width: 100%; height: 100%; position: absolute; top: 0; left: 0;"></div>
                            @elseif($project->images && count($project->images) > 0)
                                <div style="background-image: url('{{ asset('storage/' . $project->images[0]) }}'); background-size: cover; background-position: center; width: 100%; height: 100%; position: absolute; top: 0; left: 0;"></div>
                            @else
                                <div style="background-color: #f0f0f0; width: 100%; height: 100%; position: absolute; top: 0; left: 0;"></div>
                            @endif
                            <div class="portfolio-overlay">
                                <div class="portfolio-content">
                                    <h4>{{ app()->getLocale() === 'ar' ? ($project->name_ar ?: $project->name) : ($project->name ?: $project->name_ar) }}</h4>
                                    <p>
                                        {{ app()->getLocale() === 'ar' 
                                            ? ($project->category_ar ?: $project->category ?: $project->engineering_function_ar ?: $project->engineering_function) 
                                            : ($project->category ?: $project->category_ar ?: $project->engineering_function ?: $project->engineering_function_ar) }}
                                    </p>
                                    <a href="/project/{{ $project->id }}" class="view-project-btn" data-translate="viewProject" onclick="event.stopPropagation();">
                                        {{ app()->getLocale() === 'ar' ? 'عرض المشروع' : 'View Project' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 60px 20px; color: #666; grid-column: 1 / -1;">
                        <p style="font-size: 18px; margin-bottom: 10px;">No projects available</p>
                        <p style="font-size: 14px; color: #999;">Please add active projects from the dashboard.</p>
                        <a href="/dashboard/portfolio" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2e394b; color: white; text-decoration: none; border-radius: 5px;">Go to Dashboard</a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2 data-translate="contactTitle">{{ app()->getLocale() === 'ar' ? ($contactInfo->contact_title_ar ?? 'دعنا نعمل معاً') : ($contactInfo->contact_title ?? "Let's Work Together") }}</h2>
                    <div class="contact-item">
                        <h4 data-translate="phone">Phone</h4>
                        @if($contactInfo->phone_numbers && is_array($contactInfo->phone_numbers))
                            @foreach($contactInfo->phone_numbers as $phone)
                                <p>{{ $phone }}</p>
                            @endforeach
                        @else
                            <p>-</p>
                        @endif
                    </div>
                    <div class="contact-item">
                        <h4 data-translate="email">Email</h4>
                        <p>{{ $contactInfo->email ?? '-' }}</p>
                    </div>
                    <div class="contact-item">
                        <h4 data-translate="hours">Hours</h4>
                        <p data-translate="hoursText">{!! app()->getLocale() === 'ar' ? ($contactInfo->hours_text_ar ?? 'الإثنين - الجمعة: 9:00 صباحاً - 6:00 مساءً<br>السبت: بالحجز') : ($contactInfo->hours_text ?? 'Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: By appointment') !!}</p>
                    </div>
                </div>
                <div class="contact-form">
                    <form id="contact-form">
                        @csrf
                        <div class="form-group">
                            <label for="name" data-translate="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email" data-translate="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone" data-translate="phone">Phone</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="project" data-translate="projectType">Project Type</label>
                            <input type="text" id="project" name="project">
                        </div>
                        <div class="form-group">
                            <label for="message" data-translate="message">Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn" data-translate="sendMessage">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p data-translate="footerText">&copy; 2025 MK Group. All rights reserved. | MK Group Since 2022</p>
        </div>
    </footer>

    <!-- Firebase -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-analytics.js";
        
        const firebaseConfig = {
            apiKey: "AIzaSyBI1i9DRNjLAEl-y_Hsq3hLiZpbJYUxjUY",
            authDomain: "ag-interior-design.firebaseapp.com",
            projectId: "ag-interior-design",
            storageBucket: "ag-interior-design.firebasestorage.app",
            messagingSenderId: "261319120347",
            appId: "1:261319120347:web:623c3beb5eb6f06b5943fe",
            measurementId: "G-BCK71P6YMT"
        };
        
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
    </script>

    <script src="{{ asset('js/projects-data.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>

