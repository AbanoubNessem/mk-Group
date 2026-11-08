@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>معلومات التواصل</h1>
</div>

<div class="content-box">
    <form action="{{ route('dashboard.contact-info.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>عنوان القسم (إنجليزي)</label>
                <input type="text" name="contact_title" class="form-control" value="{{ old('contact_title', $contactInfo->contact_title) }}">
            </div>
            <div class="form-group">
                <label>عنوان القسم (عربي)</label>
                <input type="text" name="contact_title_ar" class="form-control" value="{{ old('contact_title_ar', $contactInfo->contact_title_ar) }}">
            </div>
        </div>

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $contactInfo->email) }}">
        </div>

        <div class="form-group">
            <label>أرقام الهاتف (سطر واحد لكل رقم)</label>
            <textarea name="phone_numbers" class="form-control" rows="5" placeholder="+20 01200481113&#10;+20 01287444996">{{ old('phone_numbers', $contactInfo->phone_numbers ? implode("\n", $contactInfo->phone_numbers) : '') }}</textarea>
            <small style="color: #7f8c8d;">ضع كل رقم في سطر منفصل</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>ساعات العمل (إنجليزي)</label>
                <textarea name="hours_text" class="form-control" rows="4">{{ old('hours_text', $contactInfo->hours_text) }}</textarea>
                <small style="color: #7f8c8d;">يمكنك استخدام &lt;br&gt; للسطر الجديد</small>
            </div>
            <div class="form-group">
                <label>ساعات العمل (عربي)</label>
                <textarea name="hours_text_ar" class="form-control" rows="4">{{ old('hours_text_ar', $contactInfo->hours_text_ar) }}</textarea>
                <small style="color: #7f8c8d;">يمكنك استخدام &lt;br&gt; للسطر الجديد</small>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
        </div>
    </form>
</div>
@endsection




