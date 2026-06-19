@extends('layouts.app')
@section('title', 'الإشعارات')

@section('content')

{{-- ══ رأس الصفحة ══ --}}
<div class="page-header" style="margin-bottom:32px; text-align: center;">
  <div class="page-header-ornament" style="display: flex; align-items: center; justify-content: center; gap: 6px; margin-bottom: 14px;">
    <div class="ornament-line" style="width:40px; height:1px; background:linear-gradient(to right,transparent,#a7f3d0);"></div>
    <div class="ornament-line" style="width:40px; height:1px; background:linear-gradient(to right,transparent,#a7f3d0);"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة" style="width:56px;height:56px;object-fit:contain;opacity:.85"/>  
    <div class="ornament-line" style="width:40px; height:1px; background:linear-gradient(to left,transparent,#a7f3d0);"></div>
    <div class="ornament-line" style="width:40px; height:1px; background:linear-gradient(to left,transparent,#a7f3d0);"></div>
  </div>
  <h1 class="page-title" style="font-family:'Amiri',serif; font-size:2rem; font-weight:700; color:var(--text); margin-bottom:8px;">الإشعارات</h1>
  <p class="page-subtitle" style="font-size:13.5px; color:var(--text-m); line-height:1.7;">ابقَ على اطلاع بكل ما يتعلق بحفظك وأورادك ومسيرتك القرآنية</p>
</div>

