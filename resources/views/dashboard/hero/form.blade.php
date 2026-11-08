@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>{{ $slide->id ? 'تعديل شريحة' : 'إضافة شريحة جديدة' }}</h1>
</div>

<div class="content-box">
    <form action="{{ $slide->id ? route('dashboard.hero.store', $slide->id) : route('dashboard.hero.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($slide->id)
            @method('PUT')
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>العنوان (إنجليزي)</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $slide->title) }}">
            </div>
            <div class="form-group">
                <label>العنوان (عربي)</label>
                <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar', $slide->title_ar) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>العنوان الفرعي (إنجليزي)</label>
                <textarea name="subtitle" class="form-control" rows="3">{{ old('subtitle', $slide->subtitle) }}</textarea>
            </div>
            <div class="form-group">
                <label>العنوان الفرعي (عربي)</label>
                <textarea name="subtitle_ar" class="form-control" rows="3">{{ old('subtitle_ar', $slide->subtitle_ar) }}</textarea>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>نص الزر (إنجليزي)</label>
                <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $slide->button_text) }}">
            </div>
            <div class="form-group">
                <label>نص الزر (عربي)</label>
                <input type="text" name="button_text_ar" class="form-control" value="{{ old('button_text_ar', $slide->button_text_ar) }}">
            </div>
        </div>

        <div class="form-group">
            <label>رابط الزر</label>
            <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $slide->button_link) }}" placeholder="#services أو /page">
        </div>

        <div class="form-group">
            <label>صورة الشريحة</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if($slide->image)
                <img src="{{ asset('storage/' . $slide->image) }}" style="max-width: 300px; margin-top: 10px; border-radius: 5px;">
            @endif
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }}>
                    نشط
                </label>
            </div>
            <div class="form-group">
                <label>ترتيب العرض</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $slide->sort_order ?? 0) }}">
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">حفظ</button>
            <a href="{{ route('dashboard.hero') }}" class="btn" style="background: #95a5a6; color: white;">إلغاء</a>
        </div>
    </form>
</div>
@endsection








