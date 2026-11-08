@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>إدارة الخدمات</h1>
</div>

<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>الخدمات</h2>
        <a href="{{ route('dashboard.services.form') }}" class="btn btn-primary">إضافة خدمة جديدة</a>
    </div>

    @if($services->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الاسم (عربي)</th>
                    <th>الأيقونة</th>
                    <th>الحالة</th>
                    <th>الترتيب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->name_ar }}</td>
                        <td>{{ $service->icon ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $service->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $service->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>{{ $service->sort_order }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('dashboard.services.form', $service->id) }}" class="btn btn-warning">تعديل</a>
                                <form action="{{ route('dashboard.services.delete', $service->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
            <h3>لا توجد خدمات</h3>
            <p>ابدأ بإضافة خدمة جديدة</p>
        </div>
    @endif
</div>
@endsection









