@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>إدارة الدول</h1>
</div>

<div class="content-box">
    @if(session('success'))
        <div class="alert alert-success" style="background: #27ae60; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>الدول</h2>
        <a href="{{ route('dashboard.countries.form') }}" class="btn btn-primary">إضافة دولة جديدة</a>
    </div>

    @if($countries->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>العلم</th>
                    <th>الاسم (إنجليزي)</th>
                    <th>الاسم (عربي)</th>
                    <th>رمز العلم</th>
                    <th>الحالة</th>
                    <th>الترتيب</th>
                    <th>عدد المشاريع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($countries as $country)
                    <tr>
                        <td style="font-size: 32px;">{{ $country->flag_emoji ?: '🏳️' }}</td>
                        <td>{{ $country->name }}</td>
                        <td>{{ $country->name_ar }}</td>
                        <td>{{ $country->flag_code ?: '-' }}</td>
                        <td>
                            <span class="badge {{ $country->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $country->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>{{ $country->sort_order }}</td>
                        <td>{{ $country->projects()->count() }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('dashboard.countries.form', $country->id) }}" class="btn btn-warning">تعديل</a>
                                <form action="{{ route('dashboard.countries.delete', $country->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
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
            <h3>لا توجد دول</h3>
            <p>ابدأ بإضافة دولة جديدة</p>
        </div>
    @endif
</div>
@endsection

