@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>إدارة المشاريع</h1>
</div>

<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>مشاريع البورتفوليو</h2>
        <a href="{{ route('dashboard.portfolio.form') }}" class="btn btn-primary">إضافة مشروع جديد</a>
    </div>

    @if($projects->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>الاسم (عربي)</th>
                    <th>النوع</th>
                    <th>الحالة</th>
                    <th>الترتيب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>
                            @if($project->thumbnail)
                                <img src="{{ asset('storage/' . $project->thumbnail) }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                            @else
                                <span style="color: #7f8c8d;">-</span>
                            @endif
                        </td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->name_ar }}</td>
                        <td>{{ $project->project_type ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $project->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $project->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>{{ $project->sort_order }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('dashboard.portfolio.form', $project->id) }}" class="btn btn-warning">تعديل</a>
                                <form action="{{ route('dashboard.portfolio.delete', $project->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
            <h3>لا توجد مشاريع</h3>
            <p>ابدأ بإضافة مشروع جديد</p>
        </div>
    @endif
</div>
@endsection









