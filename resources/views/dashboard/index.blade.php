@extends('dashboard.layout')

@section('title', 'الرئيسية')

@php
    // Read-only overview figures
    $projects = \App\Models\Project::all(['id', 'name', 'name_ar', 'description', 'description_ar', 'thumbnail', 'images', 'is_active', 'updated_at']);
    $counts = [
        ['label' => 'المشاريع المنشورة', 'value' => $projects->where('is_active', true)->count(), 'route' => 'dashboard.portfolio'],
        ['label' => 'الخدمات', 'value' => \App\Models\Service::where('is_active', true)->count(), 'route' => 'dashboard.services'],
        ['label' => 'رسائل التواصل', 'value' => \App\Models\Contact::count(), 'route' => 'dashboard.contacts'],
        ['label' => 'الإحصائيات', 'value' => \App\Models\Stat::where('is_active', true)->count(), 'route' => 'dashboard.stats'],
    ];
    $latest = \App\Models\Contact::orderByDesc('created_at')->limit(5)->get();
    $about = \App\Models\AboutSection::pluck('section_key')->all();
    $info = \App\Models\ContactInfo::first();

    // Content that is missing or still contains placeholder text
    $todo = [];
    $noDesc = $projects->filter(fn ($p) => ! $p->description && ! $p->description_ar)->count();
    if ($noDesc) $todo[] = ['text' => "مشاريع بدون وصف: {$noDesc} — الوصف يظهر في صفحة المشروع ويحسّن الظهور في محركات البحث.", 'route' => 'dashboard.portfolio'];
    foreach (['vision' => 'الرؤية', 'mission' => 'الرسالة'] as $key => $label) {
        if (! in_array($key, $about)) $todo[] = ['text' => "أضف قسم «{$label}» في «من نحن» بالمفتاح {$key} ليظهر في صفحة من نحن.", 'route' => 'dashboard.about'];
    }
    if (\App\Models\AboutSection::where('title', 'like', '%test%')->exists()) $todo[] = ['text' => 'عنوان قسم «من نحن» بالإنجليزية يحتوي على نص تجريبي (test about).', 'route' => 'dashboard.about'];
    if ($info && str_contains((string) $info->email, 'ag.interior')) $todo[] = ['text' => 'البريد الإلكتروني في معلومات التواصل ليس بريد MK Group (' . $info->email . ').', 'route' => 'dashboard.contact-info'];
@endphp

@section('content')
<div class="page-header">
    <h1>مرحبًا، {{ Auth::user()->name }}</h1>
    <a href="{{ route('dashboard.portfolio.form') }}" class="btn btn-primary">إضافة مشروع جديد</a>
</div>

<div class="mb-6 grid grid-cols-2 gap-px border border-stone bg-stone md:grid-cols-4">
    @foreach ($counts as $item)
        <a href="{{ route($item['route']) }}" class="block bg-white p-5 transition-colors hover:bg-ivory">
            <span class="block font-display text-4xl font-light text-slate">{{ $item['value'] }}</span>
            <span class="mt-1 block text-sm text-charcoal-soft">{{ $item['label'] }}</span>
        </a>
    @endforeach
</div>

<div class="grid gap-6 lg:grid-cols-5">
    <section class="content-box lg:col-span-3" aria-labelledby="todo-title">
        <h2 id="todo-title">محتوى يحتاج إلى استكمال</h2>
        @if ($todo)
            <ul class="mt-4 divide-y divide-stone-soft">
                @foreach ($todo as $item)
                    <li class="flex items-start justify-between gap-4 py-3">
                        <span class="text-sm">{{ $item['text'] }}</span>
                        <a href="{{ route($item['route']) }}" class="btn btn-warning shrink-0">فتح</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="mt-4 text-sm text-charcoal-soft">كل المحتوى الأساسي مكتمل.</p>
        @endif
    </section>

    <section class="content-box lg:col-span-2" aria-labelledby="inbox-title">
        <div class="flex items-center justify-between gap-4">
            <h2 id="inbox-title">أحدث الرسائل</h2>
            <a href="{{ route('dashboard.contacts') }}" class="text-sm underline underline-offset-4">عرض الكل</a>
        </div>
        @if ($latest->isNotEmpty())
            <ul class="mt-4 divide-y divide-stone-soft">
                @foreach ($latest as $msg)
                    <li class="py-3">
                        <p class="text-sm font-medium">{{ $msg->name }}</p>
                        <p class="line-clamp-1 text-sm text-charcoal-soft">{{ $msg->message }}</p>
                        <p class="text-xs text-charcoal-soft" dir="ltr">{{ optional($msg->created_at)->format('Y-m-d H:i') }}</p>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="mt-4 text-sm text-charcoal-soft">لا توجد رسائل بعد.</p>
        @endif
    </section>
</div>
@endsection
