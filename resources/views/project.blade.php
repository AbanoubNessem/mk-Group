<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $siteName = 'MK Group';
        $siteUrl = url('/');
        $projectUrl = url()->current();
        
        if (isset($project)) {
            $projectTitle = app()->getLocale() === 'ar' 
                ? ($project->name_ar ?: $project->name) 
                : ($project->name ?: $project->name_ar);
            $pageTitle = $siteName . ' - ' . $projectTitle;
            
            $projectDescription = app()->getLocale() === 'ar' 
                ? ($project->description_ar ?: $project->description ?: '') 
                : ($project->description ?: $project->description_ar ?: '');
            $pageDescription = $projectDescription ?: (app()->getLocale() === 'ar' 
                ? 'مشروع تصميم داخلي من ' . $siteName 
                : 'Interior design project by ' . $siteName);
            
            $projectImage = $defaultImage = asset('assets/images/logo.jpg');
            if ($project->thumbnail) {
                $projectImage = asset('storage/' . $project->thumbnail);
            } elseif ($project->images && count($project->images) > 0) {
                $projectImage = asset('storage/' . $project->images[0]);
            }
        } else {
            $pageTitle = $siteName . ' - ' . (app()->getLocale() === 'ar' ? 'تفاصيل المشروع' : 'Project Details');
            $pageDescription = app()->getLocale() === 'ar' 
                ? 'عرض تفاصيل المشروع من ' . $siteName 
                : 'View project details from ' . $siteName;
            $projectImage = asset('assets/images/logo.jpg');
        }
    @endphp
    
    <!-- Primary Meta Tags -->
    <title>{{ $pageTitle }}</title>
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="{{ app()->getLocale() === 'ar' ? 'تصميم داخلي, مشروع, ' . (isset($projectTitle) ? $projectTitle : '') : 'interior design, project, ' . (isset($projectTitle) ? $projectTitle : '') }}">
    <meta name="author" content="MK Group">
    <meta name="robots" content="index, follow">
    <meta name="language" content="{{ app()->getLocale() }}">
    <link rel="canonical" href="{{ $projectUrl }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $projectUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $projectImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ isset($projectTitle) ? $projectTitle : $siteName }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'ar' ? 'ar_AR' : 'en_US' }}">
    @if(isset($project))
    <meta property="article:published_time" content="{{ $project->created_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $project->updated_at->toIso8601String() }}">
    @endif
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $projectUrl }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $projectImage }}">
    <meta name="twitter:image:alt" content="{{ isset($projectTitle) ? $projectTitle : $siteName }}">
    
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
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">
    
    <!-- Google Analytics -->
    @if(config('services.google_analytics_id'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config("services.google_analytics_id") }}', {
            'page_title': '{{ $pageTitle }}',
            'page_location': '{{ $projectUrl }}'
        });
    </script>
    @endif
    
    <!-- Schema.org Structured Data -->
    @if(isset($project))
    <script type="application/ld+json">
    {
        "@type": "CreativeWork",
        "name": "{{ $projectTitle }}",
        "description": "{{ $pageDescription }}",
        "image": "{{ $projectImage }}",
        "url": "{{ $projectUrl }}",
        "author": {
            "@type": "Organization",
            "name": "{{ $siteName }}"
        },
        "datePublished": "{{ $project->created_at->toIso8601String() }}",
        "dateModified": "{{ $project->updated_at->toIso8601String() }}"
    }
    </script>
    @endif
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="MK Group" class="logo-image" onerror="this.style.display='none'">
                <a href="{{ url('/') }}" class="logo">MK Group</a>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}#home">Home</a></li>
                    <li><a href="{{ url('/') }}#services">Services</a></li>
                    <li><a href="{{ url('/') }}#about">About</a></li>
                    <li><a href="{{ url('/') }}#portfolio">Portfolio</a></li>
                    <li><a href="{{ url('/') }}#contact">Contact</a></li>
                </ul>
                <a href="{{ url('/') }}#contact" class="cta-button">Start Your Project</a>
            </nav>
        </div>
    </header>

    @if(isset($project))
    <!-- Project Hero Section -->
    <section class="project-hero">
        <div class="container">
            <div class="project-info">
                <h1 id="project-title">{{ app()->getLocale() === 'ar' ? ($project->name_ar ?: $project->name) : ($project->name ?: $project->name_ar) }}</h1>
                <p class="project-category" id="project-category">
                    {{ app()->getLocale() === 'ar' 
                        ? ($project->category_ar ?: $project->category ?: $project->engineering_function_ar ?: $project->engineering_function ?: '') 
                        : ($project->category ?: $project->category_ar ?: $project->engineering_function ?: $project->engineering_function_ar ?: '') }}
                </p>
                <p class="project-description" id="project-description">
                    {{ app()->getLocale() === 'ar' ? ($project->description_ar ?: $project->description ?: '') : ($project->description ?: $project->description_ar ?: '') }}
                </p>
                <div class="project-meta">
                    @if($project->area)
                    <div class="meta-item">
                        <span class="meta-label">{{ app()->getLocale() === 'ar' ? 'المساحة:' : 'Area:' }}</span>
                        <span class="meta-value" id="project-area">{{ $project->area }}</span>
                    </div>
                    @endif
                    @if($project->location || $project->location_ar)
                    <div class="meta-item">
                        <span class="meta-label">{{ app()->getLocale() === 'ar' ? 'الموقع:' : 'Location:' }}</span>
                        <span class="meta-value" id="project-location">{{ app()->getLocale() === 'ar' ? ($project->location_ar ?: $project->location) : ($project->location ?: $project->location_ar) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Project Gallery Slider -->
    @if($project->images && count($project->images) > 0)
    <section class="project-gallery">
        <div class="container">
            <div class="gallery-slider">
                <div class="slider-container">
                    <div class="slider-track" id="slider-track">
                        @foreach($project->images as $index => $image)
                        <div class="slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ app()->getLocale() === 'ar' ? ($project->name_ar ?: $project->name) : ($project->name ?: $project->name_ar) }} - Image {{ $index + 1 }}" loading="lazy">
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Slider Navigation -->
                <div class="slider-nav">
                    <button class="slider-arrow prev" id="prev-slide">‹</button>
                    <button class="slider-arrow next" id="next-slide">›</button>
                </div>
                
                <!-- Slider Dots -->
                <div class="slider-dots" id="slider-dots">
                    @foreach($project->images as $index => $image)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></span>
                    @endforeach
                </div>
                
                <!-- Image Counter -->
                <div class="image-counter">
                    <span class="current">1</span> / <span class="total" id="total-images">{{ count($project->images) }}</span>
                </div>
            </div>
            
            <!-- Thumbnail Gallery -->
            <div class="thumbnail-gallery">
                <h3>{{ app()->getLocale() === 'ar' ? 'معرض المشروع' : 'Project Gallery' }}</h3>
                <div class="thumbnails-container" id="thumbnails-container">
                    @foreach($project->images as $index => $image)
                    <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ app()->getLocale() === 'ar' ? ($project->name_ar ?: $project->name) : ($project->name ?: $project->name_ar) }} - Thumbnail {{ $index + 1 }}" loading="lazy">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @elseif($project->thumbnail)
    <section class="project-gallery">
        <div class="container">
            <div class="gallery-slider">
                <div class="slider-container">
                    <div class="slider-track" id="slider-track">
                        <div class="slide active">
                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ app()->getLocale() === 'ar' ? ($project->name_ar ?: $project->name) : ($project->name ?: $project->name_ar) }}" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Project Details -->
    <section class="project-details">
        <div class="container">
            <div class="details-grid">
                <div class="details-content">
                    <h2>{{ app()->getLocale() === 'ar' ? 'تفاصيل المشروع' : 'Project Details' }}</h2>
                    <p id="project-details-text">
                        {{ app()->getLocale() === 'ar' ? ($project->description_ar ?: $project->description ?: '') : ($project->description ?: $project->description_ar ?: '') }}
                    </p>
                    
                    @if($project->project_type || $project->project_type_ar)
                    <h3>{{ app()->getLocale() === 'ar' ? 'نوع المشروع' : 'Project Type' }}</h3>
                    <p>{{ app()->getLocale() === 'ar' ? ($project->project_type_ar ?: $project->project_type) : ($project->project_type ?: $project->project_type_ar) }}</p>
                    @endif
                </div>
                
                <div class="project-stats">
                    <h3>{{ app()->getLocale() === 'ar' ? 'إحصائيات المشروع' : 'Project Statistics' }}</h3>
                    <div class="stats-grid">
                        @if($project->area)
                        <div class="stat-card">
                            <div class="stat-number" id="stat-area">{{ $project->area }}</div>
                            <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'المساحة' : 'Area' }}</div>
                        </div>
                        @endif
                        @if($project->rooms_count)
                        <div class="stat-card">
                            <div class="stat-number">{{ $project->rooms_count }}</div>
                            <div class="stat-label">{{ app()->getLocale() === 'ar' ? 'عدد الغرف' : 'Rooms' }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Projects -->
    @php
        $relatedProjects = \App\Models\Project::where('id', '!=', $project->id)
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    @endphp
    @if($relatedProjects->count() > 0)
    <section class="related-projects">
        <div class="container">
            <h2>{{ app()->getLocale() === 'ar' ? 'مشاريع ذات صلة' : 'Related Projects' }}</h2>
            <div class="projects-grid" id="related-projects">
                @foreach($relatedProjects as $relatedProject)
                <a href="/project/{{ $relatedProject->id }}" class="project-card">
                    @if($relatedProject->thumbnail)
                        <img src="{{ asset('storage/' . $relatedProject->thumbnail) }}" alt="{{ app()->getLocale() === 'ar' ? ($relatedProject->name_ar ?: $relatedProject->name) : ($relatedProject->name ?: $relatedProject->name_ar) }}">
                    @elseif($relatedProject->images && count($relatedProject->images) > 0)
                        <img src="{{ asset('storage/' . $relatedProject->images[0]) }}" alt="{{ app()->getLocale() === 'ar' ? ($relatedProject->name_ar ?: $relatedProject->name) : ($relatedProject->name ?: $relatedProject->name_ar) }}">
                    @endif
                    <div class="project-overlay">
                        <h3>{{ app()->getLocale() === 'ar' ? ($relatedProject->name_ar ?: $relatedProject->name) : ($relatedProject->name ?: $relatedProject->name_ar) }}</h3>
                        <p>{{ app()->getLocale() === 'ar' 
                            ? ($relatedProject->category_ar ?: $relatedProject->category ?: $relatedProject->engineering_function_ar ?: $relatedProject->engineering_function) 
                            : ($relatedProject->category ?: $relatedProject->category_ar ?: $relatedProject->engineering_function ?: $relatedProject->engineering_function_ar) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @else
    <!-- Project Not Found -->
    <section class="project-hero">
        <div class="container">
            <div class="project-info">
                <h1>{{ app()->getLocale() === 'ar' ? 'المشروع غير موجود' : 'Project Not Found' }}</h1>
                <p class="project-description">{{ app()->getLocale() === 'ar' ? 'عذراً، المشروع المطلوب غير موجود.' : 'Sorry, the requested project could not be found.' }}</p>
                <a href="{{ url('/') }}#portfolio" class="hero-button">{{ app()->getLocale() === 'ar' ? 'العودة إلى Portfolio' : 'Back to Portfolio' }}</a>
            </div>
        </div>
    </section>
    @endif

    @if(isset($project) && $project->images && count($project->images) > 0)
    <script src="{{ asset('js/project.js') }}"></script> 
    @endif

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 MK Group. All rights reserved. | Crafting Excellence Since 2010</p>
        </div>
    </footer>

</body>
</html>

