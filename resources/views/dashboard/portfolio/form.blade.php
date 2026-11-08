@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>{{ $project->id ? 'تعديل مشروع' : 'إضافة مشروع جديد' }}</h1>
</div>

<div class="content-box">
    <form action="{{ $project->id ? route('dashboard.portfolio.store', $project->id) : route('dashboard.portfolio.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($project->id)
            @method('PUT')
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>اسم المشروع (إنجليزي) *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $project->name) }}" required>
            </div>
            <div class="form-group">
                <label>اسم المشروع (عربي) *</label>
                <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $project->name_ar) }}" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>الوصف (إنجليزي)</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="form-group">
                <label>الوصف (عربي)</label>
                <textarea name="description_ar" class="form-control" rows="5">{{ old('description_ar', $project->description_ar) }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label>الصورة المصغرة (Thumbnail)</label>
            <input type="file" name="thumbnail" class="form-control" accept="image/*">
            @if($project->thumbnail)
                <img src="{{ asset('storage/' . $project->thumbnail) }}" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
            @endif
        </div>

        <div class="form-group">
            <label>صور المشروع (يمكن اختيار أكثر من صورة)</label>
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
            @if($project->images)
                <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;">
                    @foreach($project->images as $image)
                        <img src="{{ asset('storage/' . $image) }}" style="max-width: 150px; border-radius: 5px;">
                    @endforeach
                </div>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>المساحة</label>
                <input type="text" name="area" class="form-control" value="{{ old('area', $project->area) }}">
            </div>
            <div class="form-group">
                <label>عدد الغرف</label>
                <input type="number" name="rooms_count" class="form-control" value="{{ old('rooms_count', $project->rooms_count) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>نوع المشروع (إنجليزي)</label>
                <input type="text" name="project_type" class="form-control" value="{{ old('project_type', $project->project_type) }}">
            </div>
            <div class="form-group">
                <label>نوع المشروع (عربي)</label>
                <input type="text" name="project_type_ar" class="form-control" value="{{ old('project_type_ar', $project->project_type_ar) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>الوظيفة الهندسية (إنجليزي)</label>
                <input type="text" name="engineering_function" class="form-control" value="{{ old('engineering_function', $project->engineering_function) }}">
            </div>
            <div class="form-group">
                <label>الوظيفة الهندسية (عربي)</label>
                <input type="text" name="engineering_function_ar" class="form-control" value="{{ old('engineering_function_ar', $project->engineering_function_ar) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>الموقع (إنجليزي)</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', $project->location) }}">
            </div>
            <div class="form-group">
                <label>الموقع (عربي)</label>
                <input type="text" name="location_ar" class="form-control" value="{{ old('location_ar', $project->location_ar) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>الفئة (إنجليزي)</label>
                <input type="text" name="category" class="form-control" value="{{ old('category', $project->category) }}">
            </div>
        </div>

        <div class="form-group">
            <label>الفئة (عربي)</label>
            <input type="text" name="category_ar" class="form-control" value="{{ old('category_ar', $project->category_ar) }}">
        </div>

        <div class="form-group">
            <label>الدولة</label>
            <select name="country_id" class="form-control">
                <option value="">-- اختر الدولة --</option>
                @if(isset($countries))
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ old('country_id', $project->country_id) == $country->id ? 'selected' : '' }}>
                            {{ $country->flag_emoji ?: '🏳️' }} {{ $country->name_ar }} ({{ $country->name }})
                        </option>
                    @endforeach
                @else
                    @foreach(\App\Models\Country::active()->ordered()->get() as $country)
                        <option value="{{ $country->id }}" {{ old('country_id', $project->country_id) == $country->id ? 'selected' : '' }}>
                            {{ $country->flag_emoji ?: '🏳️' }} {{ $country->name_ar }} ({{ $country->name }})
                        </option>
                    @endforeach
                @endif
            </select>
            <small style="display: block; color: #666; margin-top: 5px;">اختر الدولة التي ينتمي إليها المشروع</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $project->id ? $project->is_active : true) ? 'checked' : '' }}>
                    نشط
                </label>
                <small style="display: block; color: #666; margin-top: 5px;">المشاريع النشطة فقط تظهر في الصفحة الرئيسية</small>
            </div>
            <div class="form-group">
                <label>ترتيب العرض</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $project->sort_order ?? 0) }}">
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">حفظ</button>
            <a href="{{ route('dashboard.portfolio') }}" class="btn" style="background: #95a5a6; color: white;">إلغاء</a>
        </div>
    </form>
</div>
@endsection



