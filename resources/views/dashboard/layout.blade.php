<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@hasSection('title')@yield('title') — @endif لوحة التحكم — MK Group</title>
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
    @stack('styles')
</head>
<body>
@php
    $nav = [
        ['label' => 'نظرة عامة', 'items' => [
            ['route' => 'dashboard.index', 'match' => 'dashboard.index', 'label' => 'الرئيسية', 'icon' => 'grid'],
        ]],
        ['label' => 'محتوى الموقع', 'items' => [
            ['route' => 'dashboard.portfolio', 'match' => 'dashboard.portfolio*', 'label' => 'المشاريع', 'icon' => 'image'],
            ['route' => 'dashboard.services', 'match' => 'dashboard.services*', 'label' => 'الخدمات', 'icon' => 'layers'],
            ['route' => 'dashboard.home', 'match' => 'dashboard.home*', 'label' => 'أقسام الصفحة الرئيسية', 'icon' => 'home'],
            ['route' => 'dashboard.hero', 'match' => 'dashboard.hero*', 'label' => 'شرائح الواجهة', 'icon' => 'slides'],
            ['route' => 'dashboard.about', 'match' => 'dashboard.about*', 'label' => 'من نحن', 'icon' => 'info'],
            ['route' => 'dashboard.stats', 'match' => 'dashboard.stats*', 'label' => 'الإحصائيات', 'icon' => 'chart'],
            ['route' => 'dashboard.countries', 'match' => 'dashboard.countries*', 'label' => 'الدول', 'icon' => 'pin'],
        ]],
        ['label' => 'التواصل', 'items' => [
            ['route' => 'dashboard.contacts', 'match' => 'dashboard.contacts*', 'label' => 'رسائل التواصل', 'icon' => 'inbox'],
            ['route' => 'dashboard.contact-info', 'match' => 'dashboard.contact-info*', 'label' => 'معلومات التواصل', 'icon' => 'phone'],
        ]],
    ];
    $icons = [
        'grid' => '<rect x="4" y="4" width="7" height="7"/><rect x="13" y="4" width="7" height="7"/><rect x="4" y="13" width="7" height="7"/><rect x="13" y="13" width="7" height="7"/>',
        'image' => '<rect x="3" y="5" width="18" height="14"/><path d="M3 16l5-5 4 4 3-3 6 6"/><circle cx="15.5" cy="9.5" r="1.5"/>',
        'layers' => '<path d="M12 4l9 5-9 5-9-5 9-5z"/><path d="M3 14l9 5 9-5"/>',
        'home' => '<path d="M4 11l8-7 8 7v9H4z"/><path d="M10 20v-6h4v6"/>',
        'slides' => '<rect x="5" y="6" width="14" height="12"/><path d="M2 8v8M22 8v8"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8v.01"/>',
        'chart' => '<path d="M5 20V10M12 20V4M19 20v-7"/>',
        'pin' => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0114 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'inbox' => '<path d="M3 13h5l1 3h6l1-3h5"/><path d="M5 5h14l2 8v6H3v-6z"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a1 1 0 01-1 1A16 16 0 014 5a1 1 0 011-1z"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9M18 14v6H4V6h6"/>',
        'logout' => '<path d="M15 4h4v16h-4M10 8l-4 4 4 4M6 12h10"/>',
        'menu' => '<path d="M3 7h18M3 12h18M3 17h18"/>',
    ];
    $icon = fn ($n) => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$icons[$n].'</svg>';
@endphp

<div class="min-h-dvh lg:grid lg:grid-cols-[17rem_1fr]">
    {{-- Sidebar --}}
    <aside id="dash-sidebar" class="fixed inset-y-0 start-0 z-40 flex w-72 translate-x-full flex-col bg-charcoal-deep text-white transition-transform duration-200 data-[open]:translate-x-0 lg:sticky lg:top-0 lg:h-dvh lg:w-auto lg:translate-x-0" data-dash-sidebar aria-label="القائمة الجانبية">
        <div class="flex items-center justify-between gap-4 px-6 pt-7 pb-6">
            <a href="{{ route('dashboard.index') }}" class="block" aria-label="لوحة التحكم">
                <img src="{{ asset('assets/images/mk-logo-light.png') }}" alt="MK Group" width="1127" height="582" class="h-12 w-auto">
            </a>
            <span class="text-xs text-white/50">لوحة التحكم</span>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 pb-6" aria-label="أقسام لوحة التحكم">
            @foreach ($nav as $group)
                <p class="px-3 pt-5 pb-2 text-xs text-white/45">{{ $group['label'] }}</p>
                <ul class="space-y-0.5">
                    @foreach ($group['items'] as $item)
                        @php $active = request()->routeIs($item['match']); @endphp
                        <li>
                            <a href="{{ route($item['route']) }}" @if ($active) aria-current="page" @endif
                                @class([
                                    'flex min-h-10 items-center gap-3 px-3 text-sm transition-colors',
                                    'bg-white text-charcoal' => $active,
                                    'text-white/80 hover:bg-white/10 hover:text-white' => ! $active,
                                ])>{!! $icon($item['icon']) !!}<span>{{ $item['label'] }}</span></a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </nav>

        <div class="border-t border-white/10 px-3 py-4">
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex min-h-10 items-center gap-3 px-3 text-sm text-white/80 hover:bg-white/10 hover:text-white">
                {!! $icon('external') !!}<span>عرض الموقع</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="flex min-h-10 w-full cursor-pointer items-center gap-3 px-3 text-start text-sm text-white/80 hover:bg-white/10 hover:text-white">
                    {!! $icon('logout') !!}<span>تسجيل الخروج</span>
                </button>
            </form>
            <p class="mt-3 truncate px-3 text-xs text-white/45">{{ Auth::user()->name }}</p>
        </div>
    </aside>
    <div class="fixed inset-0 z-30 bg-charcoal-deep/50 lg:hidden" data-dash-overlay hidden></div>

    {{-- Main --}}
    <div class="min-w-0">
        <header class="sticky top-0 z-20 flex h-14 items-center gap-3 border-b border-stone bg-ivory/95 px-4 backdrop-blur lg:hidden">
            <button type="button" class="btn btn-ghost -ms-2 px-2" aria-controls="dash-sidebar" aria-expanded="false" data-dash-toggle>
                {!! $icon('menu') !!}<span class="sr-only">فتح القائمة</span>
            </button>
            <img src="{{ asset('assets/images/mk-logo-slate.png') }}" alt="MK Group" width="1127" height="582" class="h-8 w-auto">
        </header>

        <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-10 lg:py-10">
            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
<script src="{{ asset('js/image-compress.js') }}"></script>
</body>
</html>
