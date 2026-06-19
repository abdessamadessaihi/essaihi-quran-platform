<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>منصة آل السيحي القرآنية — نحو بيت قرآني مستقر</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet"/>
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Tajawal',sans-serif;color:#1a1a1a;overflow-x:hidden;background:#fff}
.font-amiri{font-family:'Amiri',serif}

/* ── NAVBAR ───────────────────────────────── */
.navbar{
  position:fixed;top:0;right:0;left:0;z-index:100;
  height:68px;display:flex;align-items:center;
  padding:0 40px;background:transparent;
  transition:background .35s,backdrop-filter .35s,border-color .35s;
  border-bottom:1px solid transparent;
}
.navbar.scrolled{
  background:rgba(3,24,16,.93);
  backdrop-filter:blur(18px);
  border-color:rgba(255,255,255,.07);
}
.nav-logo{
  display:flex;align-items:center;gap:10px;
  text-decoration:none;margin-left:auto;
}

/* 🌟 تعديل اللوجو في الهيدر: يكون أبيض في البداية، ويعود لطبيعته الداكنة عند السكرول */
.nav-logo-img{
  height:52px;width:auto;
  filter: brightness(0) invert(1); /* يحول اللوجو الأسود إلى أبيض تماماً */
  transition: transform .25s, filter .35s;
}
.navbar.scrolled .nav-logo-img {
  filter: none; /* يعيد اللوجو للونه الأصلي (الأسود/الملون) عندما تصبح الخلفية بيضاء */
}

