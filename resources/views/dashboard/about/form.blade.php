@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>{{ $section->id ? 'تعديل قسم' : 'إضافة قسم جديد' }}</h1>
</div>

<div class="content-box">
    <form action="{{ $section->id ? route('dashboard.about.store', $section->id) : route('dashboard.about.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($section->id)
            @method('PUT')
        @endif

        <div class="form-group">
            <label>مفتاح القسم (Section Key) *</label>
            <input type="text" name="section_key" class="form-control" value="{{ old('section_key', $section->section_key) }}" required>
            <small style="color: #7f8c8d;">مثل: main, mission, vision, team, etc.</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>العنوان (إنجليزي)</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $section->title) }}">
            </div>
            <div class="form-group">
                <label>العنوان (عربي)</label>
                <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar', $section->title_ar) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>العنوان الفرعي (إنجليزي)</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $section->subtitle) }}">
            </div>
            <div class="form-group">
                <label>العنوان الفرعي (عربي)</label>
                <input type="text" name="subtitle_ar" class="form-control" value="{{ old('subtitle_ar', $section->subtitle_ar) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>المحتوى (إنجليزي)</label>
                <textarea name="content" class="form-control" rows="5">{{ old('content', $section->content) }}</textarea>
            </div>
            <div class="form-group">
                <label>المحتوى (عربي)</label>
                <textarea name="content_ar" class="form-control" rows="5">{{ old('content_ar', $section->content_ar) }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label>الصورة الرئيسية</label>
            <input type="file" name="main_image" class="form-control" accept="image/*">
            @if($section->main_image)
                <img src="{{ asset('storage/' . $section->main_image) }}" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
            @endif
        </div>

        <div class="form-group">
            <label>صور إضافية (يمكن اختيار أكثر من صورة)</label>
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
            @if($section->images)
                <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;">
                    @foreach($section->images as $image)
                        <img src="{{ asset('storage/' . $image) }}" style="max-width: 150px; border-radius: 5px;">
                    @endforeach
                </div>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                    نشط
                </label>
            </div>
            <div class="form-group">
                <label>ترتيب العرض</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $section->sort_order ?? 0) }}">
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">حفظ</button>
            <a href="{{ route('dashboard.about') }}" class="btn" style="background: #95a5a6; color: white;">إلغاء</a>
        </div>
    </form>
</div>
@endsection









