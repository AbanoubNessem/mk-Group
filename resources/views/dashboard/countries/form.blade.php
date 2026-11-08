@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>{{ $country->id ? 'تعديل دولة' : 'إضافة دولة جديدة' }}</h1>
</div>

<div class="content-box">
    <form action="{{ $country->id ? route('dashboard.countries.store', $country->id) : route('dashboard.countries.store') }}" method="POST">
        @csrf
        @if($country->id)
            @method('PUT')
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>الاسم (إنجليزي) *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $country->name) }}" required>
            </div>
            <div class="form-group">
                <label>الاسم (عربي) *</label>
                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $country->name_ar) }}" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>رمز العلم (Flag Code)</label>
                <input type="text" name="flag_code" class="form-control" value="{{ old('flag_code', $country->flag_code) }}" placeholder="مثل: eg, us, ae, sa">
                <small style="color: #7f8c8d;">رمز الدولة بثلاثة أحرف (اختياري)</small>
            </div>
            <div class="form-group">
                <label>إيموجي العلم (Flag Emoji) *</label>
                <input type="text" name="flag_emoji" class="form-control" value="{{ old('flag_emoji', $country->flag_emoji) }}" placeholder="🇪🇬, 🇺🇸, 🇦🇪, 🇸🇦" required>
                <small style="color: #7f8c8d;">يمكنك نسخ الإيموجي من: <a href="https://emojipedia.org/flags/" target="_blank">Emojipedia Flags</a></small>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $country->id ? $country->is_active : true) ? 'checked' : '' }}>
                    نشط
                </label>
                <small style="display: block; color: #666; margin-top: 5px;">الدول النشطة فقط تظهر في شريط الدول</small>
            </div>
            <div class="form-group">
                <label>ترتيب العرض</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $country->sort_order ?? 0) }}">
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">حفظ</button>
            <a href="{{ route('dashboard.countries') }}" class="btn" style="background: #95a5a6; color: white;">إلغاء</a>
        </div>
    </form>
</div>
@endsection

