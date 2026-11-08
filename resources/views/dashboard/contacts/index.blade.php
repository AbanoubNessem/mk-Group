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

<!-- Modal for showing full message -->
<div id="messageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">تفاصيل الرسالة</h2>
            <button onclick="closeMessage()" style="background: #e74c3c; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">إغلاق</button>
        </div>
        <div id="messageContent">
            <!-- Message content will be inserted here -->
        </div>
    </div>
</div>

<script>
function showMessage(id, name, email, phone, projectType, message, date) {
    const modal = document.getElementById('messageModal');
    const content = document.getElementById('messageContent');
    
    content.innerHTML = `
        <div style="margin-bottom: 15px;">
            <strong>الاسم:</strong> ${name}
        </div>
        <div style="margin-bottom: 15px;">
            <strong>البريد الإلكتروني:</strong> <a href="mailto:${email}">${email}</a>
        </div>
        <div style="margin-bottom: 15px;">
            <strong>رقم التليفون:</strong> ${phone ? `<a href="tel:${phone}">${phone}</a>` : '-'}
        </div>
        <div style="margin-bottom: 15px;">
            <strong>نوع المشروع:</strong> ${projectType || '-'}
        </div>
        <div style="margin-bottom: 15px;">
            <strong>التاريخ:</strong> ${date}
        </div>
        <div style="margin-bottom: 15px;">
            <strong>الرسالة:</strong>
            <div style="margin-top: 10px; padding: 15px; background: #f8f9fa; border-radius: 5px; white-space: pre-wrap;">${message}</div>
        </div>
    `;
    
    modal.style.display = 'flex';
}

function closeMessage() {
    document.getElementById('messageModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('messageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMessage();
    }
});
</script>
@endsection

