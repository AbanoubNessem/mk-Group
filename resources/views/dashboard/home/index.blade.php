@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>إدارة الصفحة الرئيسية</h1>
</div>

<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>أقسام الصفحة الرئيسية</h2>
        <a href="{{ route('dashboard.home.form') }}" class="btn btn-primary">إضافة قسم جديد</a>
    </div>

    @if($sections->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>المفتاح</th>
                    <th>العنوان</th>
                    <th>العنوان (عربي)</th>
                    <th>الحالة</th>
                    <th>الترتيب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $section)
                    <tr>
                        <td>{{ $section->section_key }}</td>
                        <td>{{ $section->title ?? '-' }}</td>
                        <td>{{ $section->title_ar ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $section->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $section->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>{{ $section->sort_order }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('dashboard.home.form', $section->id) }}" class="btn btn-warning">تعديل</a>
                                <form action="{{ route('dashboard.home.delete', $section->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <h3>لا توجد أقسام</h3>
            <p>ابدأ بإضافة قسم جديد</p>
        </div>
    @endif
</div>
@endsection









