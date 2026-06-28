<!DOCTYPE html>
<html lang="ar" dir="rtl"
      x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        darkMode: localStorage.getItem('darkMode') === 'true'
      }"
      x-init="$watch('darkMode', v => localStorage.setItem('darkMode', v))"
      :class="{ 'dark': darkMode }">
      <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title>@yield('title', 'الرئيسية') — منصة آل السيحي القرآنية</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet"/>
@vite(['resources/css/app.css','resources/js/app.js'])
@stack('styles')
<style>
/* ── CSS Variables ─────────────────────────────────────── */
:root {
  --sidebar-w: 260px;
  --topbar-h: 64px;
  --primary:   #064e3b;
  --gold:      #d97706;
  --bg:        #f4f7f5;
  --card:      #ffffff;
  --border:    #e2ebe7;
  --text:      #1a2e25;
  --text-m:    #5a7568;
  --sidebar-bg:#042a1e;
  --sidebar-border: rgba(255,255,255,.07);
  --sidebar-text:   rgba(255,255,255,.70);
}
.dark {
  --bg:     #0d1f17;
  --card:   #0f2d20;
  --border: #1a3d2b;
  --text:   #e8f5ef;
  --text-m: #7dab94;
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
  font-family:'Tajawal',sans-serif;
  background:var(--bg);color:var(--text);
  min-height:100vh;overflow-x:hidden;
}

/* ══ SIDEBAR ══════════════════════════════════════════════ */
.sidebar{
  position:fixed;
  top:0;right:0;bottom:0;
  width:var(--sidebar-w);
  background:var(--sidebar-bg);
  z-index:300;
  display:flex;flex-direction:column;
  transition:transform .3s cubic-bezier(.4,0,.2,1);
  overflow:hidden;
  /* مخفي افتراضياً على الجوال */
  transform:translateX(100%);
}
/* ظاهر دائماً على سطح المكتب */
@media(min-width:1024px){
  .sidebar{ transform:translateX(0); }
}
/* مفتوح عند إضافة is-open */
.sidebar.is-open{ transform:translateX(0) !important; }

