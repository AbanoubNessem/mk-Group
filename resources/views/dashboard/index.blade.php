@extends('dashboard.layout')

@section('content')
<div class="page-header">
    <h1>لوحة التحكم الرئيسية</h1>
</div>

<div class="content-box">
    <h2 style="margin-bottom: 20px;">مرحباً بك في لوحة التحكم</h2>
    <p style="margin-bottom: 20px; color: #7f8c8d;">من هنا يمكنك إدارة جميع أقسام موقعك</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
        <div style="background: #3498db; color: white; padding: 30px; border-radius: 8px;">
            <h3 style="margin-bottom: 10px;">الصفحة الرئيسية</h3>
            <p style="margin-bottom: 15px;">إدارة أقسام الصفحة الرئيسية</p>
            <a href="{{ route('dashboard.home') }}" class="btn" style="background: white; color: #3498db;">إدارة</a>
        </div>
        
        <div style="background: #27ae60; color: white; padding: 30px; border-radius: 8px;">
            <h3 style="margin-bottom: 10px;">الخدمات</h3>
            <p style="margin-bottom: 15px;">إدارة الخدمات المقدمة</p>
            <a href="{{ route('dashboard.services') }}" class="btn" style="background: white; color: #27ae60;">إدارة</a>
        </div>
        
        <div style="background: #f39c12; color: white; padding: 30px; border-radius: 8px;">
            <h3 style="margin-bottom: 10px;">من نحن</h3>
            <p style="margin-bottom: 15px;">إدارة أقسام صفحة من نحن</p>
            <a href="{{ route('dashboard.about') }}" class="btn" style="background: white; color: #f39c12;">إدارة</a>
        </div>
        
        <div style="background: #e74c3c; color: white; padding: 30px; border-radius: 8px;">
            <h3 style="margin-bottom: 10px;">المشاريع</h3>
            <p style="margin-bottom: 15px;">إدارة مشاريع البورتفوليو</p>
            <a href="{{ route('dashboard.portfolio') }}" class="btn" style="background: white; color: #e74c3c;">إدارة</a>
        </div>
        
        <div style="background: #9b59b6; color: white; padding: 30px; border-radius: 8px;">
            <h3 style="margin-bottom: 10px;">الدول</h3>
            <p style="margin-bottom: 15px;">إدارة الدول والأعلام</p>
            <a href="{{ route('dashboard.countries') }}" class="btn" style="background: white; color: #9b59b6;">إدارة</a>
        </div>
        
        <div style="background: #16a085; color: white; padding: 30px; border-radius: 8px;">
            <h3 style="margin-bottom: 10px;">الإحصائيات</h3>
            <p style="margin-bottom: 15px;">إدارة إحصائيات الصفحة الرئيسية</p>
            <a href="{{ route('dashboard.stats') }}" class="btn" style="background: white; color: #16a085;">إدارة</a>
        </div>
    </div>
</div>
@endsection



