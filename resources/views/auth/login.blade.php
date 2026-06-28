<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>تسجيل الدخول — منصة آل السيحي القرآنية</title>
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
  overflow-x:hidden; /* يسمح بالتمرير العمودي ويمنع الأفقي */
}

/* ── الجانب الأيمن — زخرفي ─────────────────── */
.auth-left{
  flex:1;position:relative;
  background:linear-gradient(160deg,#022c22 0%,#064e3b 45%,#0a6647 100%);
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  padding:48px;
  overflow-y:auto; /* تفعيل التمرير الداخلي في حال صغر الشاشة */
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
.auth-features{
  margin-top:36px;display:flex;
  flex-direction:column;gap:12px;
  text-align:right;
}
.auth-feature-item{
  display:flex;align-items:center;gap:12px;
  padding:10px 16px;border-radius:12px;
  background:rgba(255,255,255,.06);
  border:1px solid rgba(255,255,255,.08);
  transition:background .2s;
}
.auth-feature-item:hover{background:rgba(255,255,255,.10)}
.auth-feature-icon{
  width:36px;height:36px;border-radius:9px;
  display:flex;align-items:center;justify-content:center;
  font-size:18px;flex-shrink:0;
  background:rgba(245,158,11,.15);
}
.auth-feature-text{font-size:13px;color:rgba(255,255,255,.75);line-height:1.5}

/* ── الجانب الأيسر — النموذج ────────────────── */
.auth-right{
  width:480px;flex-shrink:0;
  background:#fff;
  display:flex;flex-direction:column;
  justify-content:flex-start; /* تغيير المحاذاة لتبدأ من الأعلى عند ظهور شريط التمرير */
  padding:56px 48px;
  overflow-y:auto; /* تفعيل التمرير العمودي */
  max-height: 100vh; /* التأكد من أنه لا يتعدى حجم الشاشة لتفعيل السكرول الداخلي */
}
.auth-back-link{
  display:inline-flex;align-items:center;gap:6px;
  color:#6b7280;font-size:13px;text-decoration:none;
  margin-bottom:36px;transition:color .2s;
}
.auth-back-link:hover{color:#064e3b}
.auth-back-link svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}

.auth-form-header{margin-bottom:36px}
.auth-form-welcome{
  font-size:12px;font-weight:700;
  color:#059669;letter-spacing:2px;
  text-transform:uppercase;margin-bottom:8px;
}
.auth-form-title{
  font-family:'Amiri',serif;
  font-size:2rem;font-weight:700;
  color:#0a2e20;margin-bottom:8px;
}
.auth-form-subtitle{font-size:14px;color:#6b7280;line-height:1.7}

/* حقول الإدخال */
.form-group{margin-bottom:22px}
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
.input-error{
  font-size:12px;color:#ef4444;
  margin-top:5px;display:flex;align-items:center;gap:4px;
}

.form-row{
  display:flex;align-items:center;
  justify-content:space-between;margin-bottom:26px;
}
.form-check{display:flex;align-items:center;gap:8px;cursor:pointer}
.form-check input[type=checkbox]{
  width:16px;height:16px;
  accent-color:#059669;cursor:pointer;
}
.form-check-label{font-size:13.5px;color:#4b5563}
.form-forgot{
  font-size:13px;color:#059669;
  text-decoration:none;font-weight:600;
  transition:color .2s;
}
.form-forgot:hover{color:#047857}

.btn-submit{
  width:100%;padding:15px;
  background:linear-gradient(135deg,#0d6b52,#065f46);
  color:#fff;font-family:'Tajawal',sans-serif;
  font-size:16px;font-weight:700;
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

.auth-separator{
  display:flex;align-items:center;gap:14px;
  margin:24px 0;color:#9ca3af;font-size:13px;
}
.auth-separator::before,.auth-separator::after{
  content:'';flex:1;height:1px;background:#e5e7eb;
}

.auth-register-link{
  text-align:center;font-size:14px;color:#6b7280;
}
.auth-register-link a{
  color:#059669;font-weight:700;
  text-decoration:none;transition:color .2s;
}
.auth-register-link a:hover{color:#047857}

/* ── Responsive ─────────────────────────────── */
@media(max-width:900px){
  .auth-left{display:none}
  .auth-right{
    width:100%;
    padding:40px 28px;
    max-height: none; /* إلغاء تقييد الارتفاع على الجوال والتلفاز للسماح للمتصفح بعمل سكرول طبيعي */
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
    <img src="{{ asset('images/essaihi-logo.png') }}"
         alt="شعار آل السيحي"
         class="auth-brand-logo"/>

    <h1 class="auth-brand-name">
      منصة <span class="gold">آل السيحي</span><br>القرآنية
    </h1>
    <p class="auth-brand-tagline">نحو بيت قرآني مستقر</p>

    <div class="auth-divider"><span>✦</span></div>

    <blockquote class="auth-ayah">
      ﴿ وَرَتِّلِ الْقُرْآنَ تَرْتِيلًا ﴾
    </blockquote>
    <cite class="auth-ayah-ref">سورة المزمل — الآية ٤</cite>

    <div class="auth-features">
      @foreach([
        ['📖','متابعة الورد اليومي والختمات'],
        ['🧠','تتبّع الحفظ والمراجعة الذكية'],
        ['🏆','لوحة الشرف والأوسمة العائلية'],
        ['📊','إحصائيات وتقارير تفصيلية'],
      ] as [$icon,$text])
      <div class="auth-feature-item">
        <div class="auth-feature-icon">{{ $icon }}</div>
        <span class="auth-feature-text">{{ $text }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- الجانب الأيسر — النموذج --}}
<div class="auth-right">

  <a href="{{ route('home') }}" class="auth-back-link">
    <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    العودة للرئيسية
  </a>

  <div class="auth-form-header">
    <p class="auth-form-welcome">مرحباً بعودتك</p>
    <h2 class="auth-form-title">تسجيل الدخول</h2>
    <p class="auth-form-subtitle">
      أدخل بياناتك للوصول إلى منصتك القرآنية العائلية
    </p>
  </div>

  {{-- رسالة الخطأ العامة --}}
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

  <form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- البريد الإلكتروني --}}
    <div class="form-group">
      <label class="form-label" for="email">
        البريد الإلكتروني <span>*</span>
      </label>
      <div class="form-input-wrap">
        <svg class="form-input-icon" viewBox="0 0 24 24"
             stroke="currentColor" fill="none" stroke-width="1.8">
          <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <input id="email" name="email" type="email"
               value="{{ old('email') }}"
               placeholder="example@email.com"
               autocomplete="email" autofocus
               class="form-input with-icon @error('email') error @enderror"
               dir="ltr"/>
      </div>
    </div>

    {{-- كلمة المرور --}}
    <div class="form-group">
      <label class="form-label" for="password">
        كلمة المرور <span>*</span>
      </label>
      <div class="form-input-wrap" x-data="{show:false}">
        <svg class="form-input-icon" viewBox="0 0 24 24"
             stroke="currentColor" fill="none" stroke-width="1.8">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0110 0v4"/>
        </svg>
        <input id="password" name="password"
               :type="show ? 'text' : 'password'"
               placeholder="••••••••"
               autocomplete="current-password"
               class="form-input with-icon @error('password') error @enderror"
               style="padding-left:44px"/>
        <button type="button" @click="show=!show"
                style="position:absolute;top:50%;left:14px;
                       transform:translateY(-50%);
                       background:none;border:none;cursor:pointer;
                       color:#9ca3af;padding:0">
          <svg x-show="!show" style="width:18px;height:18px"
               stroke="currentColor" fill="none" stroke-width="1.8"
               viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
          <svg x-show="show" style="width:18px;height:18px"
               stroke="currentColor" fill="none" stroke-width="1.8"
               viewBox="0 0 24 24">
            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
          </svg>
        </button>
      </div>
    </div>

    {{-- تذكّرني + نسيت كلمة المرور --}}
    <div class="form-row">
      <label class="form-check">
        <input type="checkbox" name="remember"/>
        <span class="form-check-label">تذكّرني</span>
      </label>
      @if(Route::has('password.request'))
      <a href="{{ route('password.request') }}" class="form-forgot">
        نسيت كلمة المرور؟
      </a>
      @endif
      
    </div>
    

    {{-- زر الدخول --}}
    <button type="submit" class="btn-submit">
      <svg viewBox="0 0 24 24">
        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
      </svg>
      دخول إلى المنصة
    </button>

    <div class="auth-separator">أو</div>

    <p class="auth-register-link">
      ليس لديك حساب؟
      <a href="{{ route('register') }}">سجّل الآن مجاناً</a>
 

  </form>

  {{-- ✨ زر الانتقال السريع لبوابة حسابات البراعم والأطفال ✨ --}}
  <div style="margin-top: 24px; padding: 16px; background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px dashed #3b82f6; border-radius: 14px; text-align: center;">
      <span style="font-size: 22px; display: block; margin-bottom: 4px;">🧒🏼👧🏻</span>
      <p style="font-size: 13px; font-weight: 800; color: #1e3a8a; margin-bottom: 4px;">هل أنت من البراعم أو الأطفال؟</p>
      <p style="font-size: 11px; color: #2563eb; margin-bottom: 10px;">سجل دخولك بدون بريد أو هاتف باستخدام الـ PIN السري</p>
      <a href="{{ route('child.login') }}" style="display: inline-block; padding: 8px 20px; font-size: 12.5px; font-weight: 700; color: #fff; background: #2563eb; text-decoration: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(37,99,235,0.2);">
          بوابة دخول الأطفال والبراعم ←
      </a>
  </div>

</div>
</body>
</html>