.sidebar-pattern{
  position:absolute;inset:0;opacity:.04;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3Ccircle cx='30' cy='30' r='12' fill='none' stroke='%23fff' stroke-width='.7'/%3E%3C/svg%3E");
  background-size:60px;pointer-events:none;
}
.sidebar-glow{
  position:absolute;bottom:-80px;left:-80px;
  width:280px;height:280px;border-radius:50%;
  background:radial-gradient(circle,rgba(16,185,129,.18),transparent);
  pointer-events:none;
}
.sidebar-header{
  padding:18px 18px 14px;
  border-bottom:1px solid var(--sidebar-border);
  display:flex;align-items:center;gap:10px;flex-shrink:0;
}
.sidebar-logo-img{
  height:42px;width:auto;
  filter:drop-shadow(0 2px 8px rgba(0,0,0,.4));flex-shrink:0;
}
.sidebar-logo-text{line-height:1.3}
.sidebar-logo-name{font-size:13px;font-weight:700;color:#fff}
.sidebar-logo-tag{font-size:10px;color:#f59e0b;opacity:.8}
.sidebar-user{
  padding:14px 18px;
  border-bottom:1px solid var(--sidebar-border);
  display:flex;align-items:center;gap:10px;flex-shrink:0;
}
.sidebar-avatar{
  width:40px;height:40px;border-radius:11px;
  display:flex;align-items:center;justify-content:center;
  font-size:16px;font-weight:700;color:#fff;flex-shrink:0;
  background:linear-gradient(135deg,rgba(245,158,11,.35),rgba(217,119,6,.25));
  border:1.5px solid rgba(245,158,11,.35);
}
.sidebar-user-name{
  font-size:13px;font-weight:700;color:#fff;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.sidebar-user-role{font-size:10.5px;color:#f59e0b;opacity:.85;margin-top:2px}
.sidebar-nav{
  flex:1;overflow-y:auto;padding:10px;
  scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent;
}
.sidebar-nav::-webkit-scrollbar{width:4px}
.sidebar-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:4px}
.sidebar-section-label{
  font-size:9.5px;font-weight:700;
  color:rgba(255,255,255,.30);letter-spacing:1.5px;
  text-transform:uppercase;padding:14px 10px 5px;
}
.sidebar-item{
  display:flex;align-items:center;gap:10px;
  padding:9px 11px;border-radius:10px;
  color:var(--sidebar-text);text-decoration:none;
  font-size:13px;font-weight:500;
  transition:all .18s;margin-bottom:2px;position:relative;
}
.sidebar-item:hover{color:#fff;background:rgba(255,255,255,.07)}
.sidebar-item.active{
  color:#fff;
  background:linear-gradient(135deg,rgba(16,185,129,.25),rgba(5,150,105,.15));
  border:1px solid rgba(16,185,129,.2);
}
.sidebar-item.active::before{
  content:'';position:absolute;right:0;top:50%;
  transform:translateY(-50%);width:3px;height:60%;
  background:#10b981;border-radius:4px 0 0 4px;
}
.sidebar-badge{
  margin-right:auto;font-size:10px;font-weight:700;
  padding:2px 8px;border-radius:100px;
  background:rgba(239,68,68,.25);color:#fca5a5;
}
.sidebar-footer{
  padding:10px;border-top:1px solid var(--sidebar-border);flex-shrink:0;
}

/* ══ OVERLAY ══════════════════════════════════════════════ */
.sidebar-overlay{
  position:fixed;inset:0;
  background:rgba(0,0,0,.55);
  z-index:290;
  opacity:0;visibility:hidden;
  transition:opacity .3s,visibility .3s;
}
.sidebar-overlay.active{opacity:1;visibility:visible}

/* ══ TOPBAR ═══════════════════════════════════════════════ */
.topbar{
  position:fixed;top:0;left:0;right:0;
  height:var(--topbar-h);
  background:var(--card);
  border-bottom:1px solid var(--border);
  z-index:200;
  display:flex;align-items:center;
  padding:0 16px;gap:12px;
  transition:right .3s;
}
@media(min-width:1024px){
  .topbar{ right:var(--sidebar-w); }
  .topbar.sidebar-collapsed{ right:0; }
}

.topbar-toggle{
  width:38px;height:38px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  background:var(--bg);border:1px solid var(--border);
  cursor:pointer;color:var(--text-m);
  transition:all .18s;flex-shrink:0;
}
.topbar-toggle:hover{background:var(--border);color:var(--text)}
.topbar-breadcrumb{
  display:flex;align-items:center;gap:6px;
  font-size:13px;color:var(--text-m);
  overflow:hidden;white-space:nowrap;
}
.topbar-breadcrumb .current{font-weight:700;color:var(--text)}
.topbar-right{
  margin-right:auto;display:flex;align-items:center;gap:8px;
}
.topbar-search{
  display:none;
  align-items:center;gap:8px;
  background:var(--bg);border:1.5px solid var(--border);
  border-radius:10px;padding:7px 14px;transition:border-color .2s;
}
@media(min-width:768px){.topbar-search{display:flex}}
.topbar-search:focus-within{border-color:#059669}
.topbar-search input{
  background:none;border:none;outline:none;
  font-family:'Tajawal',sans-serif;font-size:13px;
  color:var(--text);width:160px;
}
.topbar-search input::placeholder{color:var(--text-m)}
.topbar-btn{
  position:relative;width:36px;height:36px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  background:var(--bg);border:1px solid var(--border);
  cursor:pointer;color:var(--text-m);
  transition:all .18s;text-decoration:none;
}
.topbar-btn:hover{background:var(--border);color:var(--text)}
.topbar-btn-badge{
  position:absolute;top:-4px;left:-4px;
  width:16px;height:16px;border-radius:50%;
  background:#ef4444;color:#fff;font-size:9px;font-weight:700;
  display:flex;align-items:center;justify-content:center;
  border:2px solid var(--card);
}
.topbar-avatar{
  width:36px;height:36px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-size:14px;font-weight:700;color:#fff;cursor:pointer;
  background:linear-gradient(135deg,#064e3b,#0d6b52);
  border:2px solid #059669;flex-shrink:0;
}

/* ══ MAIN ══════════════════════════════════════════════════ */
.main-wrap{
  padding-top:var(--topbar-h);
  min-height:100vh;
  transition:margin-right .3s;
}
@media(min-width:1024px){
  .main-wrap{ margin-right:var(--sidebar-w); }
  .main-wrap.sidebar-collapsed{ margin-right:0; }
}
.main-content{
  padding:20px 16px;
  max-width:1400px;
}
@media(min-width:768px){.main-content{padding:24px 24px}}
@media(min-width:1024px){.main-content{padding:28px 32px}}

/* ══ CARDS ═════════════════════════════════════════════════ */
.card{
  background:var(--card);border:1px solid var(--border);
  border-radius:16px;overflow:hidden;
}
.card-header{
  padding:14px 18px;border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;gap:12px;
}
.card-header-title{
  display:flex;align-items:center;gap:10px;
  font-weight:700;font-size:14px;color:var(--text);
}
.card-icon{
  width:32px;height:32px;border-radius:9px;
  display:flex;align-items:center;justify-content:center;
  font-size:16px;flex-shrink:0;
}
.card-icon.green{background:linear-gradient(135deg,#ecfdf5,#d1fae5)}
.card-icon.gold{background:linear-gradient(135deg,#fffbeb,#fef3c7)}
.card-icon.blue{background:linear-gradient(135deg,#eff6ff,#dbeafe)}
.card-icon.red{background:linear-gradient(135deg,#fef2f2,#fee2e2)}
.card-body{padding:18px}

/* ══ DROPDOWN ══════════════════════════════════════════════ */
.dropdown{
  position:absolute;top:calc(100% + 8px);
  left:0;width:210px;
  background:var(--card);border:1px solid var(--border);
  border-radius:14px;
  box-shadow:0 12px 40px rgba(0,0,0,.12);
  z-index:400;overflow:hidden;
}
.dropdown-item{
  display:flex;align-items:center;gap:10px;
  padding:10px 14px;font-size:13px;
  color:var(--text);text-decoration:none;transition:background .15s;
}
.dropdown-item:hover{background:var(--bg)}
.dropdown-item.danger{color:#ef4444}
.dropdown-item.danger:hover{background:#fef2f2}
.dropdown-divider{height:1px;background:var(--border);margin:4px 0}

/* ══ TOAST ═════════════════════════════════════════════════ */
.toast{
  display:flex;align-items:center;gap:12px;
  padding:12px 18px;border-radius:14px;
  background:var(--card);border:1px solid var(--border);
  box-shadow:0 8px 32px rgba(0,0,0,.12);
  font-size:13.5px;font-weight:500;
  min-width:240px;max-width:360px;
  animation:slideUp .3s ease;
}
.toast.success{border-color:#a7f3d0}
.toast.error{border-color:#fca5a5}
@keyframes slideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}

/* ══ RESPONSIVE UTILITIES ══════════════════════════════════ */
@media(max-width:640px){
  .dash-stats-grid{grid-template-columns:1fr 1fr !important}
  .khatma-grid{grid-template-columns:1fr !important}
  .ward-stats{grid-template-columns:1fr 1fr !important}
  .mem-stats{grid-template-columns:1fr 1fr !important}
  .ward-form-grid{grid-template-columns:1fr !important}
  div[style*="grid-template-columns:repeat(6,1fr)"]{
    grid-template-columns:repeat(5,1fr) !important;
  }
}
@media(max-width:1024px){
  .ward-layout{grid-template-columns:1fr !important}
  .mem-layout{grid-template-columns:1fr !important}
  div[style*="grid-template-columns:minmax(0,2fr) 300px"],
  div[style*="grid-template-columns:minmax(0,2fr) 320px"]{
    grid-template-columns:1fr !important;
  }
}
</style>
</head>

<body>

{{-- ═══ SIDEBAR ═══ --}}
<aside class="sidebar" id="sidebar" :class="{ 'is-open': sidebarOpen }">
  <div class="sidebar-pattern"></div>
  <div class="sidebar-glow"></div>

  {{-- رأس --}}
  <div class="sidebar-header">
    <img src="{{ asset('images/essaihi-logo.png') }}"
         alt="شعار آل السيحي" class="sidebar-logo-img"/>
    <div class="sidebar-logo-text">
      <div class="sidebar-logo-name">آل السيحي القرآنية</div>
      <div class="sidebar-logo-tag">نحو بيت قرآني مستقر</div>
    </div>
  </div>

  {{-- معلومات المستخدم --}}
  <div class="sidebar-user">
    <div class="sidebar-avatar">
      {{ mb_substr(auth()->user()->name, 0, 1) }}
    </div>
    <div style="min-width:0;flex:1">
      <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
      <div class="sidebar-user-role">
        @if(auth()->user()->isSuperAdmin()) المدير العام
        @elseif(auth()->user()->isFamilyAdmin())  مسؤول العائلة
        @elseif(auth()->user()->isMohafid())  المحفظ الفاضل
        @elseif(auth()->user()->isStudent())  طالب علم
        @else  عضو العائلة
        @endif
      </div>
    </div>
  </div>

  {{-- القوائم --}}
  <nav class="sidebar-nav" id="sidebarNav">

    <div class="sidebar-section-label">الرئيسية</div>

    <a href="{{ route('dashboard') }}"
       class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <img src="{{ asset('images/home.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      لوحة التحكم
    </a>

    <a href="{{ route('families.index') }}"
       class="sidebar-item {{ request()->routeIs('families.*') ? 'active' : '' }}">
      <img src="{{ asset('images/muslim.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      العائلات
    </a>

    <div class="sidebar-section-label">القرآن الكريم</div>

    <a href="{{ route('articles.index') }}"
       class="sidebar-item {{ request()->routeIs('articles.*') ? 'active' : '' }}">
      <img src="{{ asset('images/articles.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      المقالات التدبرية
    </a>

    <a href="{{ route('mushaf.index') }}"
       class="sidebar-item {{ request()->routeIs('mushaf.*') ? 'active' : '' }}">
      <img src="{{ asset('images/mushaf.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      المصحف المحمدي الشريف
    </a>

    <a href="{{ route('tilawats.index') }}"
       class="sidebar-item {{ request()->routeIs('tilawats.*') ? 'active' : '' }}">
      <img src="{{ asset('images/tilawat.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      التلاوات الخاشعة
    </a>

    <a href="{{ route('khatmas.index') }}"
       class="sidebar-item {{ request()->routeIs('khatmas.*') ? 'active' : '' }}">
      <img src="{{ asset('images/quran.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      الختمات القرآنية
    </a>

    <a href="{{ route('ward.index') }}"
       class="sidebar-item {{ request()->routeIs('ward.*') ? 'active' : '' }}">
      <img src="{{ asset('images/wird.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      الورد اليومي
    </a>

    <a href="{{ route('memorizations.index') }}"
       class="sidebar-item {{ request()->routeIs('memorizations.*') ? 'active' : '' }}">
      <img src="{{ asset('images/injaz.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      الحفظ
    </a>

    <a href="{{ route('revisions.index') }}"
       class="sidebar-item {{ request()->routeIs('revisions.*') ? 'active' : '' }}">
      <img src="{{ asset('images/brain.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      المراجعة
      <span class="sidebar-badge"></span>
    </a>

    <div class="sidebar-section-label">المجتمع</div>

    {{-- 🌟 تم إضافة زر "حلقات المقارئ والتجويد" هنا بنجاح مع تفعيل الـ Active Class 🌟 --}}
    <a href="{{ route('quran-classes.index') }}"
       class="sidebar-item {{ request()->routeIs('quran-classes.*') ? 'active' : '' }}">
      <div style="font-size:22px; width:30px; text-align:center; flex-shrink:0;">🏫</div>
      حلقات التجويد والتحفيظ
    </a>

    <a href="{{ route('leaderboard') }}"
       class="sidebar-item {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
      <img src="{{ asset('images/homeStatistics3.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      لوحة الشرف
    </a>

    <a href="{{ route('notifications.index') }}"
       class="sidebar-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
      <img src="{{ asset('images/notification.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      الإشعارات
      @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
      <span class="sidebar-badge">{{ $unreadNotifCount }}</span>
      @endif
    </a>

    @if(auth()->user()->isSuperAdmin())
    <div class="sidebar-section-label">الإدارة</div>

    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
      <img src="{{ asset('images/settings.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      لوحة الإدارة
    </a>

    <a href="{{ route('admin.users.index') }}" class="sidebar-item">
      <img src="{{ asset('images/users.png') }}" alt="" style="width:30px;height:30px;object-fit:contain">
      المستخدمون
    </a>

    <a href="{{ route('admin.families.index') }}" class="sidebar-item">
      <div class="sidebar-item-icon">🏘️</div>
      إدارة العائلات
    </a>
    <a href="{{ route('quran-classes.index') }}" 
       class="sidebar-item {{ request()->routeIs('quran-classes.*') ? 'active' : '' }}">
      <div style="font-size:22px; width:30px; text-align:center; flex-shrink:0;">🏫</div>
      إدارة حلقات التحفيظ
    </a>
    @endif

  </nav>

  {{-- تذييل --}}
  <div class="sidebar-footer">
    <a href="{{ route('profile.show') }}" class="sidebar-item">
      <img src="{{ asset('images/user.png') }}" style="width:30px;height:30px;object-fit:contain"/>
      الملف الشخصي
    </a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="sidebar-item"
              style="width:100%;border:none;cursor:pointer;background:none;text-align:right">
        <img src="{{ asset('images/logout.png') }}" style="width:30px;height:30px;object-fit:contain"/>
        <span style="color:rgba(239,68,68,.80)">تسجيل الخروج</span>
      </button>
    </form>
  </div>
</aside>

{{-- Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ═══ TOPBAR ═══ --}}
<header class="topbar" id="topbar">

  <button class="topbar-toggle" id="sidebarToggle" aria-label="فتح/إغلاق القائمة">
    <svg width="18" height="18" fill="none" stroke="currentColor"
         stroke-width="2" viewBox="0 0 24 24">
      <path d="M3 12h18M3 6h18M3 18h18"/>
    </svg>
  </button>

  {{-- Breadcrumb --}}
  <div class="topbar-breadcrumb">
    <span>منصة آل السيحي</span>
    <span style="opacity:.4">/</span>
    <span class="current">@yield('title', 'الرئيسية')</span>
  </div>

  <div class="topbar-right">

    {{-- تبديل الوضع --}}
    <button class="topbar-btn" @click="darkMode = !darkMode"
            :title="darkMode ? 'الوضع النهاري' : 'الوضع الليلي'">
      <svg x-show="!darkMode" width="17" height="17" fill="none"
           stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
      </svg>
      <svg x-show="darkMode" width="17" height="17" fill="none"
           stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="5"/>
        <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
      </svg>
    </button>

    {{-- إشعارات --}}
    <a href="{{ route('notifications.index') }}" class="topbar-btn">
      <svg width="17" height="17" fill="none" stroke="currentColor"
           stroke-width="2" viewBox="0 0 24 24">
        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
      </svg>
      @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
      <span class="topbar-btn-badge">{{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}</span>
      @endif
    </a>

    {{-- قائمة المستخدم --}}
    <div class="relative" x-data="{ open: false }">
      <div class="topbar-avatar" @click="open = !open">
        {{ mb_substr(auth()->user()->name, 0, 1) }}
      </div>
      <div class="dropdown" x-show="open" @click.outside="open=false"
           x-transition style="display:none">
        <div style="padding:14px 16px 10px;border-bottom:1px solid var(--border)">
          <p style="font-size:13.5px;font-weight:700;color:var(--text)">
            {{ auth()->user()->name }}
          </p>
          <p style="font-size:11.5px;color:var(--text-m);margin-top:2px">
            {{ auth()->user()->email }}
          </p>
        </div>
        <div style="padding:6px">
          <a href="#" class="dropdown-item">
            <span>👤</span> الملف الشخصي
          </a>
          <a href="#" class="dropdown-item">
            <span>⚙️</span> الإعدادات
          </a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item danger"
                    style="width:100%;border:none;background:none;cursor:pointer;text-align:right">
              <img src="{{ asset('images/logout.png') }}" style="width:20px;height:20px;object-fit:contain">
              تسجيل الخروج
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
</header>

{{-- ═══ MAIN ═══ --}}
<div class="main-wrap" id="mainWrap">
  <div class="main-content">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show:true }" x-show="show"
         x-init="setTimeout(()=>show=false, 4000)"
         class="toast success mb-6" style="position:relative">
      <span class="toast-icon">✅</span>
      <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show:true }" x-show="show"
         x-init="setTimeout(()=>show=false, 4000)"
         class="toast error mb-6">
      <span class="toast-icon">⚠️</span>
      <span>{{ session('error') }}</span>
    </div>
    @endif

    @yield('content')
  </div>
</div>

<script>
(function () {
  var sidebar    = document.getElementById('sidebar');
  var overlay    = document.getElementById('sidebarOverlay');
  var topbar     = document.getElementById('topbar');
  var mainWrap   = document.getElementById('mainWrap');
  var toggleBtn  = document.getElementById('sidebarToggle');

  var isMobile = function () { return window.innerWidth < 1024; };

  function openSidebar() {
    sidebar.classList.add('is-open');
    if (isMobile()) {
      overlay.classList.add('active');
    } else {
      topbar.style.right  = 'var(--sidebar-w)';
      mainWrap.style.marginRight = 'var(--sidebar-w)';
    }
  }

  function closeSidebar() {
    sidebar.classList.remove('is-open');
    overlay.classList.remove('active');
    if (!isMobile()) {
      topbar.style.right  = '0';
      mainWrap.style.marginRight = '0';
    }
  }

  function toggleSidebar() {
    if (sidebar.classList.contains('is-open')) {
      if (!isMobile()) {
        var collapsed = topbar.style.right === '0px' || topbar.style.right === '0';
        if (collapsed) {
          openSidebar();
        } else {
          closeSidebar();
        }
      } else {
        closeSidebar();
      }
    } else {
      openSidebar();
    }
  }

  if (!isMobile()) {
    topbar.style.right  = 'var(--sidebar-w)';
    mainWrap.style.marginRight = 'var(--sidebar-w)';
  }

  toggleBtn.addEventListener('click', toggleSidebar);
  overlay.addEventListener('click', closeSidebar);

  document.querySelectorAll('#sidebarNav a.sidebar-item').forEach(function (link) {
    link.addEventListener('click', function () {
      if (isMobile()) {
        closeSidebar();
      }
    });
  });

  window.addEventListener('resize', function () {
    if (!isMobile()) {
      overlay.classList.remove('active');
      topbar.style.right  = 'var(--sidebar-w)';
      mainWrap.style.marginRight = 'var(--sidebar-w)';
    } else {
      if (!sidebar.classList.contains('is-open')) {
        topbar.style.right  = '';
        mainWrap.style.marginRight = '';
      }
    }
  });
})();
</script>

@stack('scripts')
</body>
</html>