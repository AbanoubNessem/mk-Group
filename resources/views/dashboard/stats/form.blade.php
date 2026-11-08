@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>{{ $stat->id ? 'تعديل إحصائية' : 'إضافة إحصائية جديدة' }}</h1>
</div>

<div class="content-box">
    <form action="{{ $stat->id ? route('dashboard.stats.store', $stat->id) : route('dashboard.stats.store') }}" method="POST">
        @csrf
        @if($stat->id)
            @method('PUT')
        @endif

        <div class="form-group">
            <label>القيمة *</label>
            <input type="text" name="value" class="form-control" value="{{ old('value', $stat->value) }}" required placeholder="مثل: 500+ أو 15">
            <small style="color: #7f8c8d;">يمكن إضافة رموز مثل + أو %</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>التسمية (إنجليزي) *</label>
                <input type="text" name="label" class="form-control" value="{{ old('label', $stat->label) }}" required placeholder="مثل: Projects Completed">
            </div>
            <div class="form-group">
                <label>التسمية (عربي) *</label>
                <input type="text" name="label_ar" class="form-control" value="{{ old('label_ar', $stat->label_ar) }}" required placeholder="مثل: المشاريع المكتملة">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $stat->is_active) ? 'checked' : '' }}>
                    نشط
                </label>
            </div>
            <div class="form-group">
                <label>ترتيب العرض</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $stat->sort_order ?? 0) }}">
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">حفظ</button>
            <a href="{{ route('dashboard.stats') }}" class="btn" style="background: #95a5a6; color: white;">إلغاء</a>
        </div>
    </form>
</div>
@endsection

