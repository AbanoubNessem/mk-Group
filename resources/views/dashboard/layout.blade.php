<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة التحكم - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        .sidebar-header h2 {
            font-size: 24px;
            font-weight: 600;
        }
        .sidebar-menu {
            list-style: none;
        }
        .sidebar-menu li {
            margin: 5px 0;
        }
        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #34495e;
            border-right: 3px solid #3498db;
        }
        .main-content {
            margin-right: 250px;
            flex: 1;
            padding: 30px;
        }
        .page-header {
            background: white;
            padding: 20px 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .page-header h1 {
            font-size: 28px;
            color: #2c3e50;
        }
        .content-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background: #2980b9;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .btn-success:hover {
            background: #229954;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        /* High Quality Image Rendering */
        img {
            image-rendering: auto;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: high-quality;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
            height: auto;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background: #d68910;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th,
        .table td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        .table tr:hover {
            background: #f8f9fa;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Cairo', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #3498db;
        }
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
        }
        .badge-success {
            background: #27ae60;
            color: white;
        }
        .badge-danger {
            background: #e74c3c;
            color: white;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }
        .empty-state h3 {
            margin-bottom: 10px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>لوحة التحكم</h2>
                <p style="font-size: 12px; color: #95a5a6; margin-top: 5px;">{{ Auth::user()->name }}</p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">الرئيسية</a></li>
                <li><a href="{{ route('dashboard.home') }}" class="{{ request()->routeIs('dashboard.home*') ? 'active' : '' }}">الصفحة الرئيسية</a></li>
                <li><a href="{{ route('dashboard.services') }}" class="{{ request()->routeIs('dashboard.services*') ? 'active' : '' }}">الخدمات</a></li>
                <li><a href="{{ route('dashboard.about') }}" class="{{ request()->routeIs('dashboard.about*') ? 'active' : '' }}">من نحن</a></li>
                <li><a href="{{ route('dashboard.portfolio') }}" class="{{ request()->routeIs('dashboard.portfolio*') ? 'active' : '' }}">المشاريع</a></li>
                <li><a href="{{ route('dashboard.contacts') }}" class="{{ request()->routeIs('dashboard.contacts*') ? 'active' : '' }}">رسائل التواصل</a></li>
                <li><a href="{{ route('dashboard.contact-info') }}" class="{{ request()->routeIs('dashboard.contact-info*') ? 'active' : '' }}">معلومات التواصل</a></li>
                <li><a href="{{ route('home') }}" target="_blank">عرض الموقع</a></li>
            </ul>
            <div style="padding: 20px; border-top: 1px solid #34495e; margin-top: auto;">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 10px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; font-family: 'Cairo', sans-serif; font-size: 14px; transition: background 0.3s;">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')
    <script src="{{ asset('js/image-compress.js') }}"></script>
</body>
</html>