{{-- الشبكة الرئيسية الحاضنة - تم تصحيحها للتجاوب التلقائي عبر الـ CSS السفلي --}}
<div class="main-notification-container">

  {{-- ── العمود الرئيسي ── --}}
  <div class="notifications-main-column">

    {{-- شريط الإجراءات --}}
    <div class="notif-toolbar">
      <div style="display:flex;align-items:center;gap:10px">
        <span class="notif-counter-badge">{{ $unreadCount }}</span>
        <span style="font-size:13.5px;font-weight:700;color:var(--text)">
          إشعار غير مقروء
        </span>
      </div>

      @if($unreadCount > 0)
      <form method="POST" action="{{ route('notifications.read-all') }}" style="margin:0;">
        @csrf
        <button type="submit" class="btn-mark-all">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
          تعيين الكل كمقروء
        </button>
      </form>
      @endif
    </div>

    {{-- ── قسم غير المقروءة ── --}}
    @if($unread->isNotEmpty())
    <div class="notif-section">
      <div class="notif-section-label">
        <span class="notif-section-dot unread-dot"></span>
        غير مقروءة ({{ $unread->count() }})
      </div>

      <div class="notif-list">
        @foreach($unread as $notif)
        <div class="notif-item unread" id="notif-{{ $notif->id }}">
          <div class="notif-icon-wrap {{ notifIconClass($notif->type) }}">
            <span>{{ notifIcon($notif->type) }}</span>
          </div>

          <div class="notif-body">
            <p class="notif-title">{{ notifTitle($notif->type, $notif->data) }}</p>
            <p class="notif-desc">{{ notifDesc($notif->type, $notif->data) }}</p>
            <div class="notif-meta">
              <span class="notif-channel {{ $notif->channel }}">
                {{ notifChannel($notif->channel) }}
              </span>
              <span style="color:var(--text-m);font-size:11px">
                {{ $notif->sent_at ? $notif->sent_at->diffForHumans() : $notif->created_at->diffForHumans() }}
              </span>
            </div>
          </div>

          <div class="notif-actions">
            <form method="POST" action="{{ route('notifications.read', $notif->id) }}" style="margin:0;">
              @csrf
              <button type="submit" class="notif-action-btn read-btn" title="تعيين كمقروء">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M20 6L9 17l-5-5"/>
                </svg>
              </button>
            </form>
            <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" style="margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit" class="notif-action-btn delete-btn" title="حذف">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
              </button>
            </form>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- ── قسم المقروءة ── --}}
    @if($read->isNotEmpty())
    <div class="notif-section">
      <div class="notif-section-label">
        <span class="notif-section-dot read-dot"></span>
        مقروءة ({{ $read->count() }})
      </div>

      <div class="notif-list">
        @foreach($read as $notif)
        <div class="notif-item read" id="notif-{{ $notif->id }}">
          <div class="notif-icon-wrap {{ notifIconClass($notif->type) }} muted">
            <span>{{ notifIcon($notif->type) }}</span>
          </div>

          <div class="notif-body">
            <p class="notif-title muted">{{ notifTitle($notif->type, $notif->data) }}</p>
            <p class="notif-desc">{{ notifDesc($notif->type, $notif->data) }}</p>
            <div class="notif-meta">
              <span class="notif-channel {{ $notif->channel }}">
                {{ notifChannel($notif->channel) }}
              </span>
              <span style="color:var(--text-m);font-size:11px">
                {{ $notif->read_at ? 'قُرئ ' . $notif->read_at->diffForHumans() : $notif->created_at->diffForHumans() }}
              </span>
            </div>
          </div>

          <div class="notif-actions">
            <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" style="margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit" class="notif-action-btn delete-btn" title="حذف">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
              </button>
            </form>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- الحالة الفارغة --}}
    @if($notifications->isEmpty())
    <div class="card" style="padding:60px 40px;text-align:center">
      <div class="empty-bell">
        <img src="{{ asset('images/notification.png') }}" style="width:56px;height:56px;object-fit:contain;opacity:.85"/>  
      </div>
      <h3 style="font-size:16px;font-weight:700;color:var(--text);margin-bottom:8px">لا توجد إشعارات بعد</h3>
      <p style="font-size:13px;color:var(--text-m);line-height:1.7;max-width:320px;margin:0 auto 20px">
        ستظهر هنا إشعارات الورد اليومي والحفظ والمراجعات والتحديثات المجتمعية.
      </p>
      <a href="{{ route('ward.index') }}" style="display:inline-flex;align-items:center;gap:8px; padding:10px 24px;border-radius:12px;text-decoration:none; background:linear-gradient(135deg,#0d6b52,#065f46); color:#fff;font-size:13px;font-weight:700; box-shadow:0 4px 16px rgba(13,107,82,.3)">
          ابدأ ورد اليوم
      </a>
    </div>
    @endif

  </div>

  {{-- ── الشريط الجانبي ── --}}
  <div class="notifications-sidebar">

    {{-- ملخص الإشعارات --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title" style="display:flex; align-items:center; gap:8px;">
          <span style="font-size:17px">📊</span>
          <span style="font-weight:700; color:var(--text)">ملخص الإشعارات</span>
        </div>
      </div>
      <div style="padding:18px;display:flex;flex-direction:column;gap:14px">
        @php
          $totalCount  = $notifications->count();
          $readCount   = $read->count();
          $readPct     = $totalCount > 0 ? round($readCount / $totalCount * 100) : 0;
        @endphp

        {{-- شريط التقدم --}}
        <div>
          <div style="display:flex;justify-content:space-between; font-size:11.5px;color:var(--text-m);margin-bottom:6px">
            <span>نسبة المقروءة</span>
            <span style="font-weight:700;color:#059669">{{ $readPct }}٪</span>
          </div>
          <div style="height:7px;background:var(--bg);border-radius:100px;overflow:hidden">
            <div style="height:100%;width:{{ $readPct }}%; background:linear-gradient(90deg,#059669,#10b981); border-radius:100px;transition:width .5s"></div>
          </div>
        </div>

        {{-- بطاقات الأرقام المتجاوبة --}}
        <div class="summary-stats-grid">
          @foreach([
            ['الكل',       $totalCount, '#fffbeb', '#fde68a', '#d97706'],
            ['غير مقروء',  $unreadCount, '#fef2f2', '#fecaca', '#dc2626'],
            ['مقروء',      $readCount,  '#ecfdf5', '#a7f3d0', '#059669'],
            ['هذا الأسبوع', $notifications->filter(fn($n) => $n->created_at->isCurrentWeek())->count(), '#eff6ff', '#bfdbfe', '#2563eb'],
          ] as [$lbl,$val,$bg,$border,$color])
          <div style="padding:12px;border-radius:10px; background:{{ $bg }};border:1px solid {{ $border }}; text-align:center">
            <p style="font-family:'Amiri',serif;font-size:1.5rem; font-weight:700;color:{{ $color }};line-height:1; margin:0;">
              {{ $val }}
            </p>
            <p style="font-size:10.5px;color:var(--text-m); margin-top:4px; margin-bottom:0; font-weight:600">{{ $lbl }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
      
    {{-- روابط سريعة --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title" style="display:flex; align-items:center; gap:8px;">
          <div class="feature-icon-wrap">
            <img src="{{ asset('images/link.png') }}" alt="اختصارات" class="feature-icon" style="width:20px;height:20px;object-fit:contain"/>
          </div>
          <span style="font-weight:700; color:var(--text)">روابط سريعة</span>
        </div>
      </div>

      <div style="padding:14px;display:flex;flex-direction:column;gap:8px">
        @foreach([
          [route('ward.index'), 'images/wird.png', 'الورد اليومي'],
          [route('memorizations.index'), 'images/morajaa.png', 'صفحة الحفظ'],
          [route('revisions.index'), 'images/brain.png', 'المراجعة'],
          [route('leaderboard'), 'images/homeStatistics3.png', 'لوحة الشرف'],
        ] as [$url, $icon, $label])
        <a href="{{ $url }}" style="display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; background:var(--bg); border:1px solid var(--border); text-decoration:none; font-size:13px; color:var(--text); font-weight:600; transition:.2s;">
          <div class="feature-icon-wrap">
            <img src="{{ asset($icon) }}" alt="{{ $label }}" class="feature-icon" style="width:20px;height:20px;object-fit:contain"/>
          </div>
          <span>{{ $label }}</span>
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:auto;color:var(--text-m)">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
        @endforeach
      </div>
    </div>

  </div>
</div>

@endsection

@push('styles')
<style>
/* ══ هندسة وتجاوب حاويات الصفحة الشاملة ════════════════ */
.main-notification-container {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 24px;
}
.notifications-main-column {
  display: flex;
  flex-col: column;
  flex-direction: column;
  gap: 20px;
}
.notifications-sidebar {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.summary-stats-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}

/* ══ Toolbar ══════════════════════════════════════════════ */
.notif-toolbar {
  display:flex;align-items:center;justify-content:space-between;
  background:var(--card);border:1px solid var(--border);
  border-radius:14px;padding:12px 18px;
  box-shadow:0 2px 8px rgba(0,0,0,.04);
  flex-wrap: wrap; gap: 10px;
}
.notif-counter-badge {
  display:inline-flex;align-items:center;justify-content:center;
  min-width:22px;height:22px;border-radius:100px;
  background:linear-gradient(135deg,#dc2626,#b91c1c);
  color:#fff;font-size:11px;font-weight:700;padding:0 6px;
}
.btn-mark-all {
  display:inline-flex;align-items:center;gap:6px;
  padding:8px 16px;border-radius:10px;border:none;cursor:pointer;
  background:linear-gradient(135deg,#059669,#047857);
  color:#fff;font-size:12.5px;font-weight:700;
  font-family:'Tajawal',sans-serif;
  transition:opacity .18s,transform .18s;
  box-shadow:0 3px 12px rgba(5,150,105,.25);
}
.btn-mark-all:hover { opacity:.9;transform:translateY(-1px); }

/* ══ Section Labels ═══════════════════════════════════════ */
.notif-section { display:flex;flex-direction:column;gap:10px; }
.notif-section-label {
  display:flex;align-items:center;gap:8px;
  font-size:11.5px;font-weight:700;color:var(--text-m);
  letter-spacing:.5px;text-transform:uppercase;
  padding:0 4px;
}
.notif-section-dot { width:8px;height:8px;border-radius:50%;flex-shrink:0; }
.unread-dot { background:#dc2626;box-shadow:0 0 0 3px rgba(220,38,38,.15); }
.read-dot   { background:#9ca3af; }

/* ══ Notification List & Items ═══════════════════════════ */
.notif-list { display:flex;flex-direction:column;gap:8px; }
.notif-item {
  display:flex;align-items:flex-start;gap:14px;
  padding:16px 18px;border-radius:14px;
  border:1px solid var(--border); background:var(--card);
  transition:box-shadow .2s,transform .2s;
  position:relative;overflow:hidden;
}
.notif-item::before {
  content:'';position:absolute;right:0;top:0;bottom:0;
  width:3px;border-radius:4px 0 0 4px;
}
.notif-item.unread::before { background:linear-gradient(180deg,#dc2626,#ef4444); }
.notif-item.read::before   { background:transparent; }
.notif-item:hover { box-shadow:0 6px 22px rgba(0,0,0,.07); transform:translateY(-1px); }
.notif-item.unread {
  background:color-mix(in srgb,var(--card) 96%,#fef2f2);
  border-color:color-mix(in srgb,var(--border) 80%,#fca5a5);
}

/* Icons */
.notif-icon-wrap {
  width:44px;height:44px;border-radius:12px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center; font-size:20px;
}
.notif-icon-wrap.muted { opacity:.5; }
.notif-icon-wrap.ward-type   { background:linear-gradient(135deg,#022c22,#064e3b); }
.notif-icon-wrap.memo-type   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
.notif-icon-wrap.streak-type { background:linear-gradient(135deg,#fff7ed,#ffedd5); }
.notif-icon-wrap.badge-type  { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
.notif-icon-wrap.family-type { background:linear-gradient(135deg,#fdf4ff,#fae8ff); }
.notif-icon-wrap.default-type{ background:var(--bg); }

/* Elements inside body */
.notif-body { flex:1;min-width:0; }
.notif-title { font-size:13.5px;font-weight:700;color:var(--text);margin-bottom:4px;line-height:1.4; }
.notif-title.muted { color:var(--text-m); }
.notif-desc { font-size:12px;color:var(--text-m);line-height:1.6;margin-bottom:8px; }
.notif-meta { display:flex;align-items:center;gap:10px;flex-wrap:wrap; }
.notif-channel { font-size:10px;font-weight:700;padding:2px 8px;border-radius:100px; }
.notif-channel.database { background:#ecfdf5;color:#065f46; }
.notif-channel.email    { background:#eff6ff;color:#1d4ed8; }
.notif-channel.push     { background:#fdf4ff;color:#7e22ce; }

/* Actions styling */
.notif-actions { display:flex;flex-direction:row;gap:6px;flex-shrink:0; align-self: center; }
.notif-action-btn {
  width:30px;height:30px;border-radius:8px;
  display:flex;align-items:center;justify-content:center;
  border:1px solid var(--border);background:var(--bg);
  cursor:pointer;transition:all .18s;
}
.notif-action-btn.read-btn:hover { background:#ecfdf5;border-color:#a7f3d0;color:#059669; }
.notif-action-btn.delete-btn:hover { background:#fef2f2;border-color:#fca5a5;color:#dc2626; }

/* ══ Empty State Animation ══════════════════════════════ */
.empty-bell {
  width:80px;height:80px;border-radius:24px;
  background:linear-gradient(135deg,#f4f7f5,#e8f5ef); border:2px solid #d1fae5;
  display:flex;align-items:center;justify-content:center;
  font-size:36px;margin:0 auto 18px;
  animation:bellSwing 2.5s ease-in-out infinite;
}
@keyframes bellSwing {
  0%,100% { transform:rotate(0deg); }
  15%      { transform:rotate(-10deg); }
  30%      { transform:rotate(10deg); }
  45%      { transform:rotate(-8deg); }
  60%      { transform:rotate(8deg); }
  75%      { transform:rotate(-4deg); }
}

/* ══ التجاوب لشاشات الهواتف والأجهزة اللوحية ═══════════════ */
@media (max-width: 991px) {
  .main-notification-container {
    grid-template-columns: 1fr; /* تحويل العرض إلى عمود واحد كامل لتفادي ضغط الأزرار والبطاقات */
  }
}

@media (max-width: 576px) {
  .notif-item {
    flex-direction: column; /* جعل الأيقونة والمحتوى والأزرار تترتب رأسياً في الهواتف الضيقة */
    align-items: stretch;
    gap: 12px;
  }
  .notif-actions {
    align-self: flex-end; /* سحب أزرار التحكم والمسح لليسار أسفل المحتوى لسهولة ضغطها باليد */
    margin-top: 4px;
  }
  .summary-stats-grid {
    grid-template-columns: 1fr; /* جعل الأرقام الإحصائية بطاقة واحدة لكل سطر */
  }
}
</style>
@endpush