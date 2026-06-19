@extends('layouts.app')

@section('title', 'لوحة التحكم')

@push('styles')
<style>
/* ═══ DASHBOARD VARIABLES ═══════════════════════════════════ */
:root {
  --dash-green-deep: #022c22;
  --dash-green-mid:  #064e3b;
  --dash-green-lite: #059669;
  --dash-gold:       #d97706;
  --dash-gold-lite:  #f59e0b;
  --dash-radius:     14px;
  --dash-radius-sm:  10px;
}

/* ═══ BANNER (RESPONSIVE) ═══════════════════════════════════ */
.dash-banner {
  border-radius: 18px;
  background: linear-gradient(140deg, #022c22 0%, #064e3b 60%, #0a6647 100%);
  padding: 16px;
  margin-bottom: 16px;
  position: relative;
  overflow: hidden;
}

.dash-banner::before {
  content: '';
  position: absolute; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60 L0 30Z' fill='none' stroke='%23fff' stroke-width='.8'/%3E%3C/svg%3E");
  background-size: 60px;
  opacity: .04; pointer-events: none;
}

.dash-banner-inner {
  position: relative; z-index: 1;
  display: flex; 
  flex-direction: column; /* متوافق مع الهاتف افتراضياً */
  gap: 16px;
}

@media(min-width: 640px) {
  .dash-banner-inner { flex-direction: row; align-items: center; justify-content: space-between; }
  .dash-banner { padding: 20px; }
}

.dash-banner-user {
  display: flex;
  align-items: center;
  gap: 14px;
  width: 100%;
}

.dash-banner-avatar {
  width: 52px; height: 52px;
  border-radius: 14px;
  overflow: hidden; flex-shrink: 0;
  border: 2px solid rgba(255,255,255,.2);
  background: rgba(16,185,129,.2);
}

.dash-banner-avatar img { width: 100%; height: 100%; object-fit: cover; }
.dash-banner-info { flex: 1; min-width: 0; }
.dash-banner-hint { font-size: 11px; color: rgba(167,243,208,.8); margin-bottom: 3px; }
.dash-banner-name { font-size: 18px; font-weight: 800; color: #fff; line-height: 1.2; }

.dash-banner-role {
  display: inline-block; margin-top: 5px; font-size: 10px; font-weight: 700;
  padding: 3px 10px; border-radius: 100px;
  background: rgba(255,255,255,.15); color: #fff;
  border: 1px solid rgba(255,255,255,.2);
}

.dash-banner-cta {
  display: flex; 
  align-items: center; 
  justify-content: space-between; /* توزيع متوازن على الهاتف */
  gap: 12px;
  width: 100%;
  border-top: 1px solid rgba(255,255,255,0.1);
  padding-top: 12px;
}

@media(min-width: 640px) {
  .dash-banner-cta { 
    width: auto; border-top: none; padding-top: 0;
    flex-direction: column; align-items: flex-end; gap: 8px;
  }
}

.dash-streak-chip {
  display: flex; align-items: center; gap: 8px;
  background: rgba(0,0,0,.2);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 10px; padding: 6px 14px;
}

@media(min-width: 640px) {
  .dash-streak-chip { flex-direction: column; gap: 2px; text-align: center; }
}

.dash-streak-num { font-family: 'Amiri', serif; font-size: 20px; font-weight: 700; color: var(--dash-gold-lite); line-height: 1; }
.dash-streak-label { font-size: 9px; color: rgba(255,255,255,.5); }

.dash-ward-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 16px; border-radius: 10px;
  background: linear-gradient(135deg, #d97706, #b45309);
  color: #fff; font-size: 12px; font-weight: 700; text-decoration: none;
  box-shadow: 0 4px 14px rgba(217,119,6,.4); transition: transform .2s;
}

/* ═══ STAT CARDS (MOBILE OPTIMIZED) ═════════════════════════ */
.dash-stats {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* كارتين بجانب بعض على الهاتف دائماً */
  gap: 10px; margin-bottom: 16px;
}

@media(min-width: 768px) {
  .dash-stats { grid-template-columns: repeat(4, 1fr); }
}

.dash-stat-card {
  border-radius: var(--dash-radius); padding: 12px;
  position: relative; overflow: hidden;
}

.dash-stat-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.dash-stat-icon {
  width: 34px; height: 34px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.5);
}
.dash-stat-icon img { width: 18px; height: 18px; object-fit: contain; }
.dash-stat-badge { font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 100px; background: rgba(255,255,255,.5); }
.dash-stat-val { font-family: 'Amiri', serif; font-size: 22px; font-weight: 700; line-height: 1; margin-bottom: 2px; }
.dash-stat-label { font-size: 11px; font-weight: 700; margin-bottom: 1px; }
.dash-stat-sub { font-size: 9px; opacity: .7; }

/* ═══ NEW MOBILE-FRIENDLY STATISTICS ═════════════════════════ */
.stat-progress-container {
  display: flex; flex-direction: column; gap: 16px;
}

/* مؤشر التقدم الدائري البسيط للهاتف */
.circle-stats-row {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; text-align: center;
}

.circle-stat-item {
  background: var(--bg-soft, #f0fdf4); padding: 10px 6px; border-radius: var(--dash-radius-sm);
  border: 1px solid var(--border, #e6f4ea);
}

.circle-stat-num {
  font-family: 'Amiri', serif; font-size: 18px; font-weight: bold; color: var(--dash-green-mid);
}

.circle-stat-label { font-size: 10px; color: var(--text-m); margin-top: 2px; }

/* شريط التقدم الخطي الشهري */
.monthly-progress-card {
  background: linear-gradient(to left, #f6fbf8, #ffffff);
  border: 1px dashed var(--dash-green-lite);
  border-radius: var(--dash-radius-sm); padding: 12px;
}

.monthly-bar-label { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px; }
.monthly-bar-bg { width: 100%; height: 8px; background: #e2e8f0; border-radius: 100px; overflow: hidden; }
.monthly-bar-fill { height: 100%; background: linear-gradient(to left, var(--dash-green-lite), #34d399); border-radius: 100px; }

/* ═══ RESPONSIVE MAIN GRID ══════════════════════════════════ */
.dash-grid {
  display: flex; flex-direction: column; gap: 16px;
}

@media(min-width: 1024px) {
  .dash-grid { display: grid; grid-template-columns: minmax(0, 2fr) 320px; }
}

/* ═══ SECTION CARD ══════════════════════════════════════════ */
.dash-card {
  background: var(--card, #fff); border: 1px solid var(--border, #e2e8f0); border-radius: var(--dash-radius); overflow: hidden;
}
.dash-card-head { padding: 12px 16px; border-bottom: 1px solid var(--border, #e2e8f0); display: flex; align-items: center; justify-content: space-between; }
.dash-card-title { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: var(--text); }
.dash-card-title img { width: 20px; height: 20px; object-fit: contain; }
.dash-card-body { padding: 14px; }

/* ═══ WARD CARD ═════════════════════════════════════════════ */
.ward-card-head { padding: 12px 16px; background: linear-gradient(135deg, #022c22, #064e3b); display: flex; align-items: center; gap: 10px; }
.ward-card-head-text p:first-child { color: #fff; font-size: 13px; font-weight: 700; }
.ward-card-head-text p:last-child { color: #6ee7b7; font-size: 10px; margin-top: 1px; }
.ward-card-body { padding: 16px; text-align: center; }
.ward-status-text { font-size: 12px; color: var(--text-m); margin-bottom: 12px; }
.ward-action-btn {
  display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: 10px;
  background: linear-gradient(135deg, #0d6b52, #065f46); color: #fff; font-size: 12px; font-weight: 700; text-decoration: none;
}

/* ═══ REVIEWS ═══════════════════════════════════════════════ */
.reviews-empty { padding: 16px; text-align: center; }
.reviews-empty p { font-size: 12px; color: var(--text-m); }
.reviews-empty a { display: inline-block; margin-top: 6px; font-size: 11px; color: var(--dash-green-lite); font-weight: 600; text-decoration: none; }

/* ═══ LEADERBOARD ═══════════════════════════════════════════ */
.lb-head { padding: 12px 16px; background: linear-gradient(135deg, #fffbeb, #fef3c7); display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #fde68a; }
.lb-head p { font-size: 13px; font-weight: 700; color: #78350f; }
.lb-row { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-bottom: 1px solid var(--border, #e2e8f0); }
.lb-row:last-child { border-bottom: none; }
.lb-medal { font-size: 16px; flex-shrink: 0; }
.lb-name { flex: 1; min-width: 0; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.lb-pts { font-size: 11px; color: var(--text-m); }
.lb-more { display: block; margin: 10px; text-align: center; padding: 8px; border-radius: 9px; background: #fffbeb; color: #92400e; font-size: 11px; font-weight: 700; text-decoration: none; border: 1px solid #fde68a; }

/* ═══ QUICK LINKS (GRID FIX) ════════════════════════════════ */
.quick-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; padding: 4px; }
.quick-link { display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 12px 6px; border-radius: 10px; text-decoration: none; }
.quick-link img { width: 22px; height: 22px; object-fit: contain; }
.quick-link span { font-size: 11px; font-weight: 600; color: var(--text); }

/* ═══ AYAH BANNER ═══════════════════════════════════════════ */
.ayah-banner {
  margin-top: 16px; border-radius: 18px; padding: 24px 16px; text-align: center; position: relative; overflow: hidden;
  background: linear-gradient(135deg, #031810, #042a1e);
}
.ayah-banner::before {
  content: ''; position: absolute; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E");
  background-size: 60px; opacity: .05;
}
.ayah-text { position: relative; font-family: 'Amiri', serif; font-size: clamp(1.1rem, 4.5vw, 1.5rem); color: rgba(255,255,255,.9); line-height: 1.8; margin-bottom: 6px; }
.ayah-ref { position: relative; font-size: 10px; color: #f59e0b; opacity: .8; }

.dash-side { display: flex; flex-direction: column; gap: 16px; }
</style>
@endpush

@section('content')

{{-- ═══ BANNER ═══════════════════════════════════════════════ --}}
<div class="dash-banner">
  <div class="dash-banner-inner">
    
    {{-- User Info Block --}}
    <div class="dash-banner-user">
      <div class="dash-banner-avatar">
        @if(auth()->user()->avatar_url)
          <img src="{{ asset(auth()->user()->avatar_url) }}" alt="{{ auth()->user()->name }}">
        @elseif(auth()->user()->avatar && Storage::disk('public')->exists(auth()->user()->avatar))
          <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
        @else
          <img src="{{ asset('images/user.png') }}" alt="{{ auth()->user()->name }}" style="padding:6px">
        @endif
      </div>

      <div class="dash-banner-info">
        <p class="dash-banner-hint">
          {{ now()->hour < 12 ? 'أهلاً بك في رحاب القرآن' : 'اشتاق لك القرآن، مرحباً' }}
        </p>
        <p class="dash-banner-name">{{ auth()->user()->name }}</p>
        <span class="dash-banner-role">
          @if(auth()->user()->isSuperAdmin()) المدير العام
          @elseif(auth()->user()->isFamilyAdmin()) مسؤول العائلة
          @else عضو العائلة
          @endif
        </span>
      </div>
    </div>

    {{-- Call to Action Block (Stacked on Mobile, Row on Desktop) --}}
    <div class="dash-banner-cta">
      <div class="dash-streak-chip">
        <div class="dash-streak-num">{{ $dashboardStats['current_streak'] }}</div>
        <div class="dash-streak-label">يوم متتالي 🔥</div>
      </div>
      <a href="{{ route('ward.index') }}" class="dash-ward-btn">
        🌙 {{ $todayWard?->is_completed ? 'مكتمل ✓' : 'ورد اليوم' }}
      </a>
    </div>

  </div>
</div>

{{-- ═══ STAT CARDS ════════════════════════════════════════════ --}}
<div class="dash-stats">
  
  {{-- الأيام --}}
  <div class="dash-stat-card" style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border:1px solid #fed7aa">
    <div class="dash-stat-top">
      <div class="dash-stat-icon"><img src="{{ asset('images/day.png') }}" alt=""></div>
      <span class="dash-stat-badge" style="color:#ea580c">سلسلة</span>
    </div>
    <div class="dash-stat-val" style="color:#ea580c">{{ $dashboardStats['current_streak'] }}</div>
    <div class="dash-stat-label" style="color:#7c2d12">أيام متتالية</div>
    <div class="dash-stat-sub" style="color:#9a3412">أطول: {{ $dashboardStats['longest_streak'] }}</div>
  </div>

  {{-- الأوراد --}}
  <div class="dash-stat-card" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0">
    <div class="dash-stat-top">
      <div class="dash-stat-icon"><img src="{{ asset('images/quran.png') }}" alt=""></div>
      <span class="dash-stat-badge" style="color:#059669">مكتمل</span>
    </div>
    <div class="dash-stat-val" style="color:#059669">{{ $dashboardStats['completed_wards_count'] }}</div>
    <div class="dash-stat-label" style="color:#064e3b">ورد مكتمل</div>
    <div class="dash-stat-sub" style="color:#065f46">إجمالي أورادك</div>
  </div>

  {{-- الحفظ --}}
  <div class="dash-stat-card" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe">
    <div class="dash-stat-top">
      <div class="dash-stat-icon"><img src="{{ asset('images/target2.png') }}" alt=""></div>
      <span class="dash-stat-badge" style="color:#2563eb">القرآن</span>
    </div>
    <div class="dash-stat-val" style="color:#2563eb">{{ $dashboardStats['memorized_juz_estimate'] }}</div>
    <div class="dash-stat-label" style="color:#1e3a8a">جزء محفوظ</div>
    <div class="dash-stat-sub" style="color:#1d4ed8">من أصل ٣٠ جزءاً</div>
  </div>

  {{-- النقاط --}}
  <div class="dash-stat-card" style="background:linear-gradient(135deg,#fdf4ff,#fae8ff);border:1px solid #e9d5ff">
    <div class="dash-stat-top">
      <div class="dash-stat-icon"><img src="{{ asset('images/homeStatistics3.png') }}" alt=""></div>
      <span class="dash-stat-badge" style="color:#9333ea">XP</span>
    </div>
    <div class="dash-stat-val" style="color:#9333ea">{{ $dashboardStats['total_xp'] }}</div>
    <div class="dash-stat-label" style="color:#581c87">نقطة خبرة</div>
    <div class="dash-stat-sub" style="color:#6b21a8">المستوى: {{ $dashboardStats['level'] }}</div>
  </div>

</div>

{{-- ═══ MAIN GRID ══════════════════════════════════════════════ --}}
<div class="dash-grid">

  {{-- ── عمود الإحصائيات الجديد البديل للخريطة السنوية المعقدة ── --}}
  <div>
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-title">
          <img src="{{ asset('images/calendar.png') }}" alt="">
          <div>
            خلاصة الالتزام والإنجاز الإحصائي
            <p style="font-size:10px;color:var(--text-m);font-weight:400;margin-top:1px">
              متابعة حية لبناء عاداتك القرآنية
            </p>
          </div>
        </div>
      </div>
      <div class="dash-card-body">
        
        <div class="stat-progress-container">
          
          {{-- الإحصاءات السريعة المصغرة والموزعة كخلايا ذكية للهاتف --}}
          <div class="circle-stats-row">
            <div class="circle-stat-item">
              <div class="circle-stat-num">{{ $dashboardStats['current_streak'] }} يوم</div>
              <div class="circle-stat-label">الالتزام الحالي</div>
            </div>
            <div class="circle-stat-item">
              <div class="circle-stat-num">
                {{ $dashboardStats['completed_wards_count'] > 0 ? round(($dashboardStats['completed_wards_count'] / 30) * 100) : 0 }}%
              </div>
              <div class="circle-stat-label">نسبة الختمة</div>
            </div>
            <div class="circle-stat-item">
              <div class="circle-stat-num">{{ $dashboardStats['level'] }}</div>
              <div class="circle-stat-label">الرتبة الحالية</div>
            </div>
          </div>

          {{-- مؤشر تقدم الشهر الحالي خطي ونظيف جداً على شاشة الجوال --}}
          <div class="monthly-progress-card">
            <div class="monthly-bar-label">
              <span class="font-bold text-emerald-900">📊 نسبة إنجاز ورد الشهر الحالي</span>
              <span class="font-mono text-emerald-600 font-bold">
                {{ min(100, $dashboardStats['completed_wards_count'] * 3) }}%
              </span>
            </div>
            <div class="monthly-bar-bg">
              <div class="monthly-bar-fill" style="width: {{ min(100, $dashboardStats['completed_wards_count'] * 3) }}%"></div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

  {{-- ── عمود البطاقات الجانبية (يتراصف تحت الإحصائيات في الهاتف تلقائياً) ── --}}
  <div class="dash-side">

    {{-- الورد اليومي --}}
    <div class="dash-card">
      <div class="ward-card-head">
        <span style="font-size:20px">🌙</span>
        <div class="ward-card-head-text">
          <p>الورد اليومي</p>
          <p>{{ now()->locale('ar')->isoFormat('dddd، D MMM') }}</p>
        </div>
      </div>
      <div class="ward-card-body">
        <p class="ward-status-text">
          @if($todayWard?->is_completed)
            اكتمل ورد اليوم بحمد الله ✓
          @elseif($todayWard)
            إنجاز اليوم: {{ $todayWard->adherence_pct }}٪
          @else
            لم تسجّل ورداً اليوم بعد
          @endif
        </p>
        <a href="{{ route('ward.index') }}" class="ward-action-btn">
          {{ $todayWard ? 'فتح الورد' : 'ابدأ الآن' }}
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="transform: scaleX(-1); margin-right:4px;">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>

    {{-- مراجعات اليوم --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-title">
          <img src="{{ asset('images/brain.png') }}" alt="">
          مراجعات اليوم
        </div>
        <span style="font-size:10px;padding:2px 8px;border-radius:100px;background:#eff6ff;color:#1d4ed8;font-weight:700">٠</span>
      </div>
      <div class="reviews-empty">
        <p>لا توجد مراجعات مجدولة اليوم</p>
        <a href="{{ route('memorizations.index') }}">+ إضافة محفوظات</a>
      </div>
    </div>
{{-- لوحة الشرف --}}
<div class="dash-card" style="border-color:#fde68a">
  <div class="lb-head">
    <span style="font-size:16px">🏆</span>
    <p>لوحة الشرف العائلية</p>
  </div>
  @forelse($topUsers as $idx => $u)
  <div class="lb-row">
    <span class="lb-medal">
      {{ ['🥇','🥈','🥉'][$idx] ?? '🏅' }}
    </span>
    <div style="flex:1;min-width:0">
      <p class="lb-name">{{ $u->name }}</p>
      @if($u->id === auth()->id())
      <span style="font-size:9px;padding:1px 5px;border-radius:100px;
                   background:#059669;color:#fff">أنت</span>
      @endif
    </div>
    <span class="lb-pts">{{ number_format($u->total_xp) }} نقطة</span>
  </div>
  @empty
  <div style="padding:20px;text-align:center;color:var(--text-m);font-size:13px">
    لا توجد بيانات بعد
  </div>
  @endforelse
  <a href="{{ route('leaderboard') }}" class="lb-more">عرض الترتيب الكامل ←</a>
</div>

    {{-- روابط سريعة --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-title">
          <img src="{{ asset('images/link.png') }}" alt="">
          روابط سريعة
        </div>
      </div>
      <div class="quick-grid">
        @foreach([
          ['families.index','muslim.png','العائلات','#ecfdf5','#a7f3d0'],
          ['khatmas.create','quran.png','ختمة جديدة','#fffbeb','#fde68a'],
          ['memorizations.index','injaz.png','الحفظ','#eff6ff','#bfdbfe'],
          ['revisions.index','brain.png','المراجعة','#fdf4ff','#e9d5ff'],
        ] as [$route,$icon,$label,$bg,$border])
        <a href="{{ route($route) }}" class="quick-link" style="background:{{ $bg }}; border:1px solid {{ $border }}">
          <img src="{{ asset('images/'.$icon) }}" alt="{{ $label }}">
          <span>{{ $label }}</span>
        </a>
        @endforeach
      </div>
    </div>

  </div>
</div>

{{-- ═══ AYAH BANNER ═══════════════════════════════════════════ --}}
<div class="ayah-banner">
  <p class="ayah-text">﴿ وَرَتِّلِ الْقُرْآنَ تَرْتِيلًا ﴾</p>
  <p class="ayah-ref">سورة المزمل — الآية ٤</p>
</div>

@endsection