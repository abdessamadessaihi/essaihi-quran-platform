<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>استعادة كلمة المرور — منصة آل السيحي القرآنية</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet"/>
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Tajawal',sans-serif;
  min-height:100vh;
  display:flex;
  background:#f0fdf4;
  overflow-x:hidden;
}

/* ── الجانب الأيمن — زخرفي (ثابت ومطابق لجميع صفحات الدخول) ────────────────── */
.auth-left{
  flex:1;position:relative;
  background:linear-gradient(160deg,#022c22 0%,#064e3b 45%,#0a6647 100%);
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  padding:48px;overflow-y:auto;
}
.auth-left-pattern{
  position:absolute;inset:0;opacity:.06;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3Ccircle cx='40' cy='40' r='18' fill='none' stroke='%23fff' stroke-width='.8'/%3E%3C/svg%3E");
  background-size:80px;
}
.auth-left-glow{
  position:absolute;top:-100px;left:-100px;
  width:400px;height:400px;border-radius:50%;
  background:radial-gradient(circle,rgba(16,185,129,.25),transparent 70%);
  pointer-events:none;
}
.auth-left-glow2{
  position:absolute;bottom:-80px;right:-80px;
  width:300px;height:300px;border-radius:50%;
  background:radial-gradient(circle,rgba(217,119,6,.18),transparent 70%);
  pointer-events:none;
}
.auth-left-content{position:relative;z-index:10;text-align:center;max-width:380px}
.auth-brand-logo{
  height:120px;width:auto;
  filter:drop-shadow(0 8px 32px rgba(0,0,0,.4));
  margin-bottom:28px;
  animation:floatLogo 4s ease-in-out infinite;
}
@keyframes floatLogo{
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-10px)}
}
.auth-brand-name{
  font-family:'Amiri',serif;
  font-size:2rem;font-weight:700;
  color:#fff;margin-bottom:8px;line-height:1.4;
}
.auth-brand-name .gold{
  background:linear-gradient(135deg,#fbbf24,#f59e0b);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  background-clip:text;
}
.auth-brand-tagline{
  font-size:13px;color:rgba(255,255,255,.60);
  margin-bottom:40px;font-style:italic;
}
.auth-divider{
  display:flex;align-items:center;justify-content:center;
  gap:12px;margin-bottom:36px;
}
.auth-divider::before,.auth-divider::after{
  content:'';flex:1;height:1px;
  background:rgba(255,255,255,.15);
}
.auth-divider span{color:#f59e0b;font-size:16px}
.auth-ayah{
  font-family:'Amiri',serif;
  font-size:1.25rem;color:rgba(255,255,255,.85);
  line-height:1.9;margin-bottom:8px;
}
.auth-ayah-ref{
  font-size:11.5px;color:#f59e0b;opacity:.75;
}

/* ── الجانب الأيسر — النموذج ────────────────── */
.auth-right{
  width:480px;flex-shrink:0;
  background:#fff;
  display:flex;flex-direction:column;
  justify-content:flex-start;
  padding:56px 48px;
  overflow-y:auto;
  max-height: 100vh;
}
.auth-back-link{
  display:inline-flex;align-items:center;gap:6px;
  color:#6b7280;font-size:13px;text-decoration:none;
  margin-bottom:36px;transition:color .2s;
}
.auth-back-link:hover{color:#064e3b}
.auth-back-link svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}

.auth-form-header{margin-bottom:32px}
.auth-form-welcome{
  font-size:11px;font-weight:700;
  color:#d97706;letter-spacing:1px;
  text-transform:uppercase;margin-bottom:8px;
}
.auth-form-title{
  font-family:'Amiri',serif;
  font-size:2rem;font-weight:700;
  color:#0a2e20;margin-bottom:12px;
}
.auth-form-subtitle{
  font-size:13px;color:#4b5563;line-height:1.7;
  background:#f8fafc;border:1px solid #e2e8f0;
  padding:14px;border-radius:12px;
}

/* حقول الإدخال */
.form-group{margin-bottom:24px}
.form-label{
  display:block;font-size:13.5px;font-weight:600;
  color:#374151;margin-bottom:8px;
}
.form-label span{color:#ef4444;margin-right:2px}
.form-input{
  width:100%;padding:13px 16px;
  border:1.5px solid #e5e7eb;
  border-radius:12px;
  font-family:'Tajawal',sans-serif;
  font-size:14.5px;color:#1a1a1a;
  background:#fafafa;
  transition:border-color .2s,box-shadow .2s,background .2s;
  outline:none;
}
.form-input:focus{
  border-color:#059669;
  background:#fff;
  box-shadow:0 0 0 4px rgba(5,150,105,.10);
}
.form-input.error{
  border-color:#ef4444;
  box-shadow:0 0 0 4px rgba(239,68,68,.08);
}
.form-input-wrap{position:relative}
.form-input-icon{
  position:absolute;top:50%;right:14px;
  transform:translateY(-50%);
  width:18px;height:18px;
  color:#9ca3af;pointer-events:none;
}
.form-input.with-icon{padding-right:44px}

.btn-submit{
  width:100%;padding:15px;
  background:linear-gradient(135deg,#0d6b52,#065f46);
  color:#fff;font-family:'Tajawal',sans-serif;
  font-size:15px;font-weight:700;
  border:none;border-radius:12px;cursor:pointer;
  box-shadow:0 6px 22px rgba(13,107,82,.38);
  transition:transform .15s,box-shadow .15s;
  display:flex;align-items:center;justify-content:center;gap:8px;
}
.btn-submit:hover{
  transform:translateY(-2px);
  box-shadow:0 10px 30px rgba(13,107,82,.48);
}
.btn-submit:active{transform:translateY(0)}
.btn-submit svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2.2}

/* ── Responsive ─────────────────────────────── */
@media(max-width:900px){
  .auth-left{display:none}
  .auth-right{
    width:100%;padding:40px 28px;max-height: none;
  }
}
</style>
</head>
<body>

{{-- الجانب الأيمن الزخرفي --}}
<div class="auth-left">
  <div class="auth-left-pattern"></div>
  <div class="auth-left-glow"></div>
  <div class="auth-left-glow2"></div>

  <div class="auth-left-content">
    <img src="{{ asset('images/essaihi-logo.png') }}" alt="شعار آل السيحي" class="auth-brand-logo"/>
    <h1 class="auth-brand-name">منصة <span class="gold">آل السيحي</span><br>القرآنية</h1>
    <p class="auth-brand-tagline">نحو بيت قرآني مستقر</p>
    <div class="auth-divider"><span>✦</span></div>
    <blockquote class="auth-ayah">﴿ وَرَتِّلِ الْقُرْآنَ تارتِيلًا ﴾</blockquote>
    <cite class="auth-ayah-ref">سورة المزمل — الآية ٤</cite>
  </div>
</div>

{{-- الجانب الأيسر — نموذج طلب الاستعادة --}}
<div class="auth-right">

  <a href="{{ route('login') }}" class="auth-back-link">
    <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    العودة لصفحة تسجيل الدخول
  </a>

  <div class="auth-form-header">
    <p class="auth-form-welcome">🔑 استرجاع الحساب</p>
    <h2 class="auth-form-title">نسيت كلمة المرور؟</h2>
    <p class="auth-form-subtitle">
        لا تقلق! فقط أدخل بريدك الإلكتروني المسجل لدينا وسنرسل لك عبره رابطاً آمناً يتيح لك تعيين كلمة مرور جديدة بكل سهولة.
    </p>
  </div>

  @if (session('status'))
  <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:14px 16px; margin-bottom:22px; display:flex; align-items:flex-start; gap:10px;">
    <p style="font-size:13px; color:#166534; margin:0; font-weight:600;">
        {{ __(session('status')) }}
    </p>
  </div>
@endif

  {{-- عرض أخطاء التحقق إن وجدت --}}
  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;
              padding:14px 16px;margin-bottom:22px;
              display:flex;align-items:flex-start;gap:10px;">
    <span style="font-size:18px;flex-shrink:0">⚠️</span>
    <div>
      @foreach($errors->all() as $error)
        <p style="font-size:13px;color:#b91c1c;margin-bottom:2px">{{ $error }}</p>
      @endforeach
    </div>
  </div>
  @endif

  <form method="POST" action="{{ route('password.email') }}">
    @csrf

    {{-- حقل البريد الإلكتروني --}}
    <div class="form-group">
      <label class="form-label" for="email">
        البريد الإلكتروني المسجل <span>*</span>
      </label>
      <div class="form-input-wrap">
        <svg class="form-input-icon" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.8">
          <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <input id="email" name="email" type="email"
               value="{{ old('email') }}"
               placeholder="example@email.com"
               required autofocus
               class="form-input with-icon @error('email') error @enderror"
               dir="ltr"/>
      </div>
    </div>

    {{-- زر إرسال الرابط --}}
    <button type="submit" class="btn-submit">
      <svg viewBox="0 0 24 24">
        <path d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5"/>
      </svg>
      إرسال رابط استعادة كلمة المرور
    </button>

  </form>

</div>
</body>
</html>