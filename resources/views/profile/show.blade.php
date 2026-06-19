@extends('layouts.app')
@section('title', 'الملف الشخصي')

@section('content')

{{-- ══ بانر الملف الشخصي ══ --}}
<div style="position:relative;border-radius:20px;overflow:hidden;
            margin-bottom:24px;min-height:160px;
            background:linear-gradient(135deg,#022c22,#064e3b,#0a6647)">
  <div style="position:absolute;inset:0;opacity:.05;
              background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E\");
              background-size:60px"></div>

  <div style="position:relative;z-index:10;padding:32px;
              display:flex;align-items:flex-end;gap:22px;flex-wrap:wrap">
    {{-- الصورة الشخصية --}}
    <div style="width:88px;height:88px;border-radius:20px;
                overflow:hidden;flex-shrink:0;
                border:3px solid rgba(245,158,11,.5);
                box-shadow:0 8px 24px rgba(0,0,0,.3);
                background:rgba(255,255,255,.1);
                display:flex;align-items:center;justify-content:center">
      @if($user->avatar_url)
        <img src="{{ asset($user->avatar_url) }}" alt="{{ $user->name }}"
             style="width:100%;height:100%;object-fit:cover"/>
      @else
        <span style="font-family:'Amiri',serif;font-size:2.5rem;
                     font-weight:700;color:#f59e0b">
          {{ mb_substr($user->name,0,1) }}
        </span>
      @endif
    </div>

    <div style="flex:1;min-width:0">
      <h1 style="font-family:'Amiri',serif;font-size:1.8rem;
                 font-weight:700;color:#fff;margin-bottom:5px">
        {{ $user->name }}
      </h1>
      <p style="font-size:13px;color:rgba(255,255,255,.65);margin-bottom:10px">
        {{ $user->email }}
      </p>
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <span style="font-size:11.5px;padding:4px 12px;border-radius:100px;font-weight:700;
              background:rgba(245,158,11,.2);color:#fcd34d;border:1px solid rgba(245,158,11,.3)">
          @if($user->isSuperAdmin()) 🌟 المدير العام
          @elseif($user->isFamilyAdmin()) 👑 مسؤول العائلة
          @else 📖 عضو العائلة
          @endif
        </span>
        <span style="font-size:11.5px;color:rgba(255,255,255,.55)">
          عضو منذ {{ $stats['member_since'] }}
        </span>
      </div>
    </div>

    <a href="{{ route('profile.edit') }}"
       style="display:inline-flex;align-items:center;gap:8px;
              padding:10px 20px;border-radius:11px;
              background:rgba(255,255,255,.12);
              border:1px solid rgba(255,255,255,.2);
              color:#fff;font-size:13px;font-weight:600;
              text-decoration:none;transition:background .18s;flex-shrink:0"
       onmouseover="this.style.background='rgba(255,255,255,.2)'"
       onmouseout="this.style.background='rgba(255,255,255,.12)'">
      ✏️ تعديل الملف
    </a>
  </div>
</div>

{{-- ══ إحصائيات ══ --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);
            gap:14px;margin-bottom:24px">
  @foreach([
    ['🔥',$stats['current_streak'],'يوم متتالي','أطول: '.$stats['longest_streak'],'#fff7ed','#fed7aa','#ea580c'],
    ['📖',$stats['total_wards'],'ورد مكتمل','مجموع الأوراد','#ecfdf5','#a7f3d0','#059669'],
    ['🎯',$stats['total_ayahs'],'آية محفوظة','إجمالي الحفظ','#eff6ff','#bfdbfe','#2563eb'],
    ['⭐',$stats['total_xp'],'نقطة XP','مجموع النقاط','#fdf4ff','#e9d5ff','#9333ea'],
    ['🏅',$stats['badges_count'],'وسام مكتسب','شاراتي','#fffbeb','#fde68a','#d97706'],
    ['👨‍👩‍👧',$families->count(),'عائلة منضم','دوائر القرآن','#ecfdf5','#a7f3d0','#059669'],
  ] as [$icon,$val,$lbl,$sub,$bg,$border,$vc])
  <div style="background:{{ $bg }};border:1px solid {{ $border }};
              border-radius:16px;padding:18px;
              transition:transform .2s;cursor:default"
       onmouseover="this.style.transform='translateY(-2px)'"
       onmouseout="this.style.transform='translateY(0)'">
    <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:10px">
      <span style="font-size:22px">{{ $icon }}</span>
    </div>
    <p style="font-family:'Amiri',serif;font-size:1.7rem;font-weight:700;
              color:{{ $vc }};line-height:1;margin-bottom:4px">{{ $val }}</p>
    <p style="font-size:12.5px;font-weight:700;color:var(--text);margin-bottom:1px">{{ $lbl }}</p>
    <p style="font-size:11px;color:var(--text-m)">{{ $sub }}</p>
  </div>
  @endforeach
</div>

<div style="display:grid;grid-template-columns:minmax(0,2fr) 300px;gap:20px">

  {{-- ── المحتوى الرئيسي ── --}}
  <div style="display:flex;flex-direction:column;gap:18px">

    {{-- الشارات --}}
    @if($badges->isNotEmpty())
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon gold">🏅</div>
          شاراتي المكتسبة
        </div>
        <span style="font-size:12px;color:var(--text-m)">{{ $stats['badges_count'] }} وسام</span>
      </div>
      <div style="padding:18px 22px">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px">
          @foreach($badges as $badge)
          <div style="padding:14px;border-radius:14px;text-align:center;
                      background:var(--bg);border:1px solid var(--border)">
            <span style="font-size:28px;display:block;margin-bottom:8px">
              {{ $badge->icon_url ?? '🏅' }}
            </span>
            <p style="font-size:12.5px;font-weight:700;color:var(--text);margin-bottom:3px">
              {{ $badge->name }}
            </p>
            <p style="font-size:11px;color:var(--text-m)">
              {{ $badge->pivot->earned_at->locale('ar')->diffForHumans() }}
            </p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    {{-- العائلات --}}
    @if($families->isNotEmpty())
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon green">👨‍👩‍👧</div>
          عائلاتي
        </div>
        <a href="{{ route('families.index') }}"
           style="font-size:12.5px;color:#059669;text-decoration:none;font-weight:600">
          عرض الكل
        </a>
      </div>
      <div style="padding:16px 22px;display:flex;flex-direction:column;gap:10px">
        @foreach($families as $family)
        <a href="{{ route('families.show', $family) }}"
           style="display:flex;align-items:center;gap:12px;
                  padding:12px;border-radius:12px;
                  background:var(--bg);border:1px solid var(--border);
                  text-decoration:none;transition:border-color .18s"
           onmouseover="this.style.borderColor='#a7f3d0'"
           onmouseout="this.style.borderColor='var(--border)'">
          <div style="width:40px;height:40px;border-radius:11px;
                      background:linear-gradient(135deg,#ecfdf5,#d1fae5);
                      border:1px solid #a7f3d0;
                      display:flex;align-items:center;justify-content:center;
                      font-size:18px;flex-shrink:0">
            👨‍👩‍👧
          </div>
          <div>
            <p style="font-size:13.5px;font-weight:700;color:var(--text)">
              {{ $family->name }}
            </p>
            <p style="font-size:11px;color:var(--text-m);margin-top:2px">
              {{ $family->pivot->role === 'admin' ? '👑 مسؤول' : '📖 عضو' }}
            </p>
          </div>
          <svg style="margin-right:auto;color:var(--text-m)"
               width="16" height="16" fill="none" stroke="currentColor"
               stroke-width="2" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
        @endforeach
      </div>
    </div>
    @endif

  </div>

  {{-- ── الشريط الجانبي ── --}}
  <div style="display:flex;flex-direction:column;gap:16px">

    {{-- معلومات الحساب --}}
    <div class="card">
      <div style="padding:14px 18px;
                  background:linear-gradient(135deg,#022c22,#064e3b)">
        <p style="color:#fff;font-size:13.5px;font-weight:700">ℹ️ معلومات الحساب</p>
      </div>
      <div style="padding:16px 18px;display:flex;flex-direction:column;gap:0">
        @foreach([
          ['الاسم',  $user->name],
          ['البريد', $user->email],
          ['الهاتف', $user->phone ?? '—'],
          ['اللغة',  $user->locale === 'ar' ? 'العربية' : $user->locale],
        ] as [$k,$v])
        <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:11px 0;border-bottom:1px solid var(--border)">
          <span style="font-size:12px;color:var(--text-m)">{{ $k }}</span>
          <span style="font-size:12.5px;font-weight:600;color:var(--text);
                       max-width:160px;text-align:left;overflow:hidden;
                       text-overflow:ellipsis;white-space:nowrap">{{ $v }}</span>
        </div>
        @endforeach
      </div>
      <div style="padding:14px 18px">
        <a href="{{ route('profile.edit') }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;
                  padding:10px;border-radius:10px;
                  background:linear-gradient(135deg,#0d6b52,#064e3b);
                  color:#fff;font-size:13px;font-weight:700;text-decoration:none">
          ✏️ تعديل البيانات
        </a>
      </div>
    </div>

    {{-- آية الملف الشخصي --}}
    <div style="background:linear-gradient(135deg,#031810,#042a1e);
                border-radius:16px;padding:24px;text-align:center">
      <p style="font-family:'Amiri',serif;font-size:1.2rem;
                color:rgba(255,255,255,.9);line-height:2;margin-bottom:8px">
        ﴿ وَمَن يَتَّقِ اللَّهَ يَجْعَل لَّهُ مَخْرَجًا ﴾
      </p>
      <p style="font-size:11px;color:#f59e0b;opacity:.8">
        سورة الطلاق — الآية ٢
      </p>
    </div>

  </div>
</div>

@endsection

@push('styles')
<style>
@media (max-width:1024px) {
  div[style*="grid-template-columns:minmax(0,2fr) 300px"] {
    grid-template-columns: 1fr !important;
  }
}
@media (max-width:768px) {
  div[style*="grid-template-columns:repeat(3,1fr)"] {
    grid-template-columns: repeat(2,1fr) !important;
  }
}
</style>
@endpush