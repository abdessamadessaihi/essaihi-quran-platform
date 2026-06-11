@extends('layouts.app')
@section('title', 'العائلات')

@section('content')

{{-- ══ رأس الصفحة ══ --}}
<div class="fam-header">
  <div class="fam-ornament">
    <div class="fam-ornament-line"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة"
         style="width:64px;height:64px;object-fit:contain;opacity:.85"/>
    <div class="fam-ornament-line"></div>
  </div>
  <div class="fam-header-content">
    <div>
      <h1 class="fam-title">العائلات القرآنية</h1>
      <p class="fam-subtitle">
        انضم إلى عائلتك أو أنشئ دائرة قرآنية جديدة وابدأ رحلتكم معاً
      </p>
    </div>
    <a href="{{ route('families.create') }}" class="fam-btn-primary">
      <svg width="15" height="15" fill="none" stroke="currentColor"
           stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      إنشاء عائلة
    </a>
  </div>
</div>

{{-- ══ طلب الانضمام المعلق ══ --}}
@if($pendingRequest)
<div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);
            border:1px solid #fde68a;border-radius:16px;
            padding:18px 22px;margin-bottom:24px;
            display:flex;align-items:center;gap:14px">
  <span style="font-size:24px;flex-shrink:0">⏳</span>
  <div>
    <p style="font-size:14px;font-weight:700;color:#78350f;margin-bottom:3px">
      طلب انضمام قيد الانتظار
    </p>
    <p style="font-size:13px;color:#92400e">
      أرسلت طلبًا للانضمام إلى عائلة
      <strong>{{ $pendingRequest->family->name }}</strong>
      — بانتظار موافقة المسؤول
    </p>
  </div>
</div>
@endif

{{-- ══ عائلاتي ══ --}}
@if($myFamilies->isNotEmpty())
<div style="margin-bottom:32px">
  <div class="fam-section-header">
    <span class="fam-section-dot" style="background:#059669"></span>
    <h2 class="fam-section-title">عائلاتي ({{ $myFamilies->count() }})</h2>
  </div>
  <div class="fam-grid">
    @foreach($myFamilies as $family)
    @php
      $membership = $family->memberships->firstWhere('user_id', auth()->id());
      $isAdmin    = $membership?->role === 'admin';
    @endphp
    <div class="fam-card my-family">
      {{-- زخرفة الزاوية --}}
      <div class="fam-card-ornament"></div>

      {{-- رأس البطاقة --}}
      <div class="fam-card-header">
        <div class="fam-logo-wrap">
          @if($family->logo_url)
            <img src="{{ asset($family->logo_url) }}"
                 alt="{{ $family->name }}"
                 style="width:100%;height:100%;object-fit:cover;border-radius:14px">
          @else
            <span style="font-size:28px">👨‍👩‍👧</span>
          @endif
        </div>
        <div style="flex:1;min-width:0">
          <h3 class="fam-card-name">{{ $family->name }}</h3>
          <div style="display:flex;align-items:center;gap:8px;margin-top:4px;flex-wrap:wrap">
            @if($isAdmin)
            <span class="fam-role-badge admin">👑 مسؤول</span>
            @else
            <span class="fam-role-badge member">📖 عضو</span>
            @endif
            <span class="fam-status-badge active">
              <span class="fam-status-dot"></span>
              نشطة
            </span>
          </div>
        </div>
      </div>

      {{-- الوصف --}}
      @if($family->description)
      <p class="fam-card-desc">{{ Str::limit($family->description, 80) }}</p>
      @endif

      {{-- إحصائيات --}}
      <div class="fam-card-stats">
        @foreach([
          [$family->active_members_count, 'عضو نشط', '👥'],
          [$family->active_khatmas_count, 'ختمة نشطة', '📚'],
          [$family->completed_khatmas_count, 'ختمة مكتملة', '✅'],
        ] as [$val, $lbl, $icon])
        <div class="fam-stat-item">
          <span style="font-size:16px">{{ $icon }}</span>
          <p class="fam-stat-value">{{ $val }}</p>
          <p class="fam-stat-label">{{ $lbl }}</p>
        </div>
        @endforeach
      </div>

      {{-- تذييل --}}
      <div class="fam-card-footer">
        <span class="fam-joined-date">
          <svg width="12" height="12" fill="none" stroke="currentColor"
               stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <path d="M16 2v4M8 2v4M3 10h18"/>
          </svg>
          {{ $membership?->joined_at?->locale('ar')->isoFormat('D MMM YYYY') ?? 'المنشئ' }}
        </span>
        <a href="{{ route('families.show', $family) }}"
           class="fam-view-btn">
          دخول العائلة
          <svg width="13" height="13" fill="none" stroke="currentColor"
               stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

