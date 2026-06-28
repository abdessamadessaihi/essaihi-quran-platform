@extends('layouts.app')
@section('title', 'تحقق من بريدك')

@section('content')
<div style="max-width:480px;margin:60px auto;text-align:center">

  {{-- أيقونة --}}
  <div style="width:80px;height:80px;border-radius:20px;
              background:linear-gradient(135deg,#ecfdf5,#d1fae5);
              border:2px solid #a7f3d0;
              display:flex;align-items:center;justify-content:center;
              font-size:36px;margin:0 auto 24px">
    📧
  </div>

  <h1 style="font-family:'Amiri',serif;font-size:1.8rem;font-weight:700;
             color:var(--text);margin-bottom:12px">
    تحقق من بريدك الإلكتروني
  </h1>

  <p style="font-size:14px;color:var(--text-m);line-height:1.8;margin-bottom:28px">
    أرسلنا رسالة تحقق إلى بريدك الإلكتروني.<br/>
    افتح بريدك واضغط على رابط التفعيل للمتابعة.
  </p>

  {{-- رسالة نجاح --}}
  @if(session('status') === 'verification-link-sent')
  <div style="background:#ecfdf5;border:1px solid #a7f3d0;
              border-radius:12px;padding:14px;
              color:#065f46;font-size:13.5px;
              margin-bottom:20px">
    ✅ تم إرسال رابط التحقق مجدداً — تحقق من بريدك
  </div>
  @endif

  {{-- زر إعادة الإرسال --}}
  <form method="POST" action="{{ route('verification.send') }}"
        style="margin-bottom:14px">
    @csrf
    <button type="submit"
            style="width:100%;padding:13px;border-radius:12px;
                   background:linear-gradient(135deg,#0d6b52,#064e3b);
                   border:none;color:#fff;font-family:'Tajawal',sans-serif;
                   font-size:14px;font-weight:700;cursor:pointer;
                   box-shadow:0 4px 16px rgba(13,107,82,.3)">
      📨 إعادة إرسال رابط التحقق
    </button>
  </form>

  {{-- تسجيل الخروج --}}
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit"
            style="font-size:13px;color:var(--text-m);
                   background:none;border:none;cursor:pointer;
                   text-decoration:underline">
      تسجيل الخروج والعودة لاحقاً
    </button>
  </form>

  {{-- نصيحة --}}
  <div style="margin-top:24px;padding:14px;border-radius:12px;
              background:var(--bg);border:1px solid var(--border);
              font-size:12.5px;color:var(--text-m);line-height:1.8;
              text-align:right">
    <strong>لم تصلك الرسالة؟</strong><br/>
    • تحقق من مجلد Spam أو Junk<br/>
    • تأكد أن البريد الذي كتبته صحيح<br/>
    • انتظر دقيقة وأعد المحاولة
  </div>

</div>
@endsection