.nav-logo:hover .nav-logo-img{transform:scale(1.05)}
.nav-logo-text{line-height:1.25}
.nav-logo-name{font-size:14px;font-weight:700;color:#fff}
.navbar.scrolled .nav-logo-name { color: #fff; } /* يمكنك تغييرها لـ #0a2e20 إذا أردت النص داكناً عند السكرول */
.nav-logo-tagline{font-size:10px;color:#f59e0b;opacity:.85}

.nav-links{
  display:flex;align-items:center;
  gap:2px;list-style:none;margin-right:36px;
}
.nav-links a{
  font-size:13.5px;color:rgba(255,255,255,.78);
  text-decoration:none;padding:7px 13px;
  border-radius:8px;transition:color .2s,background .2s;
}
.nav-links a:hover{color:#fff;background:rgba(255,255,255,.09)}
.nav-cta{
  margin-right:10px;padding:9px 22px;
  background:linear-gradient(135deg,#0d6b52,#065f46);
  color:#fff!important;font-weight:700;font-size:14px;
  border-radius:100px;text-decoration:none;
  border:1px solid rgba(255,255,255,.18);
  box-shadow:0 4px 16px rgba(13,107,82,.45);
  transition:transform .15s,box-shadow .15s!important;
  white-space:nowrap;
}
.nav-cta:hover{transform:translateY(-2px)!important;box-shadow:0 6px 22px rgba(13,107,82,.55)!important}

/* ── HERO ───────────────────────────────── */
.hero{
  position:relative;height:100vh;min-height:640px;
  display:flex;align-items:center;overflow:hidden;
  justify-content: flex-start;
}
.hero-bg{
  position:absolute;inset:0;
  background:
    linear-gradient(to right,
      rgba(2,20,12,.35) 0%,
      rgba(2,20,12,.75) 45%,
      rgba(2,20,12,.95) 100%),
    url('https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1800&q=85')
    center/cover no-repeat;
}
.hero-pattern{
  position:absolute;inset:0;opacity:.04;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3Ccircle cx='40' cy='40' r='18' fill='none' stroke='%23fff' stroke-width='.8'/%3E%3C/svg%3E");
  background-size:80px 80px;
}
.hero-glow{
  position:absolute;top:50%;left:5%;
  transform:translateY(-50%);
  width:520px;height:520px;border-radius:50%;
  background:radial-gradient(circle,rgba(245,158,11,.10),transparent 70%);
  pointer-events:none;
}
.hero-content{
  position:relative;z-index:10;
  max-width:880px; 
  padding-right: 120px; 
  padding-left: 40px;
  margin-left:auto;
  margin-right:0;
  text-align:right;
}
.hero-badge{
  display:inline-flex;align-items:center;gap:8px;
  padding:6px 18px;border-radius:100px;
  background:rgba(255,255,255,.08);
  border:1px solid rgba(255,255,255,.15);
  color:#fff;font-size:13.5px;font-weight:500;margin-bottom:28px;
}
.hero-badge-dot{
  width:8px;height:8px;border-radius:50%;
  background:#6ee7b7;animation:blink 2s infinite;
}
@keyframes blink{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(1.5)}}

.hero-logo-wrap{
  display:flex;align-items:center;gap:20px;
  margin-bottom:24px;
  justify-content:flex-start;
}

/* 🌟 تعديل اللوجو الكبير داخل الـ Hero: تحويله للون الأبيض الناصع ليظهر بوضوح فوق الخلفية المظلمة */
.hero-logo-img{
  height:110px;width:auto;
  filter: brightness(0) invert(1) drop-shadow(0 4px 20px rgba(255,255,255,0.1));
  animation:floatLogo 4s ease-in-out infinite;
}
@keyframes floatLogo{
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-8px)}
}
.hero-logo-divider{
  width:1px;height:90px;
  background:linear-gradient(to bottom,transparent,rgba(255,255,255,.3),transparent);
}
.hero-logo-text-wrap{line-height:1.2; text-align:right;}

.hero-logo-title{
  font-family:'Amiri',serif;
  font-size:clamp(2.5rem, 4.5vw, 3.8rem); 
  font-weight:700;color:#ffffff;
  letter-spacing: -0.5px;
}
.hero-logo-title .gold{
  color: #fcd34d;
}
.hero-logo-tagline-txt{
  font-size:16px;color:rgba(255,255,255,.70);
  font-style:italic;margin-top:4px;
}

.hero-desc{
  font-size:18px;
  line-height:1.8;
  color:rgba(255,255,255,.85);
  margin-bottom:40px;
  max-width:720px;
  font-weight: 400;
}
.hero-actions{
  display:flex;flex-wrap:wrap;gap:16px;margin-bottom:48px;
  justify-content:flex-start;
}
.btn-primary{
  display:inline-flex;align-items:center;gap:8px;
  padding:15px 36px;border-radius:100px;
  background:linear-gradient(135deg,#0d6b52,#065f46);
  color:#fff;font-size:16px;font-weight:700;
  text-decoration:none;border:none;cursor:pointer;
  box-shadow:0 8px 28px rgba(13,107,82,.45);
  transition:transform .2s,box-shadow .2s;
}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 12px 36px rgba(13,107,82,.55)}
.btn-secondary{
  display:inline-flex;align-items:center;gap:8px;
  padding:15px 36px;border-radius:100px;
  background:rgba(255,255,255,.10);
  color:#fff;font-size:16px;font-weight:600;
  text-decoration:none;
  border:1px solid rgba(255,255,255,.25);
  backdrop-filter:blur(8px);
  transition:background .2s;
}
.btn-secondary:hover{background:rgba(255,255,255,.17)}
.icon-arrow{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round}

.hero-stats{
  display:flex;gap:40px;flex-wrap:wrap;
  padding-top:24px;
  border-top:1px solid rgba(255,255,255,.12);
  justify-content:flex-start;
}
.hero-stat-item{text-align:right}
.hero-stat-num{
  font-family:'Amiri',serif;
  font-size:2rem;font-weight:700;
  color:#f59e0b;line-height:1;
}
.hero-stat-lbl{font-size:12px;color:rgba(255,255,255,.60);margin-top:4px}

/* ── SCROLL INDICATOR ─────────────────────── */
.scroll-indicator{
  position:absolute;bottom:32px;left:50%;
  transform:translateX(-50%);z-index:10;
  display:flex;flex-direction:column;align-items:center;gap:6px;
  color:rgba(255,255,255,.40);font-size:11px;
}
.scroll-arrow{
  width:24px;height:24px;stroke:currentColor;
  fill:none;stroke-width:2;
  animation:bounce 2s infinite;
}
@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(6px)}}