{{-- ══ عائلات متاحة للانضمام ══ --}}
@if($availableFamilies->isNotEmpty())
<div>
  <div class="fam-section-header">
    <span class="fam-section-dot" style="background:#3b82f6"></span>
    <h2 class="fam-section-title">عائلات متاحة للانضمام</h2>
  </div>
  <div class="fam-grid">
    @foreach($availableFamilies as $family)
    <div class="fam-card available">
      <div class="fam-card-ornament"></div>
      <div class="fam-card-header">
        <div class="fam-logo-wrap" style="opacity:.75">
          @if($family->logo_url)
            <img src="{{ asset($family->logo_url) }}"
                 alt="{{ $family->name }}"
                 style="width:100%;height:100%;object-fit:cover;border-radius:14px">
          @else
            <span style="font-size:28px">👨‍👩‍👧</span>
          @endif
        </div>
        <div style="flex:1;min-width:0">
          <h3 class="fam-card-name">{{ $family->name }}</h3>
          <p style="font-size:12px;color:var(--text-m);margin-top:3px">
            {{ $family->active_members_count }} عضو نشط
          </p>
        </div>
      </div>

      @if($family->description)
      <p class="fam-card-desc">{{ Str::limit($family->description, 80) }}</p>
      @endif

      <div class="fam-card-footer" style="margin-top:16px">
        <span style="font-size:12px;color:var(--text-m)">
          أُسّست {{ $family->created_at->locale('ar')->diffForHumans() }}
        </span>
        <form method="POST" action="{{ route('families.join', $family) }}">
          @csrf
          <button type="submit" class="fam-join-btn">
            طلب الانضمام
          </button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

{{-- حالة فارغة --}}
@if($myFamilies->isEmpty() && $availableFamilies->isEmpty())
<div class="fam-empty">
  <span style="font-size:56px;display:block;margin-bottom:16px">👨‍👩‍👧</span>
  <p style="font-family:'Amiri',serif;font-size:1.5rem;font-weight:700;
            color:var(--text);margin-bottom:10px">
    لا توجد عائلات بعد
  </p>
  <p style="font-size:13.5px;color:var(--text-m);margin-bottom:28px;line-height:1.8">
    كن أول من يؤسس عائلة قرآنية ويدعو أفراد عائلته للانضمام
  </p>
  <a href="{{ route('families.create') }}" class="fam-btn-primary">
    <svg width="15" height="15" fill="none" stroke="currentColor"
         stroke-width="2.5" viewBox="0 0 24 24">
      <path d="M12 5v14M5 12h14"/>
    </svg>
    إنشاء أول عائلة
  </a>
</div>
@endif

@endsection

