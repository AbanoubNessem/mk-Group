@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>{{ $service->id ? 'تعديل خدمة' : 'إضافة خدمة جديدة' }}</h1>
</div>

<div class="content-box">
    <form action="{{ $service->id ? route('dashboard.services.store', $service->id) : route('dashboard.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($service->id)
            @method('PUT')
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>الاسم (إنجليزي) *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
            </div>
            <div class="form-group">
                <label>الاسم (عربي) *</label>
                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $service->name_ar) }}" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>الوصف (إنجليزي)</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description', $service->description) }}</textarea>
            </div>
            <div class="form-group">
                <label>الوصف (عربي)</label>
                <textarea name="description_ar" class="form-control" rows="5">{{ old('description_ar', $service->description_ar) }}</textarea>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>الأيقونة</label>
                <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon) }}" placeholder="مثل: fa-home أو رابط الصورة">
            </div>
            <div class="form-group">
                <label>الرابط (Slug)</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $service->slug) }}">
                <small style="color: #7f8c8d;">سيتم إنشاؤه تلقائياً إذا لم يتم تحديده</small>
            </div>
        </div>

        <div class="form-group">
            <label>صورة الخدمة</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if($service->image)
                <img src="{{ asset('storage/' . $service->image) }}" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
            @endif
        </div>

        <div class="form-group">
            <label>المميزات (إنجليزي) - كل مميزة في سطر جديد</label>
            <textarea name="features" class="form-control" rows="5" placeholder="ميزة 1&#10;ميزة 2&#10;ميزة 3">@if($service->features && is_array($service->features)){{ implode("\n", $service->features) }}@endif</textarea>
        </div>

        <div class="form-group">
            <label>المميزات (عربي) - كل مميزة في سطر جديد</label>
            <textarea name="features_ar" class="form-control" rows="5" placeholder="ميزة 1&#10;ميزة 2&#10;ميزة 3">@if($service->features_ar && is_array($service->features_ar)){{ implode("\n", $service->features_ar) }}@endif</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                    نشط
                </label>
            </div>
            <div class="form-group">
                <label>ترتيب العرض</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">حفظ</button>
            <a href="{{ route('dashboard.services') }}" class="btn" style="background: #95a5a6; color: white;">إلغاء</a>
        </div>
    </form>
</div>

@endsection

