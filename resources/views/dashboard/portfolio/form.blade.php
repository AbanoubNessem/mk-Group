@extends('dashboard.layout')

@section('title', $project->id ? 'تعديل مشروع' : 'إضافة مشروع')

@section('content')
<div class="page-header">
    <h1>{{ $project->id ? 'تعديل مشروع' : 'إضافة مشروع جديد' }}</h1>
    @if ($project->id)
        <a href="{{ route('project', $project->id) }}" target="_blank" rel="noopener" class="btn btn-ghost">معاينة على الموقع</a>
    @endif
</div>

<div class="content-box">
    <form action="{{ $project->id ? route('dashboard.portfolio.store', $project->id) : route('dashboard.portfolio.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($project->id)
            @method('PUT')
        @endif

        {{-- Basic information --}}
        <fieldset class="form-section m-0 border-0 p-0">
            <legend class="form-section-title">البيانات الأساسية</legend>
            <div class="form-grid">
                <div class="form-group">
                    <label for="f-name">اسم المشروع (إنجليزي) *</label>
                    <input id="f-name" type="text" name="name" class="form-control" dir="ltr" value="{{ old('name', $project->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="f-name-ar">اسم المشروع (عربي) *</label>
                    <input id="f-name-ar" type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $project->name_ar) }}" required>
                </div>
                <div class="form-group">
                    <label for="f-desc">الوصف (إنجليزي)</label>
                    <textarea id="f-desc" name="description" class="form-control" dir="ltr" rows="5">{{ old('description', $project->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="f-desc-ar">الوصف (عربي)</label>
                    <textarea id="f-desc-ar" name="description_ar" class="form-control" rows="5">{{ old('description_ar', $project->description_ar) }}</textarea>
                    <p class="form-hint">كل سطر جديد يظهر كفقرة مستقلة في صفحة المشروع.</p>
                </div>
            </div>
        </fieldset>

        {{-- Media --}}
        <fieldset class="form-section m-0 border-0 p-0 pt-6">
            <legend class="form-section-title">الصور</legend>
            <div class="form-grid">
                <div class="form-group">
                    <label for="f-thumb">صورة الغلاف (Thumbnail)</label>
                    <input id="f-thumb" type="file" name="thumbnail" class="form-control" accept="image/*">
                    <p class="form-hint">تظهر في قائمة المشاريع وأعلى صفحة المشروع. يفضل صورة أفقية عالية الجودة.</p>
                    @if($project->thumbnail)
                        <p class="media-label">الغلاف الحالي:</p>
                        <div class="media-grid" style="grid-template-columns: minmax(0, 12rem)">
                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="الغلاف الحالي" loading="lazy">
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="f-images">صور المعرض (يمكن اختيار أكثر من صورة)</label>
                    <input id="f-images" type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    @if($project->images)
                        <p class="form-hint"><strong>تنبيه:</strong> رفع صور جديدة يستبدل كل صور المعرض الحالية ({{ count($project->images) }}). اختر كل الصور المطلوبة معًا.</p>
                        <p class="media-label">صور المعرض الحالية:</p>
                        <div class="media-grid">
                            @foreach($project->images as $i => $image)
                                <figure>
                                    <img src="{{ asset('storage/' . $image) }}" alt="صورة {{ $i + 1 }}" loading="lazy">
                                    <figcaption>{{ $i + 1 }}</figcaption>
                                </figure>
                            @endforeach
                        </div>
                    @else
                        <p class="form-hint">استخدم صورًا حقيقية للأعمال المنفذة. لا تستخدم تصميمات ثلاثية الأبعاد كأنها أعمال منفذة.</p>
                    @endif
                </div>
            </div>
        </fieldset>

        {{-- Details --}}
        <fieldset class="form-section m-0 border-0 p-0 pt-6">
            <legend class="form-section-title">تفاصيل المشروع</legend>
            <div class="form-grid">
                <div class="form-group">
                    <label for="f-area">المساحة</label>
                    <input id="f-area" type="text" name="area" class="form-control" dir="ltr" value="{{ old('area', $project->area) }}" placeholder="مثال: 350 m²">
                </div>
                <div class="form-group">
                    <label for="f-rooms">عدد الغرف</label>
                    <input id="f-rooms" type="number" name="rooms_count" class="form-control" value="{{ old('rooms_count', $project->rooms_count) }}" min="0">
                </div>
                <div class="form-group">
                    <label for="f-type">نوع المشروع (إنجليزي)</label>
                    <input id="f-type" type="text" name="project_type" class="form-control" dir="ltr" value="{{ old('project_type', $project->project_type) }}" placeholder="Residential, Commercial…">
                </div>
                <div class="form-group">
                    <label for="f-type-ar">نوع المشروع (عربي)</label>
                    <input id="f-type-ar" type="text" name="project_type_ar" class="form-control" value="{{ old('project_type_ar', $project->project_type_ar) }}" placeholder="سكني، تجاري…">
                </div>
                <div class="form-group">
                    <label for="f-func">نطاق العمل / الوظيفة الهندسية (إنجليزي)</label>
                    <input id="f-func" type="text" name="engineering_function" class="form-control" dir="ltr" value="{{ old('engineering_function', $project->engineering_function) }}">
                </div>
                <div class="form-group">
                    <label for="f-func-ar">نطاق العمل / الوظيفة الهندسية (عربي)</label>
                    <input id="f-func-ar" type="text" name="engineering_function_ar" class="form-control" value="{{ old('engineering_function_ar', $project->engineering_function_ar) }}">
                </div>
                <div class="form-group">
                    <label for="f-loc">الموقع (إنجليزي)</label>
                    <input id="f-loc" type="text" name="location" class="form-control" dir="ltr" value="{{ old('location', $project->location) }}">
                </div>
                <div class="form-group">
                    <label for="f-loc-ar">الموقع (عربي)</label>
                    <input id="f-loc-ar" type="text" name="location_ar" class="form-control" value="{{ old('location_ar', $project->location_ar) }}">
                </div>
                <div class="form-group">
                    <label for="f-cat">الفئة (إنجليزي)</label>
                    <input id="f-cat" type="text" name="category" class="form-control" dir="ltr" value="{{ old('category', $project->category) }}">
                    <p class="form-hint">تُستخدم لتصفية المشاريع في صفحة المشاريع. استخدم نفس الكتابة للمشاريع المتشابهة.</p>
                </div>
                <div class="form-group">
                    <label for="f-cat-ar">الفئة (عربي)</label>
                    <input id="f-cat-ar" type="text" name="category_ar" class="form-control" value="{{ old('category_ar', $project->category_ar) }}">
                </div>
                <div class="form-group">
                    <label for="f-country">الدولة</label>
                    <select id="f-country" name="country_id" class="form-control">
                        <option value="">-- اختر الدولة --</option>
                        @foreach(($countries ?? \App\Models\Country::active()->ordered()->get()) as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $project->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->flag_emoji ?: '🏳️' }} {{ $country->name_ar }} ({{ $country->name }})
                            </option>
                        @endforeach
                    </select>
                    <p class="form-hint">اختر الدولة التي ينتمي إليها المشروع</p>
                </div>
            </div>
        </fieldset>

        {{-- Publishing --}}
        <fieldset class="form-section m-0 border-0 p-0 pt-6">
            <legend class="form-section-title">النشر</legend>
            <div class="form-grid">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $project->id ? $project->is_active : true) ? 'checked' : '' }}>
                        نشط (يظهر على الموقع)
                    </label>
                    <p class="form-hint">المشاريع النشطة فقط تظهر على الموقع.</p>
                </div>
                <div class="form-group">
                    <label for="f-sort">ترتيب العرض</label>
                    <input id="f-sort" type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $project->sort_order ?? 0) }}">
                    <p class="form-hint">الرقم الأصغر يظهر أولًا.</p>
                </div>
            </div>
        </fieldset>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">حفظ</button>
            <a href="{{ route('dashboard.portfolio') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection
