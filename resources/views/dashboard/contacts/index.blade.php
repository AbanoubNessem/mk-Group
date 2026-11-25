@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>رسائل التواصل</h1>
</div>

<div class="content-box">
    <h2 style="margin-bottom: 20px;">جميع الرسائل الواردة</h2>

    @if($contacts->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم التليفون</th>
                    <th>نوع المشروع</th>
                    <th>الرسالة</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                        <td>
                            @if($contact->phone)
                                <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $contact->project_type ?? '-' }}</td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $contact->message }}">
                            {{ \Illuminate\Support\Str::limit($contact->message, 100) }}
                        </td>
                        <td>{{ $contact->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="actions">
                                <button type="button" class="btn btn-warning" onclick="showMessage({{ $contact->id }}, {{ json_encode($contact->name) }}, {{ json_encode($contact->email) }}, {{ json_encode($contact->phone ?? '') }}, {{ json_encode($contact->project_type ?? '') }}, {{ json_encode($contact->message) }}, {{ json_encode($contact->created_at->format('Y-m-d H:i')) }})">عرض</button>
                                <form action="{{ route('dashboard.contacts.delete', $contact->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
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
            <h3>لا توجد رسائل</h3>
            <p>لم يتم استلام أي رسائل حتى الآن</p>
        </div>
    @endif
</div>

<!-- Message details. Values are inserted with textContent only (visitor input is untrusted). -->
<dialog id="messageModal" class="m-auto w-[min(40rem,92vw)] border border-stone bg-white p-0 text-charcoal backdrop:bg-charcoal-deep/50" aria-labelledby="messageModalTitle">
    <div class="flex items-center justify-between gap-4 border-b border-stone px-6 py-4">
        <h2 id="messageModalTitle">تفاصيل الرسالة</h2>
        <button type="button" class="btn btn-ghost" onclick="closeMessage()">إغلاق</button>
    </div>
    <dl class="grid grid-cols-[8rem_1fr] gap-x-4 gap-y-3 px-6 py-5 text-sm">
        <dt class="text-charcoal-soft">الاسم</dt><dd data-f="name"></dd>
        <dt class="text-charcoal-soft">البريد الإلكتروني</dt><dd><a data-f="email" class="underline" dir="ltr"></a></dd>
        <dt class="text-charcoal-soft">رقم التليفون</dt><dd><a data-f="phone" class="underline" dir="ltr"></a></dd>
        <dt class="text-charcoal-soft">نوع المشروع</dt><dd data-f="projectType"></dd>
        <dt class="text-charcoal-soft">التاريخ</dt><dd data-f="date" dir="ltr" class="text-end"></dd>
    </dl>
    <div class="px-6 pb-6">
        <p class="mb-2 text-sm text-charcoal-soft">الرسالة</p>
        <div data-f="message" class="max-h-[50vh] overflow-y-auto bg-ivory p-4 whitespace-pre-wrap"></div>
    </div>
</dialog>

<script>
function showMessage(id, name, email, phone, projectType, message, date) {
    const modal = document.getElementById('messageModal');
    const set = (key, value) => { modal.querySelector('[data-f="' + key + '"]').textContent = value || '-'; };
    set('name', name);
    set('projectType', projectType);
    set('date', date);
    set('message', message);
    const emailEl = modal.querySelector('[data-f="email"]');
    emailEl.textContent = email || '-';
    email ? emailEl.setAttribute('href', 'mailto:' + encodeURIComponent(email).replace('%40', '@')) : emailEl.removeAttribute('href');
    const phoneEl = modal.querySelector('[data-f="phone"]');
    phoneEl.textContent = phone || '-';
    phone ? phoneEl.setAttribute('href', 'tel:' + String(phone).replace(/[^\d+]/g, '')) : phoneEl.removeAttribute('href');
    modal.showModal();
}

function closeMessage() {
    document.getElementById('messageModal').close();
}

// Close when clicking the backdrop
document.getElementById('messageModal').addEventListener('click', function (e) {
    if (e.target === this) closeMessage();
});
</script>
@endsection

