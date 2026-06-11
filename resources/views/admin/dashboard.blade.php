@extends('layouts.app')
@section('title','لوحة الإدارة')
@section('content')

{{-- ══ Header ══ --}}
<div class="page-header" style="margin-bottom:32px">
  <div class="page-header-ornament">
    <div class="ornament-line"></div>
    <span class="ornament-icon">✦</span>
    <div class="ornament-line"></div>
  </div>
  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
    <div>
      <h1 class="page-title">لوحة الإدارة العامة</h1>
      <p class="page-subtitle">نظرة شاملة على نشاط المنصة وإدارة المستخدمين والعائلات</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a href="{{ route('admin.users.index') }}"
         style="display:inline-flex;align-items:center;gap:7px;
                padding:10px 18px;border-radius:11px;
                background:var(--card);border:1.5px solid var(--border);
                font-family:'Tajawal',sans-serif;font-size:13px;
                font-weight:600;color:var(--text);cursor:pointer;text-decoration:none;">
        👥 إدارة المستخدمين
      </a>
      <a href="{{ route('admin.families.index') }}"
         style="display:inline-flex;align-items:center;gap:7px;
                padding:10px 18px;border-radius:11px;
                background:var(--card);border:1.5px solid var(--border);
                font-family:'Tajawal',sans-serif;font-size:13px;
                font-weight:600;color:var(--text);cursor:pointer;text-decoration:none;">
        👨‍👩‍👧 إدارة العائلات
      </a>
    </div>
  </div>
</div>

{{-- ══ إحصائيات المنصة ══ --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:32px">
  @foreach([
    ['إجمالي المستخدمين', $stats['total_users'], 'مستخدم مسجل', '#eff6ff', '#bfdbfe', '#1d4ed8'],
    ['العائلات النشطة', $stats['active_families'] . '/' . $stats['total_families'], 'عائلة مسجلة', '#fdf4ff', '#e9d5ff', '#7e22ce'],
    ['الختمات النشطة', $stats['active_khatmas'] . '/' . $stats['total_khatmas'], 'ختمة بالمجمل', '#ecfdf5', '#a7f3d0', '#059669'],
    ['المراجعات المكتملة', $stats['completed_revisions'], 'جزء تمت مراجعته', '#fffbeb', '#fde68a', '#d97706'],
  ] as [$lbl, $val, $sub, $bg, $border, $vc])
  <div style="background:{{ $bg }};border:1px solid {{ $border }};
              border-radius:16px;padding:24px 20px;text-align:center;
              transition:transform .2s,box-shadow .2s;cursor:default"
       onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)'"
       onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
    <p style="font-family:'Amiri',serif;font-size:2.2rem;
              font-weight:700;color:{{ $vc }};line-height:1;
              margin-bottom:8px">{{ $val }}</p>
    <p style="font-size:14px;font-weight:700;color:var(--text);
              margin-bottom:4px">{{ $lbl }}</p>
    <p style="font-size:11.5px;color:var(--text-m)">{{ $sub }}</p>
  </div>
  @endforeach
</div>

{{-- ══ أحدث النشاطات ══ --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:24px">
  
  {{-- المستخدمين الجدد --}}
  <div style="background:var(--card);border:1px solid var(--border);border-radius:20px;overflow:hidden">
    <div style="padding:20px 24px;border-bottom:1px solid var(--border);
                display:flex;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:10px">
        <div style="width:36px;height:36px;border-radius:10px;
                    background:linear-gradient(135deg,#eff6ff,#dbeafe);
                    display:flex;align-items:center;justify-content:center;
                    font-size:18px">👤</div>
        <p style="font-size:15px;font-weight:700;color:var(--text)">أحدث المستخدمين</p>
      </div>
      <a href="{{ route('admin.users.index') }}" style="font-size:12.5px;color:#2563eb;font-weight:600;text-decoration:none">عرض الكل</a>
    </div>
    <div style="padding:0">
      @forelse($latestUsers as $u)
      <div style="padding:16px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:14px">
        <div style="width:40px;height:40px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;color:#475569;flex-shrink:0">
          {{ mb_substr($u->name, 0, 1) }}
        </div>
        <div style="flex:1;min-width:0">
          <p style="font-size:13.5px;font-weight:700;color:var(--text);margin-bottom:2px">{{ $u->name }}</p>
          <p style="font-size:11.5px;color:var(--text-m)">{{ $u->email }}</p>
        </div>
        <div>
          <span style="font-size:10.5px;padding:3px 10px;border-radius:100px;
                       background:{{ $u->role === 'super_admin' ? '#fdf4ff' : ($u->role === 'family_admin' ? '#eff6ff' : '#f8fafc') }};
                       color:{{ $u->role === 'super_admin' ? '#9333ea' : ($u->role === 'family_admin' ? '#2563eb' : '#64748b') }};
                       font-weight:600">
            {{ match($u->role) { 'super_admin' => 'مدير نظام', 'family_admin' => 'مدير عائلة', default => 'عضو' } }}
          </span>
        </div>
      </div>
      @empty
      <div style="padding:30px;text-align:center;font-size:13px;color:var(--text-m)">لا يوجد مستخدمين مسجلين بعد.</div>
      @endforelse
    </div>
  </div>

  {{-- العائلات الحديثة --}}
  <div style="background:var(--card);border:1px solid var(--border);border-radius:20px;overflow:hidden">
    <div style="padding:20px 24px;border-bottom:1px solid var(--border);
                display:flex;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:10px">
        <div style="width:36px;height:36px;border-radius:10px;
                    background:linear-gradient(135deg,#fdf4ff,#fae8ff);
                    display:flex;align-items:center;justify-content:center;
                    font-size:18px">👨‍👩‍👧</div>
        <p style="font-size:15px;font-weight:700;color:var(--text)">أحدث العائلات</p>
      </div>
      <a href="{{ route('admin.families.index') }}" style="font-size:12.5px;color:#9333ea;font-weight:600;text-decoration:none">عرض الكل</a>
    </div>
    <div style="padding:0">
      @forelse($latestFamilies as $f)
      <div style="padding:16px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:14px">
        <div style="width:40px;height:40px;border-radius:10px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">
          🏡
        </div>
        <div style="flex:1;min-width:0">
          <p style="font-size:13.5px;font-weight:700;color:var(--text);margin-bottom:2px">{{ $f->name }}</p>
          <p style="font-size:11.5px;color:var(--text-m)">بواسطة: {{ $f->creator->name ?? 'غير معروف' }}</p>
        </div>
        <div>
          <span style="font-size:10.5px;padding:3px 10px;border-radius:100px;
                       background:{{ $f->is_active ? '#ecfdf5' : '#fef2f2' }};
                       color:{{ $f->is_active ? '#059669' : '#b91c1c' }};
                       font-weight:600">
            {{ $f->is_active ? 'نشطة' : 'غير نشطة' }}
          </span>
        </div>
      </div>
      @empty
      <div style="padding:30px;text-align:center;font-size:13px;color:var(--text-m)">لا يوجد عائلات مسجلة بعد.</div>
      @endforelse
    </div>
  </div>

</div>

@endsection