@push('styles')
<style>
.fam-header { margin-bottom: 28px; }
.fam-ornament {
  display: flex; align-items: center; gap: 14px; margin-bottom: 16px;
}
.fam-ornament-line {
  flex: 1; height: 1px;
  background: linear-gradient(to left, transparent, #a7f3d0);
}
.fam-ornament-line:last-child {
  background: linear-gradient(to right, transparent, #a7f3d0);
}
.fam-header-content {
  display: flex; justify-content: space-between;
  align-items: flex-start; gap: 18px; flex-wrap: wrap;
}
.fam-title {
  font-family: 'Amiri', serif;
  font-size: 1.95rem; font-weight: 700;
  color: var(--text); margin-bottom: 6px;
}
.fam-subtitle { color: var(--text-m); font-size: 13px; line-height: 1.7; }

.fam-btn-primary {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 11px 22px; border-radius: 12px;
  background: linear-gradient(135deg, #0d6b52, #064e3b);
  color: #fff; font-size: 13.5px; font-weight: 700;
  text-decoration: none;
  box-shadow: 0 4px 16px rgba(13,107,82,.30);
  transition: transform .2s, box-shadow .2s; white-space: nowrap;
}
.fam-btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(13,107,82,.40);
}

.fam-section-header {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 16px;
}
.fam-section-dot {
  width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}
.fam-section-title {
  font-size: 15px; font-weight: 700; color: var(--text);
}

.fam-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 18px;
}

.fam-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 20px; padding: 22px;
  transition: box-shadow .25s, transform .25s;
  position: relative; overflow: hidden;
}
.fam-card:hover {
  box-shadow: 0 10px 36px rgba(6,78,59,.10);
  transform: translateY(-3px);
}
.fam-card.my-family { border-color: #a7f3d0; }
.fam-card.available { opacity: .92; }

.fam-card-ornament {
  position: absolute; top: 0; right: 0;
  width: 80px; height: 80px;
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z' fill='none' stroke='%23064e3b' stroke-width='.5' opacity='.12'/%3E%3C/svg%3E");
  pointer-events: none;
}

.fam-card-header {
  display: flex; align-items: flex-start;
  gap: 14px; margin-bottom: 12px;
}
.fam-logo-wrap {
  width: 56px; height: 56px; border-radius: 14px;
  flex-shrink: 0; overflow: hidden;
  display: flex; align-items: center; justify-content: center;
  background: linear-gradient(135deg,#ecfdf5,#d1fae5);
  border: 1.5px solid #a7f3d0;
}
.fam-card-name {
  font-family: 'Amiri', serif;
  font-size: 1.15rem; font-weight: 700;
  color: var(--text); line-height: 1.3;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.fam-role-badge {
  font-size: 11px; font-weight: 700;
  padding: 3px 10px; border-radius: 100px;
}
.fam-role-badge.admin  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.fam-role-badge.member { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }

.fam-status-badge {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 600;
  padding: 3px 10px; border-radius: 100px;
}
.fam-status-badge.active {
  background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;
}
.fam-status-dot {
  width: 6px; height: 6px; border-radius: 50%;
  background: #10b981; animation: pulse-dot 2s infinite;
}

.fam-card-desc {
  font-size: 13px; color: var(--text-m);
  line-height: 1.7; margin-bottom: 14px;
}

.fam-card-stats {
  display: grid; grid-template-columns: repeat(3,1fr);
  gap: 8px; margin-bottom: 16px;
}
.fam-stat-item {
  text-align: center; padding: 10px 6px;
  background: var(--bg); border-radius: 10px;
  border: 1px solid var(--border);
}
.fam-stat-value {
  font-family: 'Amiri', serif;
  font-size: 1.3rem; font-weight: 700;
  color: var(--text); line-height: 1;
}
.fam-stat-label { font-size: 10px; color: var(--text-m); margin-top: 2px; }

.fam-card-footer {
  display: flex; align-items: center;
  justify-content: space-between;
  padding-top: 14px; border-top: 1px solid var(--border);
}
.fam-joined-date {
  display: flex; align-items: center; gap: 5px;
  font-size: 11.5px; color: var(--text-m);
}
.fam-view-btn {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12.5px; font-weight: 700;
  color: #059669; text-decoration: none;
  padding: 6px 14px; border-radius: 8px;
  background: #ecfdf5; border: 1px solid #a7f3d0;
  transition: all .18s;
}
.fam-view-btn:hover { background: #d1fae5; transform: translateX(-2px); }

.fam-join-btn {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 12.5px; font-weight: 700;
  color: #fff; padding: 8px 16px; border-radius: 9px;
  background: linear-gradient(135deg,#0d6b52,#065f46);
  border: none; cursor: pointer;
  font-family: 'Tajawal', sans-serif;
  box-shadow: 0 3px 12px rgba(13,107,82,.25);
  transition: transform .18s;
}
.fam-join-btn:hover { transform: translateY(-1px); }

.fam-empty {
  text-align: center; padding: 72px 40px;
}

@media (max-width: 640px) {
  .fam-grid { grid-template-columns: 1fr; }
}
</style>
@endpush