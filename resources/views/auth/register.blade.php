<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>إنشاء حساب — منصة آل السيحي القرآنية</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet"/>
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Tajawal',sans-serif;
  min-height:100vh;display:flex;
  background:#f0fdf4;overflow-x:hidden;
}
/* نفس أنماط الـ auth-left من صفحة الدخول */
.auth-left{
  flex:1;position:relative;
  background:linear-gradient(160deg,#022c22 0%,#064e3b 45%,#0a6647 100%);
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;
  padding:48px;overflow:hidden;
}
.auth-left-pattern{
  position:absolute;inset:0;opacity:.06;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3Ccircle cx='40' cy='40' r='18' fill='none' stroke='%23fff' stroke-width='.8'/%3E%3C/svg%3E");
  background-size:80px;
}
.auth-left-glow{
  position:absolute;top:-100px;left:-100px;width:400px;height:400px;
  border-radius:50%;background:radial-gradient(circle,rgba(16,185,129,.25),transparent 70%);
}
.auth-left-glow2{
  position:absolute;bottom:-80px;right:-80px;width:300px;height:300px;
  border-radius:50%;background:radial-gradient(circle,rgba(217,119,6,.18),transparent 70%);
}
.auth-left-content{position:relative;z-index:10;text-align:center;max-width:380px}
.auth-brand-logo{
  height:110px;width:auto;
  filter:drop-shadow(0 8px 32px rgba(0,0,0,.4));
  margin-bottom:24px;
  animation:floatLogo 4s ease-in-out infinite;
}
@keyframes floatLogo{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.auth-brand-name{
  font-family:'Amiri',serif;font-size:1.85rem;
  font-weight:700;color:#fff;margin-bottom:6px;line-height:1.4;
}
.auth-brand-name .gold{
  background:linear-gradient(135deg,#fbbf24,#f59e0b);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.auth-brand-tagline{font-size:13px;color:rgba(255,255,255,.60);margin-bottom:36px;font-style:italic}
.auth-divider{
  display:flex;align-items:center;justify-content:center;
  gap:12px;margin-bottom:32px;
}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.15)}
.auth-divider span{color:#f59e0b;font-size:16px}
.auth-ayah{font-family:'Amiri',serif;font-size:1.2rem;color:rgba(255,255,255,.85);line-height:1.9;margin-bottom:8px}
.auth-ayah-ref{font-size:11.5px;color:#f59e0b;opacity:.75}

/* خطوات التسجيل */
.register-steps{margin-top:32px;display:flex;flex-direction:column;gap:16px;text-align:right}
.register-step{
  display:flex;align-items:center;gap:14px;
  padding:12px 16px;border-radius:12px;
  background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);
}
.register-step-num{
  width:32px;height:32px;border-radius:50%;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  background:rgba(245,158,11,.18);border:1px solid rgba(245,158,11,.35);
  font-family:'Amiri',serif;font-size:1.1rem;font-weight:700;color:#fcd34d;
}
.register-step-text{font-size:13px;color:rgba(255,255,255,.72);line-height:1.5}

/* ── الجانب الأيسر — النموذج ─── */
.auth-right{
  width:520px;flex-shrink:0;background:#fff;
  display:flex;flex-direction:column;
  justify-content:center;
  padding:48px 44px;overflow-y:auto;
}
.auth-back-link{
  display:inline-flex;align-items:center;gap:6px;
  color:#6b7280;font-size:13px;text-decoration:none;
  margin-bottom:28px;transition:color .2s;
}
.auth-back-link:hover{color:#064e3b}
.auth-back-link svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}
.auth-form-welcome{font-size:12px;font-weight:700;color:#059669;letter-spacing:2px;margin-bottom:6px}
.auth-form-title{font-family:'Amiri',serif;font-size:1.85rem;font-weight:700;color:#0a2e20;margin-bottom:6px}
.auth-form-subtitle{font-size:13.5px;color:#6b7280;line-height:1.7;margin-bottom:28px}

.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:7px}
.form-label span{color:#ef4444;margin-right:2px}
.form-input{
  width:100%;padding:12px 14px;
  border:1.5px solid #e5e7eb;border-radius:11px;
  font-family:'Tajawal',sans-serif;font-size:14px;color:#1a1a1a;
  background:#fafafa;transition:border-color .2s,box-shadow .2s,background .2s;outline:none;
}
.form-input:focus{border-color:#059669;background:#fff;box-shadow:0 0 0 4px rgba(5,150,105,.09)}
.form-input.error{border-color:#ef4444}
.form-input-wrap{position:relative}
.form-input-icon{
  position:absolute;top:50%;right:13px;transform:translateY(-50%);
  width:17px;height:17px;color:#9ca3af;pointer-events:none;
}
.form-input.with-icon{padding-right:40px}
.input-error{font-size:11.5px;color:#ef4444;margin-top:4px}

/* مستوى قوة كلمة المرور */
.password-strength{margin-top:8px}
.strength-bar{
  display:flex;gap:4px;margin-bottom:4px;
}
.strength-segment{
  flex:1;height:3px;border-radius:100px;
  background:#e5e7eb;transition:background .3s;
}
.strength-label{font-size:11px;color:#6b7280}

.btn-submit{
  width:100%;padding:14px;
  background:linear-gradient(135deg,#0d6b52,#065f46);
  color:#fff;font-family:'Tajawal',sans-serif;
  font-size:15.5px;font-weight:700;
  border:none;border-radius:12px;cursor:pointer;
  box-shadow:0 6px 22px rgba(13,107,82,.38);
  transition:transform .15s,box-shadow .15s;
  display:flex;align-items:center;justify-content:center;gap:8px;
  margin-top:6px;
}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(13,107,82,.48)}
.btn-submit svg{width:17px;height:17px;stroke:currentColor;fill:none;stroke-width:2.2}

.auth-terms{font-size:12px;color:#9ca3af;text-align:center;margin-top:14px;line-height:1.7}
.auth-terms a{color:#059669;text-decoration:none}

.auth-login-link{text-align:center;font-size:13.5px;color:#6b7280;margin-top:20px}
.auth-login-link a{color:#059669;font-weight:700;text-decoration:none}
.auth-login-link a:hover{color:#047857}

@media(max-width:900px){
  .auth-left{display:none}
  .auth-right{width:100%;padding:36px 24px}
  .form-row-2{grid-template-columns:1fr}
}
</style>
</head>
<body>

{{-- الجانب الزخرفي --}}
<div class="auth-left">
  <div class="auth-left-pattern"></div>
  <div class="auth-left-glow"></div>
  <div class="auth-left-glow2"></div>
  <div class="auth-left-content">
    <img src="{{ asset('images/essaihi-logo.png') }}"
         alt="شعار آل السيحي" class="auth-brand-logo"/>
    <h1 class="auth-brand-name">
      منصة <span class="gold">آل السيحي</span><br>القرآنية
    </h1>
    <p class="auth-brand-tagline">نحو بيت قرآني مستقر</p>
    <div class="auth-divider"><span>✦</span></div>
    <blockquote class="auth-ayah">
      ﴿ خَيْرُكُمْ مَنْ تَعَلَّمَ الْقُرْآنَ وَعَلَّمَهُ ﴾
    </blockquote>
    <cite class="auth-ayah-ref">صحيح البخاري</cite>
    <div class="register-steps">
      @foreach([
        ['١','أنشئ حسابك بالبيانات الأساسية'],
        ['٢','انضم إلى عائلتك أو أنشئ عائلة'],
        ['٣','ابدأ رحلتك القرآنية فوراً'],
      ] as [$n,$t])
      <div class="register-step">
        <div class="register-step-num">{{ $n }}</div>
        <span class="register-step-text">{{ $t }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- نموذج التسجيل --}}
<div class="auth-right">

  <a href="{{ route('home') }}" class="auth-back-link">
    <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    العودة للرئيسية
  </a>

  <p class="auth-form-welcome">انضم إلينا</p>
  <h2 class="auth-form-title">إنشاء حساب جديد</h2>
  <p class="auth-form-subtitle">
    سجّل بياناتك وانضم إلى منصة عائلتك القرآنية
  </p>

  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;
              padding:12px 16px;margin-bottom:20px;">
    @foreach($errors->all() as $error)
      <p style="font-size:12.5px;color:#b91c1c;margin-bottom:2px">⚠️ {{ $error }}</p>
    @endforeach
  </div>
  @endif

  <form method="POST" action="{{ route('register') }}">
    @csrf

    {{-- الاسم الكامل --}}
    <div class="form-group">
      <label class="form-label" for="name">
        الاسم الكامل <span>*</span>
      </label>
      <div class="form-input-wrap">
        <svg class="form-input-icon" stroke="currentColor" fill="none"
             stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
        <input id="name" name="name" type="text"
               value="{{ old('name') }}"
               placeholder="محمد آل السيحي"
               autocomplete="name"
               class="form-input with-icon @error('name') error @enderror"/>
      </div>
      @error('name')
        <p class="input-error">⚠️ {{ $message }}</p>
      @enderror
    </div>

    {{-- البريد الإلكتروني --}}
    <div class="form-group">
      <label class="form-label" for="email">
        البريد الإلكتروني <span>*</span>
      </label>
      <div class="form-input-wrap">
        <svg class="form-input-icon" stroke="currentColor" fill="none"
             stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <input id="email" name="email" type="email"
               value="{{ old('email') }}"
               placeholder="example@email.com"
               autocomplete="email" dir="ltr"
               class="form-input with-icon @error('email') error @enderror"/>
      </div>
      @error('email')
        <p class="input-error">⚠️ {{ $message }}</p>
      @enderror
    </div>

    {{-- رقم الهاتف (اختياري) --}}
    <div class="form-group">
      <label class="form-label" for="phone">
        رقم الهاتف
        <span style="color:#9ca3af;font-weight:400">(اختياري)</span>
      </label>
      <div class="form-input-wrap">
        <svg class="form-input-icon" stroke="currentColor" fill="none"
             stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
        </svg>
        <input id="phone" name="phone" type="tel"
               value="{{ old('phone') }}"
               placeholder="+212 6XX XXX XXX"
               dir="ltr"
               class="form-input with-icon"/>
      </div>
    </div>

    {{-- كلمة المرور --}}
    <div class="form-group" x-data="{show:false, strength:0}"
         x-init="$watch('$el.querySelector(`#password`).value', v => {
           let s=0;
           if(v.length>=8)s++;
           if(/[A-Z]/.test(v))s++;
           if(/[0-9]/.test(v))s++;
           if(/[^A-Za-z0-9]/.test(v))s++;
           strength=s;
         })">
      <label class="form-label" for="password">
        كلمة المرور <span>*</span>
      </label>
      <div class="form-input-wrap">
        <svg class="form-input-icon" stroke="currentColor" fill="none"
             stroke-width="1.8" viewBox="0 0 24 24">
          <rect x="3" y="11" width="18" height="11" rx="2"/>
          <path d="M7 11V7a5 5 0 0110 0v4"/>
        </svg>
        <input id="password" name="password"
               :type="show?'text':'password'"
               placeholder="٨ أحرف على الأقل"
               autocomplete="new-password"
               class="form-input with-icon @error('password') error @enderror"
               style="padding-left:40px"/>
        <button type="button" @click="show=!show"
                style="position:absolute;top:50%;left:12px;
                       transform:translateY(-50%);
                       background:none;border:none;cursor:pointer;color:#9ca3af">
          <svg x-show="!show" style="width:17px;height:17px"
               stroke="currentColor" fill="none" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
          <svg x-show="show" style="width:17px;height:17px"
               stroke="currentColor" fill="none" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
          </svg>
        </button>
      </div>
      {{-- شريط قوة كلمة المرور --}}
      <div class="password-strength">
        <div class="strength-bar">
          <div class="strength-segment"
               :style="strength>=1?'background:'+(strength<=1?'#ef4444':strength<=2?'#f59e0b':'#059669'):''"
          ></div>
          <div class="strength-segment"
               :style="strength>=2?'background:'+(strength<=2?'#f59e0b':'#059669'):''"
          ></div>
          <div class="strength-segment"
               :style="strength>=3?'background:#059669':''"
          ></div>
          <div class="strength-segment"
               :style="strength>=4?'background:#059669':''"
          ></div>
        </div>
        <span class="strength-label" x-text="
          strength===0?'':
          strength===1?'ضعيفة':
          strength===2?'مقبولة':
          strength===3?'جيدة':
          'قوية جداً ✓'
        "></span>
      </div>
      @error('password')
        <p class="input-error">⚠️ {{ $message }}</p>
      @enderror
    </div>

    {{-- تأكيد كلمة المرور --}}
    <div class="form-group">
      <label class="form-label" for="password_confirmation">
        تأكيد كلمة المرور <span>*</span>
      </label>
      <div class="form-input-wrap">
        <svg class="form-input-icon" stroke="currentColor" fill="none"
             stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <input id="password_confirmation"
               name="password_confirmation"
               type="password"
               placeholder="أعد إدخال كلمة المرور"
               autocomplete="new-password"
               class="form-input with-icon"/>
      </div>
    </div>

    {{-- زر التسجيل --}}
    <button type="submit" class="btn-submit">
      <svg viewBox="0 0 24 24">
        <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8zM19 8v6M22 11h-6"/>
      </svg>
      إنشاء الحساب والانضمام
    </button>

    <p class="auth-terms">
      بالتسجيل، أوافق على
      <a href="#">شروط الاستخدام</a>
      و
      <a href="#">سياسة الخصوصية</a>
    </p>

    <p class="auth-login-link">
      لديك حساب بالفعل؟
      <a href="{{ route('login') }}">سجّل دخولك</a>
    </p>

  </form>
</div>

</body>
</html>