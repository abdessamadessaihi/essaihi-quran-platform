@extends('layouts.app')
@section('title', 'لوحة الشرف')

@section('content')

{{-- ══ رأس الصفحة ══ --}}
<div class="page-header" style="margin-bottom:32px">
  <div class="page-header-ornament">
    <div class="ornament-line"></div>
    <span class="ornament-icon">✦</span>
    <div class="ornament-line"></div>
  </div>
  <h1 class="page-title">لوحة الشرف🏆</h1>
  <p class="page-subtitle">وَفِي ذَٰلِكَ فَلْيَتَنَافَسِ الْمُتَنَافِسُونَ — تابع ترتيبك بين أهل القرآن وتقدم في درجات الطاعة</p>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

  {{-- ── العمود الرئيسي (2/3) ── --}}
  <div class="xl:col-span-2 flex flex-col gap-6">

    {{-- الفلاتر --}}
    <div class="card" style="padding:16px">
      <div style="display:flex;flex-direction:column;gap:14px">
        
        {{-- فلتر الفئات (التصنيف) --}}
        <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px">
          @foreach([
            ['xp', '✨ نقاط الخبرة (XP)', 'ترتيب التفاعل والالتزام العام'],
            ['streak', '🔥 الأيام المتتالية', 'سلسلة المتابعة اليومية المستمرة'],
            ['memorization', '📖 عدد الآيات المحفوظة', 'ترتيب قوة الحفظ والتقدم'],
            ['reading', '🌙 الأوراد المكتملة', 'ترتيب المواظبة على الورد اليومي'],
          ] as [$catVal, $catLabel, $catDesc])
          <a href="{{ route('leaderboard', ['category' => $catVal, 'period' => $period]) }}"
             style="display:flex;flex-direction:column;gap:4px;padding:10px 16px;
                    border-radius:12px;border:1.5px solid;
                    text-decoration:none;white-space:nowrap;transition:all .18s;
                    {{ $category === $catVal 
                       ? 'background:linear-gradient(135deg,#0d6b52,#064e3b);border-color:#064e3b;color:#fff' 
                       : 'background:var(--card);border-color:var(--border);color:var(--text)' }}"
             title="{{ $catDesc }}">
            <span style="font-size:13.5px;font-weight:700">{{ $catLabel }}</span>
          </a>
          @endforeach
        </div>

        {{-- فلتر الفترة الزمنية --}}
        <div style="display:flex;align-items:center;justify-content:space-between;
                    border-top:1px solid var(--border);padding-top:12px;flex-wrap:wrap;gap:10px">
          <span style="font-size:12.5px;color:var(--text-m);font-weight:600">الفترة الزمنية:</span>
          
          <div style="display:flex;background:var(--bg);border:1px solid var(--border);
                      border-radius:10px;padding:3px;gap:4px">
            @foreach([
              ['weekly', 'أسبوعي'],
              ['monthly', 'شهري'],
              ['alltime', 'كل الأوقات'],
            ] as [$perVal, $perLabel])
            <a href="{{ route('leaderboard', ['category' => $category, 'period' => $perVal]) }}"
               style="padding:6px 16px;border-radius:7px;font-size:12px;font-weight:700;
                      text-decoration:none;transition:all .18s;
                      {{ $period === $perVal
                         ? 'background:#059669;color:#fff;box-shadow:0 3px 8px rgba(5,150,105,.2)'
                         : 'color:var(--text-m)' }}">
              {{ $perLabel }}
            </a>
            @endforeach
          </div>
        </div>

      </div>
    </div>

    {{-- المنصة التتويجية (Podium) للمراكز الثلاثة الأولى --}}
    @if($leaderboard->isNotEmpty())
    <div class="podium-section">
      <div class="podium-container">
        
        {{-- المركز الثاني --}}
        @if($leaderboard->count() > 1)
        @php $second = $leaderboard->get(1); @endphp
        <div class="podium-step step-second">
          <div class="podium-avatar-wrapper">
            <div class="podium-medal">🥈</div>
            <img src="{{ $second->avatar_url ? asset($second->avatar_url) : asset('images/user.png') }}"
                 alt="{{ $second->name }}" class="podium-avatar">
          </div>
          <p class="podium-name">{{ $second->name }}</p>
          <div class="podium-score">
            <span>{{ $second->score }}</span>
            <span style="font-size:10px;opacity:.8">
              {{ $category === 'xp' ? 'نقطة' : ($category === 'streak' ? 'يوم' : ($category === 'memorization' ? 'آية' : 'ورد')) }}
            </span>
          </div>
          <div class="podium-block">٢</div>
        </div>
        @endif

        {{-- المركز الأول --}}
        @php $first = $leaderboard->first(); @endphp
        <div class="podium-step step-first">
          <div class="podium-avatar-wrapper">
            <div class="podium-medal crown">👑</div>
            <img src="{{ $first->avatar_url ? asset($first->avatar_url) : asset('images/user.png') }}"
                 alt="{{ $first->name }}" class="podium-avatar">
          </div>
          <p class="podium-name">{{ $first->name }}</p>
          <div class="podium-score">
            <span>{{ $first->score }}</span>
            <span style="font-size:11px;opacity:.8">
              {{ $category === 'xp' ? 'نقطة' : ($category === 'streak' ? 'يوم' : ($category === 'memorization' ? 'آية' : 'ورد')) }}
            </span>
          </div>
          <div class="podium-block">١</div>
        </div>

        {{-- المركز الثالث --}}
        @if($leaderboard->count() > 2)
        @php $third = $leaderboard->get(2); @endphp
        <div class="podium-step step-third">
          <div class="podium-avatar-wrapper">
            <div class="podium-medal">🥉</div>
            <img src="{{ $third->avatar_url ? asset($third->avatar_url) : asset('images/user.png') }}"
                 alt="{{ $third->name }}" class="podium-avatar">
          </div>
          <p class="podium-name">{{ $third->name }}</p>
          <div class="podium-score">
            <span>{{ $third->score }}</span>
            <span style="font-size:10px;opacity:.8">
              {{ $category === 'xp' ? 'نقطة' : ($category === 'streak' ? 'يوم' : ($category === 'memorization' ? 'آية' : 'ورد')) }}
            </span>
          </div>
          <div class="podium-block">٣</div>
        </div>
        @endif

      </div>
    </div>
    @endif

    {{-- جدول الترتيب الكامل --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <span style="font-size:18px">📊</span>
          ترتيب الأعضاء الكامل
        </div>
        <span style="font-size:11px;color:var(--text-m)">عرض أفضل 50 مشاركاً</span>
      </div>
      
      @if($leaderboard->isEmpty())
      <div style="padding:48px;text-align:center">
        <span style="font-size:40px;display:block;margin-bottom:12px">🌱</span>
        <p style="font-size:14px;color:var(--text-m)">لا توجد بيانات مسجلة لهذه الفئة أو الفترة بعد.</p>
        <p style="font-size:12px;color:var(--text-m);margin-top:6px">كن أول من يسجل نشاطاً اليوم ويظهر هنا!</p>
      </div>
      @else
      <div style="overflow-x:auto">
        <table class="leaderboard-table" style="width:100%;border-collapse:collapse;text-align:right">
          <thead>
            <tr style="border-bottom:1.5px solid var(--border)">
              <th style="padding:16px;font-size:12px;color:var(--text-m);font-weight:700">الترتيب</th>
              <th style="padding:16px;font-size:12px;color:var(--text-m);font-weight:700">العضو</th>
              <th style="padding:16px;font-size:12px;color:var(--text-m);font-weight:700">الرتبة</th>
              <th style="padding:16px;font-size:12px;color:var(--text-m);font-weight:700;text-align:left">الإنجاز</th>
            </tr>
          </thead>
          <tbody>
            @foreach($leaderboard as $index => $user)
            @php $rank = $index + 1; @endphp
            <tr style="border-bottom:1px solid var(--border);
                       background: {{ $user->id === auth()->id() ? 'rgba(5,150,105,.05)' : 'transparent' }}"
                class="table-row-hover">
              
              {{-- الترتيب --}}
              <td style="padding:16px;font-weight:700">
                @if($rank === 1)
                  <span class="rank-badge rank-1">1</span>
                @elseif($rank === 2)
                  <span class="rank-badge rank-2">2</span>
                @elseif($rank === 3)
                  <span class="rank-badge rank-3">3</span>
                @else
                  <span class="rank-badge rank-normal">{{ $rank }}</span>
                @endif
              </td>

              {{-- العضو --}}
              <td style="padding:16px">
                <div style="display:flex;align-items:center;gap:12px">
                  <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;
                              border:1.5px solid var(--border);flex-shrink:0">
                    <img src="{{ $user->avatar_url ? asset($user->avatar_url) : asset('images/user.png') }}"
                         alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover">
                  </div>
                  <div>
                    <span style="font-weight:700;color:var(--text);font-size:13.5px;
                                 display:flex;align-items:center;gap:6px">
                      {{ $user->name }}
                      @if($user->id === auth()->id())
                        <span style="font-size:9.5px;padding:2px 8px;border-radius:100px;
                                     background:#059669;color:#fff;font-weight:700">أنت</span>
                      @endif
                    </span>
                    <span style="font-size:11px;color:var(--text-m);display:block;margin-top:2px">
                      عضو نشط
                    </span>
                  </div>
                </div>
              </td>

              {{-- الرتبة --}}
              <td style="padding:16px">
                <span style="font-size:11.5px;padding:3px 10px;border-radius:100px;font-weight:600;
                             background:var(--bg);border:1px solid var(--border);color:var(--text-m)">
                  @if($user->isSuperAdmin())
                    المدير العام
                  @elseif($user->isFamilyAdmin())
                    مسؤول العائلة
                  @else
                    عضو
                  @endif
                </span>
              </td>

              {{-- الإنجاز --}}
              <td style="padding:16px;text-align:left;font-weight:800;font-size:1.1rem;color:#059669">
                {{ $user->score }}
                <span style="font-size:11px;font-weight:600;color:var(--text-m);margin-right:2px">
                  {{ $category === 'xp' ? 'نقطة' : ($category === 'streak' ? 'يوم' : ($category === 'memorization' ? 'آية' : 'ورد')) }}
                </span>
              </td>

            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @endif
    </div>

  </div>

  {{-- ── الشريط الجانبي (1/3) ── --}}
  <div style="display:flex;flex-direction:column;gap:20px">

    {{-- بطاقة ترتيبي الخاص --}}
    <div class="card" style="border-color:#fde68a">
      <div style="padding:16px 20px;
                  background:linear-gradient(135deg,#fffbeb,#fef3c7);
                  display:flex;align-items:center;gap:12px;
                  border-bottom:1px solid #fde68a">
        <span style="font-size:24px">🏆</span>
        <div>
          <p style="color:#78350f;font-size:14.5px;font-weight:700">
            ترتيبي وموقعي
          </p>
          <p style="color:#b45309;font-size:11px;margin-top:2px">
            الفئة المحددة حالياً
          </p>
        </div>
      </div>
      <div style="padding:24px;text-align:center">
        @if($currentUserRank)
        <div style="display:inline-flex;align-items:baseline;gap:4px">
          <span style="font-size:3.5rem;font-weight:900;color:#d97706;line-height:1">
            {{ $currentUserRank }}
          </span>
          <span style="font-size:13px;font-weight:700;color:var(--text-m)">من أصل {{ $leaderboard->count() }}</span>
        </div>
        
        <p style="font-size:13px;color:var(--text-m);margin-top:12px;line-height:1.6">
          إنجازك الحالي: <strong>{{ $currentUserScore }}</strong>
          {{ $category === 'xp' ? 'نقطة XP' : ($category === 'streak' ? 'أيام متتالية' : ($category === 'memorization' ? 'آية محفوظة' : 'ورد مكتمل')) }}
        </p>

        <div style="margin-top:16px;padding:12px;border-radius:10px;background:#fef3c7;
                    border:1px solid #fde68a;font-size:11.5px;color:#78350f;line-height:1.6">
          @if($currentUserRank <= 3)
            🎉 هنيئاً لك! أنت على منصة التتويج، استمر في الصدارة وحافظ على همّتك.
          @elseif($currentUserRank <= 10)
            👏 رائع جداً! أنت ضمن قائمة أفضل 10 متسابقين، خطوة صغيرة وتصل لمنصة التتويج!
          @else
            💡 حافظ على وردك اليومي ومحفوظاتك لتزيد نقاطك وترتقي في الترتيب!
          @endif
        </div>
        @else
        <p style="font-size:13px;color:var(--text-m)">
          لم تسجّل أي نقاط أو نشاط في هذه الفئة بعد.
        </p>
        <a href="{{ route('ward.index') }}" class="btn-primary" 
           style="display:inline-block;margin-top:12px;text-decoration:none;padding:8px 16px;border-radius:8px">
          ابدأ الورد اليومي
        </a>
        @endif
      </div>
    </div>

    {{-- دليل كسب النقاط (XP) --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <span style="font-size:16px">✨</span>
          كيف تكسب نقاط الخبرة (XP)؟
        </div>
      </div>
      <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
        @foreach([
          ['🌙 الورد اليومي', 'كسب 100 نقطة عند إتمام ورد القراءة اليومي بالكامل.'],
          ['📖 حفظ آيات جديدة', 'كسب 10 نقاط عن كل آية تقوم بتسجيل حفظها.'],
          ['🧠 مراجعة المحفوظ', 'كسب 50 نقطة عند الانتهاء من مراجعة مجدولة بنجاح.'],
          ['🏆 سلاسل الأيام', 'مكافآت XP إضافية تتضاعف مع استمرار سلسلة أيامك المتتالية.'],
        ] as [$title, $desc])
        <div style="padding:10px;border-radius:10px;background:var(--bg);border:1px solid var(--border)">
          <p style="font-size:12px;font-weight:700;color:var(--text);margin-bottom:3px">{{ $title }}</p>
          <p style="font-size:11px;color:var(--text-m);line-height:1.5">{{ $desc }}</p>
        </div>
        @endforeach
      </div>
    </div>

  </div>

</div>

@endsection

@push('styles')
<style>
/* ══ Podium Styles ═════════════════════════════════════ */
.podium-section {
  background: linear-gradient(135deg, #022c22, #043e2f);
  border-radius: 20px;
  padding: 36px 24px;
  color: #fff;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(2,44,34,.25);
  position: relative;
}
.podium-section::before {
  content: '';
  position: absolute; inset: 0; opacity: .03;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='%23fff'/%3E%3C/svg%3E");
  background-size: 60px;
}
.podium-container {
  display: flex;
  align-items: flex-end;
  justify-content: center;
  gap: 16px;
  height: 240px;
  position: relative;
  z-index: 2;
}
.podium-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 30%;
  max-width: 150px;
  position: relative;
}
.podium-avatar-wrapper {
  position: relative;
  margin-bottom: 12px;
}
.podium-avatar {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid;
  background: rgba(255,255,255,.1);
}
.podium-medal {
  position: absolute;
  top: -16px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 22px;
}
.podium-medal.crown {
  font-size: 26px;
  top: -24px;
}

/* Blocks styling */
.podium-block {
  width: 100%;
  border-radius: 12px 12px 0 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  font-weight: 900;
  font-family: 'Amiri', serif;
  color: rgba(255,255,255,.9);
  transition: all .2s;
}

/* First Step */
.step-first .podium-avatar {
  width: 86px;
  height: 86px;
  border-color: #f59e0b;
}
.step-first .podium-block {
  height: 110px;
  background: linear-gradient(180deg, #d97706 0%, #b45309 100%);
  box-shadow: 0 6px 20px rgba(217,119,6,.3);
}

/* Second Step */
.step-second .podium-avatar {
  border-color: #cbd5e1;
}
.step-second .podium-block {
  height: 80px;
  background: linear-gradient(180deg, #64748b 0%, #475569 100%);
  box-shadow: 0 6px 15px rgba(100,116,139,.2);
}

/* Third Step */
.step-third .podium-avatar {
  border-color: #92400e;
}
.step-third .podium-block {
  height: 60px;
  background: linear-gradient(180deg, #a16207 0%, #854d0e 100%);
  box-shadow: 0 6px 15px rgba(161,98,7,.2);
}

.podium-name {
  font-size: 13px;
  font-weight: 700;
  color: #fff;
  text-align: center;
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 100%;
}
.podium-score {
  background: rgba(255,255,255,.15);
  padding: 3px 12px;
  border-radius: 100px;
  font-size: 11px;
  font-weight: 700;
  margin-bottom: 12px;
}

/* ══ Rank Badges ═══════════════════════════════════════ */
.rank-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  font-size: 12px;
  font-weight: 700;
}
.rank-1 { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
.rank-2 { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
.rank-3 { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.rank-normal { background: var(--bg); color: var(--text-m); border: 1.5px solid var(--border); }

/* ══ Table and page header ════════════════════════════════ */
.leaderboard-table th {
  background: var(--bg);
}
.table-row-hover:hover {
  background: rgba(0,0,0,.02) !important;
}
.page-header { text-align: center; }
.page-header-ornament {
  display: flex; align-items: center;
  justify-content: center; gap: 12px;
  margin-bottom: 14px;
}
.ornament-line {
  width: 80px; height: 1px;
  background: linear-gradient(to right, transparent, #a7f3d0);
}
.ornament-line:last-child {
  background: linear-gradient(to left, transparent, #a7f3d0);
}
.ornament-icon { font-size: 18px; color: #059669; }
.page-title {
  font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700;
  color: var(--text); margin-bottom: 8px;
}
.page-subtitle { font-size: 13.5px; color: var(--text-m); line-height: 1.7; }
</style>
@endpush