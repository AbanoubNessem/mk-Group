@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>إدارة شرائح الصفحة الرئيسية</h1>
</div>

<div class="content-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>شرائح الصفحة الرئيسية</h2>
        <a href="{{ route('dashboard.hero.form') }}" class="btn btn-primary">إضافة شريحة جديدة</a>
    </div>

    @if($slides->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>العنوان (إنجليزي)</th>
                        <th>العنوان (عربي)</th>
                        <th>العنوان الفرعي (إنجليزي)</th>
                        <th>العنوان الفرعي (عربي)</th>
                        <th>نص الزر (إنجليزي)</th>
                        <th>نص الزر (عربي)</th>
                        <th>رابط الزر</th>
                        <th>الحالة</th>
                        <th>الترتيب</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slides as $index => $slide)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($slide->image)
                                    <img src="{{ asset('storage/' . $slide->image) }}" style="max-width: 80px; max-height: 60px; border-radius: 5px; object-fit: cover;">
                                @else
                                    <span style="color: #999;">-</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $slide->title ?? '-' }}</strong>
                            </td>
                            <td>
                                <strong>{{ $slide->title_ar ?? '-' }}</strong>
                            </td>
                            <td>
                                <small style="color: #666;">{{ Str::limit($slide->subtitle ?? '-', 50) }}</small>
                            </td>
                            <td>
                                <small style="color: #666;">{{ Str::limit($slide->subtitle_ar ?? '-', 50) }}</small>
                            </td>
                            <td>
                                <span style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                    {{ $slide->button_text ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                    {{ $slide->button_text_ar ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($slide->button_link)
                                    <a href="{{ $slide->button_link }}" target="_blank" style="color: #3498db; font-size: 12px;">
                                        {{ Str::limit($slide->button_link, 30) }}
                                    </a>
                                @else
                                    <span style="color: #999;">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $slide->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $slide->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $slide->sort_order }}</strong>
                            </td>
                            <td>
                                <div class="actions" style="display: flex; gap: 5px;">
                                    <a href="{{ route('dashboard.hero.form', $slide->id) }}" class="btn btn-warning" style="padding: 6px 12px; font-size: 12px;">تعديل</a>
                                    <form action="{{ route('dashboard.hero.delete', $slide->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشريحة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <h3>لا توجد شرائح</h3>
            <p>ابدأ بإضافة شريحة جديدة</p>
        </div>
    @endif
</div>
@endsection

