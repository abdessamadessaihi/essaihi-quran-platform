@extends('layouts.app')
@section('title', 'الختمات القرآنية')

@section('content')

{{-- ══ Page Header ══ --}}
@if (session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: bold;">
    {{ session('success') }}
</div>
@endif

<div class="page-header">
  <div class="page-header-ornament">
    <div class="ornament-line"></div>

<img src="{{ asset('images/zakhrafa.png') }}"
     alt="Idée"
     style="width:80px;height:80px;object-fit:contain">     <div class="ornament-line"></div>
  </div>
  
  <div class="page-header-content">
    <div style="flex:1;min-width:0;">
      <h1 class="page-title"  >الختمات القرآنية</h1>
      <p class="page-subtitle">تابع ختماتك الجماعية والفردية وشارك في إحياء كتاب الله</p>
    </div>
    <a href="{{ route('khatmas.create') }}" class="btn-create">
      <svg width="16" height="16" fill="none" stroke="currentColor"
           stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      ختمة جديدة
    </a>
  </div>
</div>
<div style="text-align:center;padding:32px;border-radius:20px;
            background:linear-gradient(135deg,#031810,#042a1e);
            margin-bottom:28px;position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;opacity:.04;
              background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E\");
              background-size:60px"></div>
  <p style="position:relative;font-family:'Amiri',serif;font-size:1.3rem;
            color:rgba(255,255,255,.92);line-height:2;margin-bottom:8px">
    ﴿ مَن قرأَ حرفًا من كتابِ اللَّهِ فلَهُ بِهِ حسنةٌ، والحسنةُ بعشرِ أمثالِها، لا أقولُ الم حرفٌ، ولَكن ألفٌ حرفٌ ولامٌ حرفٌ وميمٌ حرفٌ﴾  
  </p>
  <p style="position:relative;font-size:12.5px;color:#f59e0b;opacity:.85">
حديث نبوي صحيح </p>
</div>

{{-- ══ Tabs ══ --}}
<div class="tabs-wrap" x-data="{ tab: 'active' }">
  <div class="tabs">
    @foreach([
      ['active',  'النشطة', $activeKhatmas->count()],
      ['completed','المكتملة', $completedKhatmas->count()],
      ['all',     'الكل', $allKhatmas->count()],
    ] as [$key, $label,$count])
    <button class="tab-btn" :class="{ 'active': tab === '{{ $key }}' }"
            @click="tab = '{{ $key }}'">
      {{ $label }}
      <span class="tab-count">{{ $count }}</span>
    </button>
    @endforeach
  </div>

  {{-- ══ Grid النشطة ══ --}}
  <div x-show="tab === 'active'" x-transition>
    <div class="khatma-grid">

      {{-- بطاقة ختمة نشطة --}}
      @forelse($activeKhatmas as $khatma)

      <div class="khatma-card">

        {{-- رأس البطاقة --}}
        <div class="khatma-card-header">
          <div class="khatma-type-badge khatma-type-{{ $khatma->type }}">
            {{ match($khatma->type) {
              'ramadan' => ' رمضان',
              'weekly'  => ' أسبوعية',
              'family'  => ' عائلية',
              'individual'=>' فردية',
              default   => ' عامة',
            } }}
          </div>
          <div class="khatma-status-dot active"></div>
        </div>

        {{-- عنوان --}}
        <h3 class="khatma-card-title">{{ $khatma->title }}</h3>
        <p class="khatma-card-meta">
          <svg width="13" height="13" fill="none" stroke="currentColor"
               stroke-width="2" viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
          </svg>
          مشاركين · بواسطة {{ $khatma->creator->name ?? 'مجهول' }}
        </p>

        {{-- شريط التقدم --}}
        <div class="khatma-progress-wrap">
          <div class="khatma-progress-top">
            <span class="khatma-progress-label">التقدم</span>
            <span class="khatma-progress-pct">
              {{ $khatma->completion_percentage }}٪
            </span>
          </div>
          <div class="khatma-progress-bar">
            <div class="khatma-progress-fill"
                 style="width: {{ $khatma->completion_percentage }}%"></div>
          </div>
          <p class="khatma-progress-sub">
            {{ $khatma->completed_juz_count }} جزءاً مكتملاً من أصل ٣٠
          </p>
        </div>

        {{-- شبكة الأجزاء المصغّرة --}}
        <div class="juz-mini-grid">
          @for($i = 1; $i <= 30; $i++)
          <div class="juz-mini
            {{ $i <= $khatma->completed_juz_count ? 'juz-done' : ($i === $khatma->completed_juz_count+1 ? 'juz-reading' : 'juz-free') }}"
               title="الجزء {{ $i }}">
          </div>
          @endfor
        </div>

        {{-- تذييل البطاقة --}}
        <div class="khatma-card-footer">
          <span class="khatma-date">
            <svg width="12" height="12" fill="none" stroke="currentColor"
                 stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="4" width="18" height="18" rx="2"/>
              <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
            {{ $khatma->ends_at ? $khatma->ends_at->format('Y-m-d') : 'مفتوحة' }}
          </span>
          <a href="{{ route('khatmas.show', $khatma->id) }}" class="khatma-view-btn">
            فتح الختمة
            <svg width="13" height="13" fill="none" stroke="currentColor"
                 stroke-width="2.2" viewBox="0 0 24 24">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </a>
        </div>

      </div>
      @empty
      @endforelse

      {{-- بطاقة إضافة --}}
      <a href="{{ route('khatmas.create') }}" class="khatma-add-card">
        <div class="khatma-add-icon">+</div>
        <p class="khatma-add-label">ابدأ ختمة جديدة</p>
        <p class="khatma-add-sub">جماعية، عائلية، أو فردية</p>
      </a>

    </div>
  </div>

  {{-- المكتملة والكل تأتي لاحقاً --}}
  <div x-show="tab === 'completed'" x-transition>
    <div class="empty-state">
      <span class="empty-icon">✅</span>
      <p class="empty-title">الختمات المكتملة</p>
      <p class="empty-sub">ستظهر هنا ختماتك التي أتممتها بحمد الله</p>
    </div>
  </div>

  <div x-show="tab === 'all'" x-transition>
    <div class="empty-state">

<img src="{{ asset('images/quran.png') }}"
     alt="Idée"
     style="width:30px;height:30px;object-fit:contain">
           <p class="empty-title">جميع الختمات</p>
      <p class="empty-sub">نشطة ومكتملة وملغاة</p>
    </div>
  </div>

</div>



@endsection

@push('styles')
<style>
/* ══ Page Header ══════════════════════════════════════════ */
.page-header {
  margin-bottom: 32px;
}
.page-header-ornament {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 18px;
}
.ornament-line {
  flex: 1; height: 1px;
  background: linear-gradient(to left, transparent, #a7f3d0);
}
.ornament-line:last-child {
  background: linear-gradient(to right, transparent, #a7f3d0);
}
.ornament-icon {
  color: #d97706; font-size: 14px; flex-shrink: 0;
}
.page-header-content {
  display: flex; align-items: flex-start;
  justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.page-title {
  font-family: 'Amiri', serif;
  font-size: 1.9rem; font-weight: 700;
  color: var(--text); margin-bottom: 5px;
}
.page-subtitle {
  font-size: 13.5px; color: var(--text-m); line-height: 1.7;
}
.btn-create {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 11px 22px; border-radius: 12px;
  background: linear-gradient(135deg, #0d6b52, #065f46);
  color: #fff; font-size: 13.5px; font-weight: 700;
  text-decoration: none; flex-shrink: 0;
  box-shadow: 0 4px 16px rgba(13,107,82,.30);
  transition: transform .2s, box-shadow .2s;
}
.btn-create:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(13,107,82,.40);
}

/* ══ Tabs ═════════════════════════════════════════════════ */
.tabs-wrap { }
.tabs {
  display: flex; gap: 6px; margin-bottom: 24px;
  background: var(--card); border: 1px solid var(--border);
  border-radius: 14px; padding: 6px;
  width: fit-content;
}
.tab-btn {
  display: flex; align-items: center; gap: 7px;
  padding: 9px 18px; border-radius: 10px;
  border: none; background: none; cursor: pointer;
  font-family: 'Tajawal', sans-serif;
  font-size: 13.5px; font-weight: 600;
  color: var(--text-m); transition: all .2s;
}
.tab-btn:hover { background: var(--bg); color: var(--text); }
.tab-btn.active {
  background: linear-gradient(135deg, #064e3b, #0d6b52);
  color: #fff; box-shadow: 0 4px 14px rgba(13,107,82,.25);
}
.tab-count {
  font-size: 11px; padding: 1px 7px; border-radius: 100px;
  background: rgba(255,255,255,.20); font-weight: 700;
}
.tab-btn:not(.active) .tab-count {
  background: var(--bg); color: var(--text-m);
}

/* ══ Khatma Grid ══════════════════════════════════════════ */
.khatma-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}
.khatma-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 22px;
  transition: box-shadow .25s, transform .25s;
  position: relative;
  overflow: hidden;
}
.khatma-card::before {
  content: '';
  position: absolute; top: 0; right: 0;
  width: 80px; height: 80px;
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z' fill='none' stroke='%23064e3b' stroke-width='.6' opacity='.15'/%3E%3C/svg%3E");
  pointer-events: none;
}
.khatma-card:hover {
  box-shadow: 0 12px 40px rgba(6,78,59,.10);
  transform: translateY(-3px);
}
.khatma-card-header {
  display: flex; align-items: center;
  justify-content: space-between; margin-bottom: 14px;
}
.khatma-type-badge {
  font-size: 11.5px; font-weight: 700;
  padding: 4px 12px; border-radius: 100px;
}
.khatma-type-ramadan {
  background: #fdf4ff; color: #7e22ce;
  border: 1px solid #e9d5ff;
}
.khatma-type-weekly {
  background: #eff6ff; color: #1d4ed8;
  border: 1px solid #bfdbfe;
}
.khatma-type-family {
  background: #ecfdf5; color: #065f46;
  border: 1px solid #a7f3d0;
}
.khatma-type-individual {
  background: #fffbeb; color: #92400e;
  border: 1px solid #fde68a;
}
.khatma-status-dot {
  width: 8px; height: 8px; border-radius: 50%;
}
.khatma-status-dot.active {
  background: #10b981;
  box-shadow: 0 0 0 3px rgba(16,185,129,.2);
  animation: pulse-dot 2s infinite;
}
@keyframes pulse-dot {
  0%,100% { box-shadow: 0 0 0 3px rgba(16,185,129,.2); }
  50%      { box-shadow: 0 0 0 6px rgba(16,185,129,.05); }
}
.khatma-card-title {
  font-family: 'Amiri', serif;
  font-size: 1.15rem; font-weight: 700;
  color: var(--text); margin-bottom: 6px;
}
.khatma-card-meta {
  display: flex; align-items: center; gap: 5px;
  font-size: 12px; color: var(--text-m); margin-bottom: 16px;
}
.khatma-progress-wrap { margin-bottom: 16px; }
.khatma-progress-top {
  display: flex; justify-content: space-between;
  margin-bottom: 6px;
}
.khatma-progress-label { font-size: 12px; color: var(--text-m); }
.khatma-progress-pct {
  font-size: 12px; font-weight: 700; color: #059669;
}
.khatma-progress-bar {
  height: 6px; background: #e8f5ef;
  border-radius: 100px; overflow: hidden;
  margin-bottom: 5px;
}
.khatma-progress-fill {
  height: 100%; border-radius: 100px;
  background: linear-gradient(to left, #059669, #34d399);
  transition: width .6s ease;
}
.khatma-progress-sub {
  font-size: 11px; color: var(--text-m);
}

/* شبكة الأجزاء المصغّرة */
.juz-mini-grid {
  display: grid; grid-template-columns: repeat(15, 1fr);
  gap: 3px; margin-bottom: 16px;
}
.juz-mini {
  aspect-ratio: 1; border-radius: 3px;
  transition: transform .15s;
  cursor: pointer;
}
.juz-mini:hover { transform: scale(1.3); }
.juz-free    { background: #e2e8f0; }
.juz-reading { background: #fcd34d; }
.juz-done    { background: #34d399; }

.khatma-card-footer {
  display: flex; align-items: center;
  justify-content: space-between;
  padding-top: 14px;
  border-top: 1px solid var(--border);
}
.khatma-date {
  display: flex; align-items: center; gap: 5px;
  font-size: 11.5px; color: var(--text-m);
}
.khatma-view-btn {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12.5px; font-weight: 700;
  color: #059669; text-decoration: none;
  padding: 6px 14px; border-radius: 8px;
  background: #ecfdf5; border: 1px solid #a7f3d0;
  transition: all .18s;
}
.khatma-view-btn:hover {
  background: #d1fae5; transform: translateX(-2px);
}

/* بطاقة الإضافة */
.khatma-add-card {
  border: 2px dashed var(--border);
  border-radius: 20px; padding: 40px 22px;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 10px;
  text-decoration: none; color: var(--text-m);
  transition: all .25s; text-align: center;
  background: transparent;
}
.khatma-add-card:hover {
  border-color: #059669; background: #ecfdf5;
  color: #059669; transform: translateY(-3px);
}
.khatma-add-icon {
  width: 52px; height: 52px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 24px; font-weight: 300;
  background: var(--bg); border: 1.5px solid var(--border);
  transition: all .25s;
}
.khatma-add-card:hover .khatma-add-icon {
  background: #d1fae5; border-color: #6ee7b7; color: #059669;
}
.khatma-add-label {
  font-size: 14px; font-weight: 700;
}
.khatma-add-sub { font-size: 12px; }

/* Empty State */
.empty-state {
  text-align: center; padding: 64px 20px;
}
.empty-icon { font-size: 52px; display: block; margin-bottom: 16px; }
.empty-title {
  font-family: 'Amiri', serif;
  font-size: 1.4rem; font-weight: 700;
  color: var(--text); margin-bottom: 8px;
}
.empty-sub { font-size: 14px; color: var(--text-m); }
</style>
@endpush