/* ── STATS BAND ───────────────────────────── */
.stats-band{
  background:#fff;
  border-bottom:1px solid #e5e7eb;
}
.stats-inner{
  display:grid;grid-template-columns:repeat(4,1fr);
  max-width:1100px;margin:0 auto;
}
.stat-item{
  padding:30px 20px;text-align:center;
  border-left:1px solid #e5e7eb;
  transition:background .2s;
}
.stat-item:last-child{border-left:none}
.stat-item:hover{background:#f0fdf4}
.stat-num{
  font-family:'Amiri',serif;
  font-size:2.2rem;font-weight:700;
  color:#0a4d3c;line-height:1;margin-bottom:5px;
}
.stat-label{font-size:12.5px;color:#6b7280}

/* ── SECTION SHARED ───────────────────────── */
.section-tag{
  display:inline-block;font-size:11px;font-weight:700;
  color:#0d6b52;letter-spacing:2px;
  text-transform:uppercase;margin-bottom:10px;
}
.section-title{
  font-family:'Amiri',serif;
  font-size:clamp(1.8rem,3vw,2.5rem);
  font-weight:700;color:#0a2e20;margin-bottom:12px;
}
.section-sub{
  font-size:14.5px;color:#6b7280;
  line-height:1.85;max-width:500px;margin:0 auto;
}
.divider-gold{
  display:flex;align-items:center;justify-content:center;
  gap:10px;margin:14px auto 0;
}
.divider-gold::before,.divider-gold::after{
  content:'';width:52px;height:1px;
}
.divider-gold::before{background:linear-gradient(to left,transparent,#d97706)}
.divider-gold::after{background:linear-gradient(to right,transparent,#d97706)}
.divider-gold span{color:#d97706;font-size:14px}

/* ── FEATURES ─────────────────────────────── */
.features-section{background:#f8faf9;padding:88px 0}
.features-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:22px;max-width:1100px;
  margin:52px auto 0;padding:0 40px;
}
.feature-card{
  background:#fff;border:1px solid #e5ebe8;
  border-radius:20px;padding:34px 26px;
  text-align:center;
  transition:box-shadow .25s,transform .25s,border-color .25s;
}
.feature-card:hover{
  box-shadow:0 14px 44px rgba(0,0,0,.09);
  transform:translateY(-5px);border-color:#6ee7b7;
}
.feature-icon-wrap{
  width:76px;height:76px;border-radius:18px;
  margin:0 auto 18px;
  display:flex;align-items:center;justify-content:center;
  font-size:34px;
}
.fi-green{background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0}
.fi-gold{background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fde68a}
.feature-title{font-size:15.5px;font-weight:700;color:#0a2e20;margin-bottom:9px}
.feature-desc{font-size:13px;color:#6b7280;line-height:1.85}

/* ── STEPS ────────────────────────────────── */
.steps-section{background:#fff;padding:88px 0}
.steps-grid{
  display:grid;grid-template-columns:repeat(3,1fr);
  gap:0;max-width:860px;margin:52px auto 0;
  padding:0 40px;position:relative;
}
.steps-line{
  position:absolute;top:37px;right:18%;left:18%;
  height:1px;
  background:linear-gradient(to left,transparent,#d97706 50%,transparent);
}
.step-item{text-align:center;padding:0 18px;position:relative;z-index:1}
.step-num{
  width:74px;height:74px;border-radius:50%;
  margin:0 auto 18px;
  display:flex;align-items:center;justify-content:center;
  background:#fff;border:2px solid #d97706;
  box-shadow:0 4px 18px rgba(217,119,6,.14);
  transition:background .25s,transform .2s;
}
.step-item:hover .step-num{background:#fffbeb;transform:scale(1.07)}
.step-num span{font-family:'Amiri',serif;font-size:1.9rem;font-weight:700;color:#d97706}
.step-title{font-size:14.5px;font-weight:700;color:#0a2e20;margin-bottom:7px}
.step-desc{font-size:13px;color:#6b7280;line-height:1.8}

/* ── LOGO SECTION (هنا اللوجو يظهر بلونه الطبيعي لأن الخلفية بيضاء مائلة للخضار) ── */
.logo-section{
  background:linear-gradient(135deg,#f0fdf4,#ecfdf5);
  padding:72px 40px;
  border-top:1px solid #d1fae5;
  border-bottom:1px solid #d1fae5;
}
.logo-section-inner{
  max-width:900px;margin:0 auto;
  display:flex;align-items:center;
  justify-content:center;gap:48px;
  flex-wrap:wrap;
}
.logo-section-img{
  height:140px;width:auto;
  filter:drop-shadow(0 6px 24px rgba(0,0,0,.12));
  transition:transform .3s;
}
.logo-section-img:hover{transform:scale(1.04) rotate(-1deg)}
.logo-section-text{max-width:420px}
.logo-section-title{
  font-family:'Amiri',serif;
  font-size:1.9rem;font-weight:700;
  color:#064e3b;margin-bottom:12px;
}
.logo-section-desc{
  font-size:14.5px;color:#374151;
  line-height:1.9;margin-bottom:20px;
}
.logo-section-values{
  display:flex;flex-wrap:wrap;gap:10px;
}
.value-tag{
  display:inline-flex;align-items:center;gap:6px;
  padding:5px 14px;border-radius:100px;
  background:#fff;border:1px solid #a7f3d0;
  color:#065f46;font-size:12.5px;font-weight:600;
  box-shadow:0 2px 8px rgba(0,0,0,.04);
}

/* ── QURAN BANNER ─────────────────────────── */
.quran-banner{
  position:relative;overflow:hidden;
  padding:96px 40px;background:#031810;
}
.quran-banner-bg{
  position:absolute;inset:0;opacity:.05;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E");
  background-size:80px;
}
.qb-glow-r{
  position:absolute;top:-80px;right:-80px;
  width:380px;height:380px;border-radius:50%;opacity:.22;
  background:radial-gradient(circle,#0d6b52,transparent);
}
.qb-glow-l{
  position:absolute;bottom:-80px;left:-80px;
  width:280px;height:280px;border-radius:50%;opacity:.15;
  background:radial-gradient(circle,#d97706,transparent);
}
.quran-content{position:relative;z-index:10;max-width:660px;margin:0 auto;text-align:center}
.quran-ornament{
  width:54px;height:54px;border-radius:50%;
  margin:0 auto 26px;
  display:flex;align-items:center;justify-content:center;
  background:rgba(245,158,11,.12);
  border:1px solid rgba(245,158,11,.28);
}
.quran-ornament span{font-size:20px;animation:blink 3s infinite}
.quran-ayah{
  font-family:'Amiri',serif;
  font-size:clamp(1.5rem,3vw,2.15rem);
  color:#fff;line-height:2;margin-bottom:10px;
}
.quran-ref{font-size:12.5px;color:#f59e0b;opacity:.80;margin-bottom:36px;display:block}
.quran-sep{
  width:76px;height:1px;
  background:linear-gradient(to left,transparent,#d97706,transparent);
  margin:0 auto 32px;
}
.quran-desc{
  font-size:14.5px;color:rgba(255,255,255,.62);
  line-height:2;margin-bottom:38px;
}

/* ── FOOTER ───────────────────────────────── */
.footer{
  background:#020f08;
  border-top:1px solid rgba(255,255,255,.06);
  padding:40px;
}
.footer-inner{
  max-width:1100px;margin:0 auto;
  display:flex;align-items:center;
  justify-content:space-between;flex-wrap:wrap;gap:18px;
}
.footer-logo{display:flex;align-items:center;gap:10px}
.footer-logo-img{
  height:40px;width:auto;
  filter:brightness(0) invert(1) opacity(.55);
}
.footer-logo-text .footer-name{font-size:13px;font-weight:700;color:#fff}
.footer-logo-text .footer-tag{font-size:10px;color:rgba(245,158,11,.65)}
.footer-links{display:flex;gap:22px;flex-wrap:wrap}
.footer-links a{font-size:12px;color:rgba(255,255,255,.40);text-decoration:none;transition:color .2s}
.footer-links a:hover{color:rgba(255,255,255,.75)}
.footer-copy{font-size:11px;color:rgba(255,255,255,.25)}

/* ── RESPONSIVE ───────────────────────────── */
@media(max-width:1024px){
  .hero-content{padding-right: 60px;}
}
@media(max-width:768px){
  .navbar{padding:0 18px}
  .nav-links{display:none}
  .hero-content{
    padding:0 22px;
    margin:0 auto;
    text-align:center;
  }
  .hero-logo-wrap{justify-content:center}
  .hero-logo-text-wrap{text-align: center;}
  .hero-actions{justify-content:center}
  .hero-stats{justify-content:center}
  .stats-inner{grid-template-columns:repeat(2,1fr)}
  .features-grid{padding:0 18px}
  .steps-grid{grid-template-columns:1fr;gap:28px;padding:0 22px}
  .steps-line{display:none}
  .logo-section-inner{flex-direction:column;text-align:center}
  .logo-section-values{justify-content:center}
  .footer-inner{flex-direction:column;align-items:flex-start}
  /* إلغاء الفلتر الأبيض في الجوال لو كنت تريد إظهار الألوان الاصلية للشعار */
  .nav-logo-img, .hero-logo-img { filter: none !important; }
}
</style>
</head>
<body>

{{-- ═══ NAVBAR ═══ --}}
<nav class="navbar" id="navbar">
  <a href="/" class="nav-logo">
    <img src="{{ asset('images/essaihi-logo.png') }}"
         alt="شعار عائلة آل السيحي"
         class="nav-logo-img"/>
    <div class="nav-logo-text">
      <div class="nav-logo-name">منصة آل السيحي القرآنية</div>
      <div class="nav-logo-tagline">نحو بيت قرآني مستقر</div>
    </div>
  </a>

  <ul class="nav-links">
    <li><a href="#hero">الرئيسية</a></li>
    <li><a href="#features">المميزات</a></li>
    <li><a href="#steps">كيف تبدأ</a></li>
    <li><a href="#about">عن العائلة</a></li>
  </ul>

  @guest
    <a href="{{ route('register') }}" class="nav-cta">
      انضم الآن
    </a>
  @else
    <a href="{{ route('dashboard') }}" class="nav-cta">
      لوحة التحكم
    </a>
  @endguest
</nav>


{{-- ═══ HERO ═══ --}}
<section class="hero" id="hero">
  <div class="hero-bg"></div>
  <div class="hero-pattern"></div>
  <div class="hero-glow"></div>

  <div class="hero-content">

    <div class="hero-badge">
      <div class="hero-badge-dot"></div>
      منصة عائلية قرآنية متكاملة
    </div>

    <div class="hero-logo-wrap">
      <img src="{{ asset('images/essaihi-logo.png') }}"
           alt="عائلة آل السيحي"
           class="hero-logo-img"/>
      <div class="hero-logo-divider"></div>
      <div class="hero-logo-text-wrap">
        <div class="hero-logo-title">
          منصة <span class="gold">آل السيحي</span> القرآنية
        </div>
        <div class="hero-logo-tagline-txt">نحو بيت قرآني مستقر</div>
      </div>
    </div>

    <p class="hero-desc">
      مشروع عائلي رائد يجمع أفراد العائلة حول كتاب الله عز وجل، ويقدم منظومة متكاملة لتقييد ومتابعة الورد اليومي وإدارة الختمات الجماعية وتتبع مسيرة الحفظ والمراجعة لجيل قرآني متميز.
    </p>

    <div class="hero-actions">
      @guest
        <a href="{{ route('register') }}" class="btn-primary">
          ابدأ رحلتك القرآنية
          <svg class="icon-arrow" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
        <a href="{{ route('login') }}" class="btn-secondary">
          تسجيل الدخول
        </a>
      @else
        <a href="{{ route('dashboard') }}" class="btn-primary">
          الذهاب للوحة التحكم
          <svg class="icon-arrow" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      @endguest
    </div>

  </div>

  <div class="scroll-indicator">
    <span>اكتشف أكثر</span>
    <svg class="scroll-arrow" viewBox="0 0 24 24">
      <path d="M12 5v14M5 12l7 7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </div>
</section>




{{-- ═══ FEATURES ═══ --}}
<section class="features-section" id="features">
  <div style="text-align:center;padding:0 20px">
    <span class="section-tag">ما تجده في المنصة</span>
    <h2 class="section-title">المميزات الأساسية</h2>
    <div class="divider-gold"><span>✦</span></div>
    <p class="section-sub" style="margin-top:14px">
      كل أدوات المتابعة القرآنية التي تحتاجها عائلتك في منصة واحدة.
    </p>
  </div>

  <div class="features-grid">
    @foreach([
      ['fi-green','📖','نظام الختمات القرآنية','أنشئ ختمات جماعية أو فردية ووزّع الأجزاء الثلاثين تلقائياً مع تتبع التقدم لحظة بلحظة.'],
      ['fi-gold','🌙','الورد اليومي','حدد هدفك اليومي وسجّل إنجازه بنقرة واحدة مع عداد الأيام المتتالية وخريطة الحرارة السنوية.'],
      ['fi-green','🧠','الحفظ والمراجعة','سجّل محفوظاتك وتابع مراجعاتها بنظام ذكي يجدول الدورات الدورية ويقيس مستوى الإتقان.'],
      ['fi-gold','👨‍👩‍👧','إدارة العائلة','لكل عائلة لوحة تحكم خاصة بها يديرها المسؤول ويتابع فيها أنشطة كل فرد.'],
      ['fi-green','🏆','لوحة الشرف والأوسمة','نظام نقاط وشارات يحفّز الأعضاء ويبرز أكثرهم قراءةً وحفظاً والتزاماً أسبوعياً وشهرياً.'],
      ['fi-gold','📊','إحصائيات وتقارير','رسوم بيانية تعرض تقدمك الأسبوعي والشهري والسنوي وتقارير شاملة للعائلة.'],
    ] as [$cls,$icon,$title,$desc])
    <div class="feature-card">
      <div class="feature-icon-wrap {{ $cls }}">{{ $icon }}</div>
      <h3 class="feature-title">{{ $title }}</h3>
      <p class="feature-desc">{{ $desc }}</p>
    </div>
    @endforeach
  </div>
</section>


{{-- ═══ STEPS ═══ --}}
<section class="steps-section" id="steps">
  <div style="text-align:center;padding:0 20px">
    <span class="section-tag">البداية سهلة</span>
    <h2 class="section-title">ابدأ في ثلاث خطوات</h2>
    <div class="divider-gold"><span>✦</span></div>
  </div>
  <div class="steps-grid">
    <div class="steps-line"></div>
    @foreach([
      ['1','أنشئ حسابك','سجّل في المنصة وانضم إلى عائلتك أو أنشئ عائلة جديدة في دقيقة واحدة.'],
      ['2','حدد أهدافك','اختر ورداً يومياً وانضم لختمة جارية أو أطلق ختمة عائلية جديدة.'],
      ['3','سجّل إنجازاتك','سجّل قراءتك وحفظك يومياً وتابع تقدمك مع أفراد عائلتك.'],
    ] as [$n,$t,$d])
    <div class="step-item">
      <div class="step-num"><span>{{ $n }}</span></div>
      <div class="step-title">{{ $t }}</div>
      <div class="step-desc">{{ $d }}</div>
    </div>
    @endforeach
  </div>
</section>


{{-- ═══ LOGO SECTION ═══ --}}
<section class="logo-section" id="about">
  <div class="logo-section-inner">
    <img src="{{ asset('images/essaihi-logo.png') }}"
         alt="شعار عائلة آل السيحي"
         class="logo-section-img"/>
    <div class="logo-section-text">
      <h2 class="logo-section-title">عائلة آل السيحي</h2>
      <p class="logo-section-desc">الإسهام في إعداد أسرة قرآنية رائدة، تتلو آيات الله وجدانا وتدبرا، وتتمثل حقائقه فكرا واعتقادا، وتهتدي بهداياته سلوكا وعملا.
      </p>
      <div class="logo-section-values">
        @foreach(['الالتزام','التنظيم','التحفيز','المشاركة الجماعية','الاستمرارية'] as $v)
        <span class="value-tag">✦ {{ $v }}</span>
        @endforeach
      </div>
    </div>
  </div>
</section>


{{-- ═══ QURAN BANNER ═══ --}}
<section class="quran-banner">
  <div class="quran-banner-bg"></div>
  <div class="qb-glow-r"></div>
  <div class="qb-glow-l"></div>
  <div class="quran-content">
    <blockquote class="quran-ayah font-amiri">
      ﴿ إِنَّ هَٰذَا الْقُرْآنَ يَهْدِي لِلَّتِي هِيَ أَقْوَمُ ﴾
    </blockquote>
    <cite class="quran-ref">سورة الإسراء — الآية ٩</cite>
    <div class="quran-sep"></div>
    <p class="quran-desc">
      القرآن الكريم هو دستور هذه الأمة ونور بيوتها —<br>
      لنجعل منازلنا تتلى فيها آياته صباح مساء.
    </p>
    @guest
    <a href="{{ route('register') }}" class="btn-primary"
       style="display:inline-flex;margin:0 auto">
      انضم إلى العائلة القرآنية
      <svg class="icon-arrow" viewBox="0 0 24 24">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>
    @endguest
  </div>
</section>


{{-- ═══ FOOTER ═══ --}}
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-logo">
      <img src="{{ asset('images/essaihi-logo.png') }}"
           alt="آل السيحي"
           class="footer-logo-img"/>
      <div class="footer-logo-text">
        <div class="footer-name">منصة آل السيحي القرآنية</div>
        <div class="footer-tag">نحو بيت قرآني مستقر</div>
      </div>
    </div>
    <div class="footer-links">
      <a href="#">عن المنصة</a>
      <a href="#">سياسة الخصوصية</a>
      <a href="#">اتصل بنا</a>
      <a href="#">الأسئلة الشائعة</a>
    </div>
    <p class="footer-copy">
      © {{ now()->year }} منصة آل السيحي — جميع الحقوق محفوظة
    </p>
  </div>
</footer>

<script>
window.addEventListener('scroll', () => {
  document.getElementById('navbar')
    .classList.toggle('scrolled', window.scrollY > 60);
});
</script>
</body>
</html>