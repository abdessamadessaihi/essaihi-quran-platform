@extends('layouts.app')
@section('title', 'عائلة ' . $family->name)

@push('styles')
<style>
/* ═══ FAMILY SHOW VARIABLES ════════════════════════════════ */
:root {
  --fam-radius: 14px;
  --fam-green: #059669;
  --fam-green-deep: #064e3b;
  --fam-gold: #d97706;
}

/* ═══ BACK BUTTON ═══════════════════════════════════════════ */
.fam-back {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: 13px; font-weight: 600; color: var(--text-m);
  text-decoration: none; margin-bottom: 18px;
  padding: 7px 13px; border-radius: 9px;
  background: var(--card); border: 1px solid var(--border);
  transition: color .18s, background .18s;
}
.fam-back:hover { color: var(--text); background: var(--border); }

/* ═══ BANNER ════════════════════════════════════════════════ */
.fam-banner {
  border-radius: 18px;
  background: linear-gradient(140deg, #022c22 0%, #064e3b 60%, #0a6647 100%);
  padding: 24px 22px;
  margin-bottom: 20px;
  position: relative; overflow: hidden;
}
.fam-banner::before {
  content: '';
  position: absolute; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='none' stroke='%23fff' stroke-width='.8'/%3E%3C/svg%3E");
  background-size: 60px; opacity: .04; pointer-events: none;
}
.fam-banner-inner {
  position: relative; z-index: 1;
  display: flex; align-items: center;
  justify-content: space-between; gap: 16px; flex-wrap: wrap;
}
.fam-banner-left { display: flex; align-items: center; gap: 14px; }
.fam-banner-icon {
  width: 56px; height: 56px; border-radius: 16px;
  background: rgba(255,255,255,.1);
  border: 1.5px solid rgba(255,255,255,.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 26px; flex-shrink: 0;
}
.fam-banner-name {
  font-family: 'Amiri', serif;
  font-size: 1.6rem; font-weight: 700; color: #fff; line-height: 1.2;
  margin-bottom: 4px;
}
.fam-banner-creator {
  font-size: 12px; color: rgba(167,243,208,.8);
}
.fam-banner-badges { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.fam-status-badge {
  font-size: 11px; font-weight: 700;
  padding: 4px 12px; border-radius: 100px;
}
.fam-status-badge.fam-status-active  { background: rgba(16,185,129,.25); color: #6ee7b7; border: 1px solid rgba(16,185,129,.3); }
.fam-status-badge.fam-status-stopped { background: rgba(239,68,68,.25);  color: #fca5a5; border: 1px solid rgba(239,68,68,.3); }
.fam-banner-date {
  font-size: 11px; color: rgba(255,255,255,.45);
  background: rgba(255,255,255,.07);
  padding: 4px 10px; border-radius: 8px;
}
.fam-banner-actions { display: flex; align-items: center; gap: 8px; }
.fam-action-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 6px;
  padding: 9px 16px; border-radius: 10px;
  font-size: 12px; font-weight: 700; cursor: pointer;
  font-family: 'Tajawal', sans-serif; text-decoration: none;
  border: none; transition: transform .18s;
}
.fam-action-btn:hover { transform: translateY(-1px); }
.fam-btn-notif {
  background: linear-gradient(135deg, #d97706, #b45309);
  color: #fff;
}
.fam-btn-toggle-off {
  background: #fef2f2; border: 1.5px solid #fecaca; color: #991b1b;
}
.fam-btn-toggle-on {
  background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #065f46;
}

/* ═══ STAT CARDS ════════════════════════════════════════════ */
.fam-stats {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px; margin-bottom: 20px;
}
@media(min-width: 640px) {
  .fam-stats { grid-template-columns: repeat(4, 1fr); }
}
.fam-stat {
  border-radius: var(--fam-radius);
  padding: 14px 12px;
  text-align: center;
  transition: transform .18s;
}
.fam-stat:hover { transform: translateY(-2px); }
.fam-stat-icon { font-size: 20px; margin-bottom: 6px; }
.fam-stat-val {
  font-family: 'Amiri', serif;
  font-size: 24px; font-weight: 700; line-height: 1;
  margin-bottom: 3px;
}
.fam-stat-label { font-size: 11px; opacity: .75; }

/* ═══ GRID LAYOUT ═══════════════════════════════════════════ */
.fam-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
}
@media(min-width: 1024px) {
  .fam-grid { grid-template-columns: minmax(0, 2fr) 300px; }
}

/* ═══ SECTION CARD ══════════════════════════════════════════ */
.fam-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--fam-radius);
  overflow: hidden;
  margin-bottom: 0;
}
.fam-card-head {
  padding: 12px 16px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center;
  justify-content: space-between; gap: 10px;
}
.fam-card-title {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; font-weight: 700; color: var(--text);
}
.fam-card-badge {
  font-size: 10px; font-weight: 700;
  padding: 2px 9px; border-radius: 100px;
  background: var(--bg); color: var(--text-m);
  border: 1px solid var(--border);
}

/* ═══ MEMBERS TABLE ═════════════════════════════════════════ */
.fam-table { width: 100%; border-collapse: collapse; }
.fam-table th {
  font-size: 10px; font-weight: 700; color: var(--text-m);
  text-align: right; padding: 8px 16px;
  background: var(--bg); border-bottom: 1px solid var(--border);
  text-transform: uppercase; letter-spacing: .5px;
}
.fam-table td {
  padding: 11px 16px;
  font-size: 12.5px; color: var(--text);
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}
.fam-table tr:last-child td { border-bottom: none; }
.fam-table tr:hover td { background: var(--bg); }
.fam-member-avatar {
  width: 32px; height: 32px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 700; color: #fff;
  background: linear-gradient(135deg, #064e3b, #0d6b52);
  flex-shrink: 0;
}
.fam-member-name-wrap {
  display: flex; align-items: center; gap: 9px;
}
.fam-member-name { font-weight: 600; }
.fam-member-email { font-size: 11px; color: var(--text-m); margin-top: 1px; }
.fam-role-badge {
  font-size: 10px; font-weight: 700;
  padding: 2px 8px; border-radius: 100px;
}
.fam-role-admin  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.fam-role-member { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.fam-joined { font-size: 11px; color: var(--text-m); }

/* ═══ KHATMA LIST ═══════════════════════════════════════════ */
.fam-khatma-item {
  padding: 12px 16px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center;
  justify-content: space-between; gap: 12px;
}
.fam-khatma-item:last-child { border-bottom: none; }
.fam-khatma-name {
  font-size: 13px; font-weight: 600; color: var(--text);
  margin-bottom: 2px;
}
.fam-khatma-meta { font-size: 11px; color: var(--text-m); }
.fam-khatma-status {
  font-size: 10px; font-weight: 700;
  padding: 3px 9px; border-radius: 100px;
  white-space: nowrap;
}
.fam-khatma-active   { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.fam-khatma-done     { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.fam-khatma-paused   { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

/* ═══ SIDEBAR INFO ══════════════════════════════════════════ */
.fam-info-row {
  display: flex; align-items: flex-start;
  justify-content: space-between;
  padding: 10px 16px;
  border-bottom: 1px solid var(--border);
  gap: 10px;
}
.fam-info-row:last-child { border-bottom: none; }
.fam-info-label { font-size: 11px; color: var(--text-m); }
.fam-info-val   { font-size: 12.5px; font-weight: 600; color: var(--text); text-align: left; }

/* ═══ EMPTY STATE ═══════════════════════════════════════════ */
.fam-empty {
  padding: 32px 16px; text-align: center;
}
.fam-empty p { font-size: 12px; color: var(--text-m); margin-top: 8px; }

/* ═══ MODAL ═════════════════════════════════════════════════ */
.fam-modal-overlay {
  display: none; position: fixed; inset: 0; z-index: 500;
  background: rgba(0,0,0,.45); backdrop-filter: blur(4px);
  align-items: center; justify-content: center;
}
.fam-modal {
  background: var(--card); border: 1px solid var(--border);
  border-radius: 22px; padding: 26px;
  max-width: 420px; width: 90%;
  box-shadow: 0 24px 64px rgba(0,0,0,.18);
}
.fam-modal h3 {
  font-family: 'Amiri', serif; font-size: 1.2rem;
  font-weight: 700; color: var(--text); margin-bottom: 4px;
}
.fam-modal p { font-size: 12.5px; color: var(--text-m); margin-bottom: 16px; }
.fam-modal textarea {
  width: 100%; padding: 11px;
  border: 1.5px solid var(--border);
  border-radius: 10px; resize: none;
  font-family: 'Tajawal', sans-serif; font-size: 13.5px;
  color: var(--text); background: var(--bg);
  outline: none; margin-bottom: 14px;
}
.fam-modal-btns { display: flex; gap: 8px; }
.fam-modal-cancel {
  flex: 1; padding: 11px; border-radius: 10px;
  background: var(--bg); border: 1.5px solid var(--border);
  font-family: 'Tajawal', sans-serif; font-size: 13.5px;
  font-weight: 600; color: var(--text-m); cursor: pointer;
}
.fam-modal-send {
  flex: 2; padding: 11px; border-radius: 10px;
  background: linear-gradient(135deg, #d97706, #b45309);
  border: none; color: #fff;
  font-family: 'Tajawal', sans-serif; font-size: 13.5px;
  font-weight: 700; cursor: pointer;
}

/* ═══ 📱 إصلاح شاشات الهواتف المحموله الجذري ══════════════ */
@media (max-width: 768px) {
  /* البانر العلوي */
  .fam-banner-inner {
    flex-direction: column !important;
    align-items: stretch !important;
    text-align: center !important;
  }
  .fam-banner-left {
    flex-direction: column !important;
    gap: 10px !important;
  }
  .fam-banner-badges {
    justify-content: center !important;
  }
  .fam-banner-actions {
    flex-direction: column !important;
    width: 100% !important;
    gap: 10px !important;
  }
  .fam-banner-actions form, 
  .fam-banner-actions .fam-action-btn {
    width: 100% !important;
    display: flex !important;
    justify-content: center !important;
  }

  /* الأعمدة الأساسية */
  .fam-grid {
    display: flex !important;
    flex-direction: column !important;
    gap: 24px !important;
  }
  
  .fam-grid > div {
    width: 100% !important;
  }

  /* الجداول */
  .fam-table th, .fam-table td {
    padding: 10px 8px !important;
    font-size: 11.5px !important;
  }
  .fam-member-name-wrap {
    min-width: 130px !important;
  }
}
</style>
@endpush

@section('content')

{{-- رجوع --}}
<a href="{{ route('admin.families.index') }}" class="fam-back">
  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
    <path d="M19 12H5M12 19l-7-7 7-7"/>
  </svg>
  العودة إلى إدارة العائلات
</a>

{{-- ═══ BANNER ════════════════════════════════════════════════ --}}
<div class="fam-banner">
  <div class="fam-banner-inner">

    <div class="fam-banner-left">
      <div class="fam-banner-icon">👨‍👩‍👧</div>
      <div>
        <p class="fam-banner-name">{{ $family->name }}</p>
        <p class="fam-banner-creator">
          أسّسها {{ $family->creator->name }}
          · {{ $family->created_at->diffForHumans() }}
        </p>
        <div class="fam-banner-badges" style="margin-top:7px">
          <span class="fam-status-badge {{ $family->is_active ? 'fam-status-active' : 'fam-status-stopped' }}">
            {{ $family->is_active ? '● نشطة' : '● موقوفة' }}
          </span>
          <span class="fam-banner-date">
            منذ {{ $family->created_at->format('Y/m/d') }}
          </span>
        </div>
      </div>
    </div>

    <div class="fam-banner-actions">
      {{-- إشعار --}}
      <button onclick="document.getElementById('notifModal').style.display='flex'"
              class="fam-action-btn fam-btn-notif">
        📨 إرسال إشعار
      </button>
      {{-- تفعيل / إيقاف --}}
      <form method="POST" action="{{ route('admin.families.update', $family) }}" style="margin:0; width:100%;">
        @csrf @method('PATCH')
        <input type="hidden" name="is_active" value="{{ $family->is_active ? '0' : '1' }}">
        <button type="submit"
                class="fam-action-btn {{ $family->is_active ? 'fam-btn-toggle-off' : 'fam-btn-toggle-on' }}">
          {{ $family->is_active ? '⛔ إيقاف' : '✅ تفعيل' }}
        </button>
      </form>
    </div>

  </div>
</div>

{{-- ═══ STAT CARDS ════════════════════════════════════════════ --}}
<div class="fam-stats">

  <div class="fam-stat" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0">
    <div class="fam-stat-icon">👥</div>
    <div class="fam-stat-val" style="color:#059669">
      {{ $family->memberships->where('status','active')->count() }}
    </div>
    <div class="fam-stat-label" style="color:#065f46">عضو نشط</div>
  </div>

  <div class="fam-stat" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe">
    <div class="fam-stat-icon">📚</div>
    <div class="fam-stat-val" style="color:#2563eb">
      {{ $family->khatmas->count() }}
    </div>
    <div class="fam-stat-label" style="color:#1e3a8a">ختمة إجمالية</div>
  </div>

  <div class="fam-stat" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fde68a">
    <div class="fam-stat-icon">✅</div>
    <div class="fam-stat-val" style="color:#d97706">
      {{ $family->khatmas->where('status','active')->count() }}
    </div>
    <div class="fam-stat-label" style="color:#78350f">ختمة نشطة</div>
  </div>

  <div class="fam-stat" style="background:linear-gradient(135deg,#fdf4ff,#fae8ff);border:1px solid #e9d5ff">
    <div class="fam-stat-icon">🏆</div>
    <div class="fam-stat-val" style="color:#9333ea">
      {{ $family->khatmas->where('status','completed')->count() }}
    </div>
    <div class="fam-stat-label" style="color:#581c87">ختمة مكتملة</div>
  </div>

</div>

{{-- ═══ MAIN GRID ══════════════════════════════════════════════ --}}
<div class="fam-grid">

  {{-- ── عمود رئيسي ── --}}
  <div style="display:flex;flex-direction:column;gap:16px">

    {{-- قائمة الأعضاء --}}
    <div class="fam-card">
      <div class="fam-card-head">
        <div class="fam-card-title">
          👥 الأعضاء
        </div>
        <span class="fam-card-badge">
          {{ $family->memberships->count() }} عضو
        </span>
      </div>

      @if($family->memberships->isEmpty())
        <div class="fam-empty">
          <span style="font-size:32px">👤</span>
          <p>لا يوجد أعضاء مسجّلون في هذه العائلة</p>
        </div>
      @else
      <div style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
        <table class="fam-table" style="min-width:500px;">
          <thead>
            <tr>
              <th>العضو</th>
              <th>الدور</th>
              <th>الحالة</th>
              <th>تاريخ الانضمام</th>
            </tr>
          </thead>
          <tbody>
            @foreach($family->memberships as $membership)
            <tr>
              <td>
                <div class="fam-member-name-wrap">
                  <div class="fam-member-avatar">
                    {{ mb_substr($membership->user->name, 0, 1) }}
                  </div>
                  <div>
                    <div class="fam-member-name">{{ $membership->user->name }}</div>
                    <div class="fam-member-email">{{ $membership->user->email }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span class="fam-role-badge {{ $membership->role === 'admin' ? 'fam-role-admin' : 'fam-role-member' }}">
                  {{ $membership->role === 'admin' ? '👑 مسؤول' : 'عضو' }}
                </span>
              </td>
              <td>
                <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:100px;
                  {{ $membership->status === 'active'
                     ? 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0'
                     : 'background:#fef2f2;color:#991b1b;border:1px solid #fecaca' }}">
                  {{ $membership->status === 'active' ? 'نشط' : 'موقوف' }}
                </span>
              </td>
              <td class="fam-joined">
                {{ $membership->created_at->format('Y/m/d') }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @endif
    </div>

    {{-- الختمات --}}
    <div class="fam-card">
      <div class="fam-card-head">
        <div class="fam-card-title">
          📚 الختمات القرآنية
        </div>
        <span class="fam-card-badge">
          {{ $family->khatmas->count() }} ختمة
        </span>
      </div>

      @if($family->khatmas->isEmpty())
        <div class="fam-empty">
          <span style="font-size:32px">📖</span>
          <p>لا توجد ختمات مسجّلة لهذه العائلة</p>
        </div>
      @else
        @foreach($family->khatmas as $khatma)
        <div class="fam-khatma-item">
          <div style="flex:1;min-width:0">
            <p class="fam-khatma-name">{{ $khatma->title ?? 'ختمة بدون عنوان' }}</p>
            <p class="fam-khatma-meta">
              بدأت {{ $khatma->created_at->format('Y/m/d') }}
              @if($khatma->completed_at)
              · اكتملت {{ $khatma->completed_at->format('Y/m/d') }}
              @endif
            </p>
          </div>
          <span class="fam-khatma-status
            @if($khatma->status === 'active') fam-khatma-active
            @elseif($khatma->status === 'completed') fam-khatma-done
            @else fam-khatma-paused
            @endif">
            @if($khatma->status === 'active') نشطة
            @elseif($khatma->status === 'completed') مكتملة
            @else متوقفة
            @endif
          </span>
        </div>
        @endforeach
      @endif
    </div>

  </div>

  {{-- ── العمود الجانبي ── --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    {{-- معلومات العائلة --}}
    <div class="fam-card">
      <div class="fam-card-head">
        <div class="fam-card-title">ℹ️ معلومات العائلة</div>
      </div>
      <div>
        <div class="fam-info-row">
          <span class="fam-info-label">الاسم</span>
          <span class="fam-info-val">{{ $family->name }}</span>
        </div>
        <div class="fam-info-row">
          <span class="fam-info-label">المؤسّس</span>
          <span class="fam-info-val">{{ $family->creator->name }}</span>
        </div>
        <div class="fam-info-row">
          <span class="fam-info-label">البريد</span>
          <span class="fam-info-val" style="word-break: break-all;">{{ $family->creator->email }}</span>
        </div>
        <div class="fam-info-row">
          <span class="fam-info-label">تاريخ الإنشاء</span>
          <span class="fam-info-val">{{ $family->created_at->format('Y/m/d') }}</span>
        </div>
        <div class="fam-info-row">
          <span class="fam-info-label">آخر تحديث</span>
          <span class="fam-info-val">{{ $family->updated_at->diffForHumans() }}</span>
        </div>
        <div class="fam-info-row">
          <span class="fam-info-label">الحالة</span>
          <span class="fam-info-val">
            <span class="fam-status-badge {{ $family->is_active ? 'fam-status-active' : 'fam-status-stopped' }}"
                  style="font-size:10px">
              {{ $family->is_active ? 'نشطة' : 'موقوفة' }}
            </span>
          </span>
        </div>
      </div>
    </div>

    {{-- إحصائيات الأعضاء --}}
    <div class="fam-card">
      <div class="fam-card-head">
        <div class="fam-card-title">📊 توزيع الأعضاء</div>
      </div>
      <div style="padding:16px;display:flex;flex-direction:column;gap:10px">

        @php
          $activeCount  = $family->memberships->where('status','active')->count();
          $totalCount   = $family->memberships->count();
          $adminCount   = $family->memberships->where('role','admin')->count();
          $pct = $totalCount > 0 ? round(($activeCount/$totalCount)*100) : 0;
        @endphp

        <div>
          <div style="display:flex;justify-content:space-between;
                      font-size:11px;color:var(--text-m);margin-bottom:5px">
            <span>نسبة النشاط</span>
            <span style="font-weight:700;color:var(--fam-green)">{{ $pct }}٪</span>
          </div>
          <div style="height:6px;border-radius:100px;background:var(--border);overflow:hidden">
            <div style="height:100%;border-radius:100px;
                        background:linear-gradient(90deg,#059669,#34d399);
                        width:{{ $pct }}%;transition:width .6s"></div>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:4px">
          <div style="text-align:center;padding:10px;border-radius:10px;
                      background:#ecfdf5;border:1px solid #a7f3d0">
            <p style="font-family:'Amiri',serif;font-size:1.4rem;
                      font-weight:700;color:#059669;line-height:1">{{ $activeCount }}</p>
            <p style="font-size:10px;color:#065f46;margin-top:2px">نشط</p>
          </div>
          <div style="text-align:center;padding:10px;border-radius:10px;
                      background:#fffbeb;border:1px solid #fde68a">
            <p style="font-family:'Amiri',serif;font-size:1.4rem;
                      font-weight:700;color:#d97706;line-height:1">{{ $adminCount }}</p>
            <p style="font-size:10px;color:#92400e;margin-top:2px">مسؤول</p>
          </div>
        </div>

      </div>
    </div>

    {{-- إجراءات سريعة --}}
    <div class="fam-card">
      <div class="fam-card-head">
        <div class="fam-card-title">⚡ إجراءات</div>
      </div>
      <div style="padding:12px;display:flex;flex-direction:column;gap:8px">

        <button onclick="document.getElementById('notifModal').style.display='flex'"
                style="width:100%;padding:10px;border-radius:10px;
                       background:linear-gradient(135deg,#d97706,#b45309);
                       border:none;color:#fff;font-family:'Tajawal',sans-serif;
                       font-size:13px;font-weight:700;cursor:pointer;
                       display:flex;align-items:center;justify-content:center;gap:7px">
          📨 إرسال إشعار للأعضاء
        </button>

        <form method="POST" action="{{ route('admin.families.update', $family) }}" style="margin:0">
          @csrf @method('PATCH')
          <input type="hidden" name="is_active" value="{{ $family->is_active ? '0' : '1' }}">
          <button type="submit"
                  style="width:100%;padding:10px;border-radius:10px;cursor:pointer;
                         font-family:'Tajawal',sans-serif;font-size:13px;font-weight:700;
                         display:flex;align-items:center;justify-content:center;gap:7px;
                         {{ $family->is_active
                            ? 'background:#fef2f2;border:1.5px solid #fecaca;color:#991b1b'
                            : 'background:#ecfdf5;border:1.5px solid #a7f3d0;color:#065f46' }}">
            {{ $family->is_active ? '⛔ إيقاف العائلة' : '✅ تفعيل العائلة' }}
          </button>
        </form>

        <a href="{{ route('admin.families.index') }}"
           style="width:100%;padding:10px;border-radius:10px;
                  background:var(--bg);border:1.5px solid var(--border);
                  color:var(--text-m);font-family:'Tajawal',sans-serif;
                  font-size:13px;font-weight:600;text-decoration:none;
                  display:flex;align-items:center;justify-content:center;gap:7px">
          ← العودة للقائمة
        </a>

      </div>
    </div>

  </div>
</div>

{{-- ═══ MODAL إشعار ════════════════════════════════════════════ --}}
<div class="fam-modal-overlay" id="notifModal">
  <div class="fam-modal">
    <h3>📨 إرسال إشعار للعائلة</h3>
    <p>سيصل الإشعار لجميع أعضاء عائلة <strong>{{ $family->name }}</strong></p>
    <form method="POST" action="{{ route('admin.families.notify', $family) }}">
      @csrf
      <textarea name="message" rows="4"
                placeholder="اكتب رسالتك هنا..."
                required></textarea>
      <div class="fam-modal-btns">
        <button type="button" class="fam-modal-cancel"
                onclick="document.getElementById('notifModal').style.display='none'">
          إلغاء
        </button>
        <button type="submit" class="fam-modal-send">
          📨 إرسال
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('notifModal')?.addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
</script>
@endpush