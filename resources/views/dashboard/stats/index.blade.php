@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>إدارة الإحصائيات</h1>
</div>

<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>الإحصائيات</h2>
        <a href="{{ route('dashboard.stats.form') }}" class="btn btn-primary">إضافة إحصائية جديدة</a>
    </div>

    @if($stats->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>القيمة</th>
                    <th>التسمية (إنجليزي)</th>
                    <th>التسمية (عربي)</th>
                    <th>الحالة</th>
                    <th>الترتيب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats as $stat)
                    <tr>
                        <td><strong>{{ $stat->value }}</strong></td>
                        <td>{{ $stat->label }}</td>
                        <td>{{ $stat->label_ar }}</td>
                        <td>
                            <span class="badge {{ $stat->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $stat->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>{{ $stat->sort_order }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('dashboard.stats.form', $stat->id) }}" class="btn btn-warning">تعديل</a>
                                <form action="{{ route('dashboard.stats.delete', $stat->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
            <h3>لا توجد إحصائيات</h3>
            <p>ابدأ بإضافة إحصائية جديدة</p>
        </div>
    @endif
</div>
@endsection

