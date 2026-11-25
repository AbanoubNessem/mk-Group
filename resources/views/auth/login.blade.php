<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>تسجيل الدخول — لوحة التحكم — MK Group</title>
    @vite(['resources/css/dashboard.css'])
</head>
<body>
    <main class="grid min-h-dvh lg:grid-cols-2">
        <div class="hidden flex-col justify-between bg-charcoal-deep p-12 text-white lg:flex">
            <img src="{{ asset('assets/images/mk-logo-light.png') }}" alt="MK Group" width="1127" height="582" class="h-16 w-auto self-start">
            <p class="max-w-sm font-display text-3xl font-light leading-snug text-white">إدارة مشاريع وخدمات ومحتوى موقع MK Group</p>
        </div>

        <div class="flex items-center justify-center p-6">
            <div class="w-full max-w-sm">
                <img src="{{ asset('assets/images/mk-logo-slate.png') }}" alt="MK Group" width="1127" height="582" class="mb-10 h-14 w-auto lg:hidden">
                <h1 class="font-display text-3xl font-light">تسجيل الدخول</h1>
                <p class="mt-2 text-sm text-charcoal-soft">لوحة التحكم</p>

                @if($errors->any())
                    <div class="alert alert-danger mt-6" role="alert">
                        <ul class="list-none !ps-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8">
                    @csrf
                    <div class="form-group">
                        <label for="username">اسم المستخدم أو البريد الإلكتروني</label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required autofocus autocomplete="username" dir="ltr">
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" dir="ltr">
                    </div>

                    <div class="form-group">
                        <label for="remember">
                            <input type="checkbox" id="remember" name="remember">
                            تذكرني
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-full min-h-12">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
