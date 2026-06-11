@extends('layouts.app')
@section('title', 'لوحة التحكم')

@section('content')

{{-- ═══ بانر الترحيب ═══ --}}
<div class="relative overflow-hidden rounded-2xl mb-7"
     style="background:linear-gradient(135deg,#022c22 0%,#064e3b 55%,#0a6647 100%);
            min-height:140px">

  <div class="absolute inset-0 opacity-5"
       style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E\");background-size:80px">
  </div>

  <div class="absolute top-0 left-0 w-56 h-56 rounded-full opacity-15 pointer-events-none"
       style="background:radial-gradient(circle,#10b981,transparent);
              transform:translate(-30%,-30%)"></div>

  <div class="absolute bottom-0 right-0 w-40 h-40 rounded-full opacity-10 pointer-events-none"
       style="background:radial-gradient(circle,#d97706,transparent);
              transform:translate(30%,30%)"></div>

  <div class="relative z-10 p-7 flex flex-col sm:flex-row
              items-start sm:items-center justify-between gap-5">

    <div class="flex items-center gap-4">

      {{-- Avatar image --}}
<div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0
            border-2 border-amber-400/40 bg-amber-500/20">

    <img src="{{ asset('images/user.png') }}"
         alt="User Avatar"
         class="w-full h-full object-cover">
</div>

      
      <div>
        <p style="color:rgba(167,243,208,.80);font-size:12px;margin-bottom:3px">
          {{ now()->hour ? " أهلا بك مجددا في رحاب القرآن " : 'اشتاق لك القرآن، مرحبا بك '  }}
        </p>

        <h1 style="color:#fff;font-size:1.4rem;font-weight:700;margin-bottom:4px">
          {{ auth()->user()->name }}
        </h1>

        <span style="font-size:11px;padding:3px 12px;border-radius:100px;font-weight:600"
              class="inline-block"
              style="background:rgba(255, 254, 252, 0.2);color:hsl(46, 71%, 20%);
                     border:1px solid rgba(255, 255, 255, 0.3)">
          @if(auth()->user()->isSuperAdmin())
            المدير العام
          @elseif(auth()->user()->isFamilyAdmin())
            مسؤول العائلة
          @else
            عضو العائلة
          @endif
        </span>
      </div>
    </div>

    <div class="flex items-center gap-3 flex-wrap">
      <div style="text-align:center;padding:0 16px;
                  border-left:1px solid rgba(255, 255, 255, 0.15)">
        <p style="font-family:'Amiri',serif;font-size:1.5rem;
                  font-weight:700;color:#f59e0b;line-height:1">
          {{ $dashboardStats['current_streak'] }}
        </p>
        <p style="font-size:10px;color:rgba(255,255,255,.55);margin-top:3px">
          يوم متتالي 
        </p>
      </div>

      <a href="{{ route('ward.index') }}"
         style="display:inline-flex;align-items:center;gap:8px;
                padding:11px 22px;border-radius:12px;
                background:linear-gradient(135deg,#d97706,#b45309);
                color:#fff;font-size:13.5px;font-weight:700;
                text-decoration:none;
                box-shadow:0 6px 20px rgba(217,119,6,.40);
                transition:transform .2s,box-shadow .2s"
         onmouseover="this.style.transform='translateY(-2px)'"
         onmouseout="this.style.transform='translateY(0)'">
        🌙 {{ $todayWard?->is_completed ? 'ورد اليوم مكتمل' : 'سجّل ورد اليوم' }}
      </a>
    </div>

  </div>
</div>
{{-- ═══ بطاقات الإحصاء ═══ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
  @foreach([
    ['icon'=>'day.png','bg'=>'linear-gradient(135deg,#fff7ed,#ffedd5)',
     'border'=>'#fed7aa','value'=>$dashboardStats['current_streak'],'label'=>'أيام متتالية',
     'sub'=>'أطول سلسلة: '.$dashboardStats['longest_streak'],'vcolor'=>'#ea580c'],
    ['icon'=>'quran.png','bg'=>'linear-gradient(135deg,#ecfdf5,#d1fae5)',
     'border'=>'#a7f3d0','value'=>$dashboardStats['completed_wards_count'],'label'=>'ورد مكتمل',
     'sub'=>'إجمالي الأوراد','vcolor'=>'#059669'],
    ['icon'=>'target2.png','bg'=>'linear-gradient(135deg,#eff6ff,#dbeafe)',
     'border'=>'#bfdbfe','value'=>$dashboardStats['memorized_juz_estimate'],'label'=>'جزء محفوظ',
     'sub'=>'من أصل ٣٠ جزءاً','vcolor'=>'#2563eb'],
    ['icon'=>'homeStatistics3.png','bg'=>'linear-gradient(135deg,#fdf4ff,#fae8ff)',
     'border'=>'#e9d5ff','value'=>$dashboardStats['total_xp'],'label'=>'نقطة XP',
     'sub'=>'المستوى: '.$dashboardStats['level'],'vcolor'=>'#9333ea'],
  ] as $s)
  <div style="background:{{ $s['bg'] }};border:1px solid {{ $s['border'] }};
              border-radius:16px;padding:22px 18px;
              transition:box-shadow .2s,transform .2s;cursor:default"
       onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,.08)'"
       onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
    <div style="display:flex;align-items:center;
                justify-content:space-between;margin-bottom:14px">
      <img src="{{ asset('images/'.$s['icon']) }}"
     alt="{{ $s['label'] }}"
     style="
        width:70px;
        height:70px;
        object-fit:contain;
        display:block;
        margin:0 auto;
     ">
      <span style="font-size:11px;padding:3px 9px;border-radius:100px;
                   background:rgba(255,255,255,.6);color:{{ $s['vcolor'] }};
                   font-weight:600">جديد</span>
    </div>
    <p style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
              color:{{ $s['vcolor'] }};line-height:1;margin-bottom:5px">
      {{ $s['value'] }}
    </p>
    <p style="font-size:13px;font-weight:700;color:#1a2e25;margin-bottom:2px">
      {{ $s['label'] }}
    </p>
    <p style="font-size:11px;color:#6b7280">{{ $s['sub'] }}</p>
  </div>
  @endforeach
</div>

{{-- ═══ المحتوى الرئيسي ═══ --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

  {{-- ── عمود (2/3) ── --}}
  <div class="xl:col-span-2 flex flex-col gap-6">

    {{-- شبكة الختمة ─────────────────────────────────── --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
    <img src="{{ asset('images/quran.png') }}"
     alt="Idée"
     style="width:30px;height:30px;object-fit:contain;
            display:flex;align-items:center;">          الختمات النشطة
        </div>
        <a href="{{ route('khatmas.index') }}"
           style="font-size:12.5px;color:#059669;text-decoration:none;
                  font-weight:600;display:flex;align-items:center;gap:4px">
          عرض الكل
          <svg width="14" height="14" fill="none" stroke="currentColor"
               stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
      <div class="card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    margin-bottom:16px;flex-wrap:wrap;gap:8px">
          @if($activeKhatmas->isEmpty())
            <p style="font-size:13px;color:var(--text-m)">
              لا توجد ختمات نشطة —
              <a href="{{ route('khatmas.create') }}"
                 style="color:#059669;font-weight:600;text-decoration:none">
                ابدأ ختمة الآن
              </a>
            </p>
          @else
            <div style="display:flex;flex-direction:column;gap:6px">
              @foreach($activeKhatmas as $khatma)
                <a href="{{ route('khatmas.show', $khatma) }}"
                   style="color:var(--text);font-size:13px;font-weight:700;text-decoration:none">
                  {{ $khatma->title }}
                  <span style="color:#059669;font-size:12px">
                    {{ $khatma->completion_percentage }}٪
                  </span>
                </a>
              @endforeach
            </div>
          @endif
          <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
            @foreach([
              ['#cbd5e1','متاح'],['#93c5fd','محجوز'],
              ['#fcd34d','قيد القراءة'],['#6ee7b7','مكتمل'],
            ] as [$c,$l])
            <div style="display:flex;align-items:center;gap:5px">
              <div style="width:12px;height:12px;border-radius:3px;
                          background:{{ $c }}"></div>
              <span style="font-size:11px;color:var(--text-m)">{{ $l }}</span>
            </div>
            @endforeach
          </div>
        </div>

        {{-- شبكة ٣٠ جزء --}}
        <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:8px">
          @for($i = 1; $i <= 30; $i++)
          <div style="aspect-ratio:1;border-radius:12px;
                      display:flex;flex-direction:column;
                      align-items:center;justify-content:center;
                      background:#f8fafc;
                      border:1.5px solid #e2e8f0;
                      cursor:pointer;transition:all .2s;
                      font-size:13px;font-weight:600;color:#64748b"
               onmouseover="this.style.transform='scale(1.06)';
                             this.style.boxShadow='0 4px 14px rgba(0,0,0,.1)'"
               onmouseout="this.style.transform='scale(1)';
                            this.style.boxShadow='none'"
               title="الجزء {{ $i }}">
            <span>{{ $i }}</span>
            <span style="font-size:9px;opacity:.5;margin-top:2px">جزء</span>
          </div>
          @endfor
        </div>
      </div>
    </div>

    {{-- Heatmap ────────────────────────────────────────── --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
    <img src="{{ asset('images/calendar.png') }}"
     alt="Idée"
     style="width:30px;height:30px;object-fit:contain;
            display:flex;align-items:center;">
                      <div>
            خريطة الالتزام السنوية
            <p style="font-size:11px;color:var(--text-m);font-weight:400;margin-top:1px">
              {{ now()->year }} — سجّل يومياً لبناء سلسلتك
            </p>
          </div>
        </div>
      </div>
      <div class="card-body" style="overflow-x:auto">
        {{-- أسماء الأيام --}}
        <div style="display:flex;gap:4px;margin-bottom:4px;
                    padding-right:24px">
          @foreach(['أح','إث','ثل','أر','خم','جم','سب'] as $d)
          <div style="width:12px;font-size:8px;color:var(--text-m);
                      text-align:center;flex-shrink:0">{{ $d }}</div>
          @endforeach
        </div>
        <div style="display:flex;gap:4px;min-width:max-content">
          {{-- الأشهر --}}
          @foreach(['يناير','فبراير','مارس','أبريل','مايو','يونيو',
                    'يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر']
                   as $idx => $month)
          <div>
            <div style="font-size:8px;color:var(--text-m);
                        margin-bottom:4px;text-align:center">
              {{ $month }}
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,12px);
                        gap:3px">
              @for($d = 0; $d < 28; $d++)
              <div style="width:12px;height:12px;border-radius:2px;
                          background:#e8f5ef;transition:background .15s;
                          cursor:pointer"
                   onmouseover="this.style.background='#059669'"
                   onmouseout="this.style.background='#e8f5ef'"
                   title="لا توجد قراءة"></div>
              @endfor
            </div>
          </div>
          @endforeach
        </div>
        <div style="display:flex;align-items:center;gap:8px;
                    margin-top:10px">
          <span style="font-size:11px;color:var(--text-m)">أقل</span>
          @foreach(['#d1fae5','#6ee7b7','#34d399','#059669'] as $c)
          <div style="width:12px;height:12px;border-radius:2px;
                      background:{{ $c }}"></div>
          @endforeach
          <span style="font-size:11px;color:var(--text-m)">أكثر</span>
        </div>
      </div>
    </div>

  </div>

  {{-- ── الشريط الجانبي (1/3) ── --}}
  <div style="display:flex;flex-direction:column;gap:20px">

    {{-- الورد اليومي --}}
    <div class="card">
      <div style="padding:18px 20px;
                  background:linear-gradient(135deg,#022c22,#064e3b);
                  display:flex;align-items:center;gap:12px">
        <span style="font-size:24px">🌙</span>
        <div>
          <p style="color:#fff;font-size:14px;font-weight:700">
            الورد اليومي
          </p>
          <p style="color:#6ee7b7;font-size:11px;margin-top:2px">
            {{ now()->locale('ar')->isoFormat('dddd، D MMM') }}
          </p>
        </div>
      </div>
      <div style="padding:24px;text-align:center">
        
        <p style="font-size:13px;color:var(--text-m);margin-bottom:14px">
          @if($todayWard?->is_completed)
            اكتمل ورد اليوم بحمد الله
          @elseif($todayWard)
            إنجاز اليوم: {{ $todayWard->adherence_pct }}٪
          @else
            لم تسجّل ورداً اليوم بعد
          @endif
        </p>
        <a href="{{ route('ward.index') }}"
           style="display:inline-flex;align-items:center;gap:7px;
                  padding:10px 22px;border-radius:10px;
                  background:linear-gradient(135deg,#0d6b52,#065f46);
                  color:#fff;font-size:13.5px;font-weight:700;
                  text-decoration:none;
                  box-shadow:0 4px 16px rgba(13,107,82,.35)">
          {{ $todayWard ? 'فتح الورد' : 'ابدأ الورد الآن' }}
          <svg width="15" height="15" fill="none" stroke="currentColor"
               stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>

    {{-- مراجعات اليوم --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
<img src="{{ asset('images/brain.png') }}"
     alt="Idée"
     style="width:24px;height:24px;object-fit:contain;
            display:flex;align-items:center;">          مراجعات اليوم
        </div>
        <span style="font-size:11px;padding:2px 9px;border-radius:100px;
                     background:#eff6ff;color:#1d4ed8;font-weight:700">
          ٠
        </span>
      </div>
      <div style="padding:28px;text-align:center">
        <p style="font-size:12.5px;color:var(--text-m)">
          لا توجد مراجعات مجدولة اليوم
        </p>
        <a href="{{ route('memorizations.index') }}"
           style="display:inline-block;margin-top:10px;
                  font-size:12.5px;color:#059669;font-weight:600;
                  text-decoration:none">
          + إضافة محفوظات
        </a>
      </div>
    </div>

    {{-- لوحة الشرف --}}
    <div class="card" style="border-color:#fde68a">
      <div style="padding:14px 18px;
                  background:linear-gradient(135deg,#fffbeb,#fef3c7);
                  display:flex;align-items:center;gap:10px;
                  border-bottom:1px solid #fde68a">
        <span style="font-size:20px">🏆</span>
        <p style="font-size:13.5px;font-weight:700;color:#78350f">
          لوحة الشرف
        </p>
      </div>
      <div style="padding:14px 16px">
        @foreach([
          ['🥇','#92400e','أحمد السيحي','١٢٠٠ نقطة'],
          ['🥈','#6b7280','محمد السيحي','٩٨٠ نقطة'],
          ['🥉','#92400e','فاطمة السيحي','٨٤٠ نقطة'],
        ] as [$medal,$mc,$name,$pts])
        <div style="display:flex;align-items:center;gap:12px;
                    padding:10px 6px;
                    border-bottom:1px solid var(--border)">
          <span style="font-size:20px">{{ $medal }}</span>
          <div style="flex:1;min-width:0">
            <p style="font-size:13px;font-weight:600;
                      color:var(--text);white-space:nowrap;
                      overflow:hidden;text-overflow:ellipsis">
              {{ $name }}
            </p>
            <p style="font-size:11px;color:var(--text-m)">{{ $pts }}</p>
          </div>
        </div>
        @endforeach
        <a href="{{ route('leaderboard') }}"
           style="display:block;margin-top:12px;text-align:center;
                  padding:9px;border-radius:10px;
                  background:#fffbeb;color:#92400e;
                  font-size:12px;font-weight:700;text-decoration:none;
                  border:1px solid #fde68a;
                  transition:background .18s"
           onmouseover="this.style.background='#fef3c7'"
           onmouseout="this.style.background='#fffbeb'">
          عرض الترتيب الكامل ←
        </a>
      </div>
    </div>

    {{-- روابط سريعة --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
<img src="{{ asset('images/link.png') }}"
     alt="Idée"
     style="width:24px;height:24px;object-fit:contain;
            display:flex;align-items:center;">          روابط سريعة
        </div>
      </div>
      <div style="padding:14px;
                  display:grid;grid-template-columns:1fr 1fr;gap:8px">
        @foreach([
          ['families.index','muslim.png','العائلات','#ecfdf5','#a7f3d0'],
          ['khatmas.create','quran.png','ختمة جديدة','#fffbeb','#fde68a'],
          ['memorizations.index','morajaa.png','الحفظ','#eff6ff','#bfdbfe'],
          ['revisions.index','brain.png','المراجعة','#fdf4ff','#e9d5ff'],
        ] as [$route,$icon,$label,$bg,$border])
        <a href="{{ route($route) }}"
           style="display:flex;flex-direction:column;
                  align-items:center;gap:7px;padding:14px 10px;
                  border-radius:12px;background:{{ $bg }};
                  border:1px solid {{ $border }};
                  text-decoration:none;transition:transform .18s,box-shadow .18s"
           onmouseover="this.style.transform='translateY(-2px)';
                        this.style.boxShadow='0 4px 14px rgba(0,0,0,.07)'"
           onmouseout="this.style.transform='translateY(0)';
                       this.style.boxShadow='none'">
          <img src="{{ asset('images/'.$icon) }}"
     alt="{{ $label }}"
     style="
        width:28px;
        height:28px;
        object-fit:contain;
        display:block;
        margin:0 auto;
     ">
          <span style="font-size:12px;font-weight:600;
                       color:var(--text)">{{ $label }}</span>
        </a>
        @endforeach
      </div>
    </div>

  </div>
</div>

{{-- ═══ آية قرآنية ═══ --}}
<div style="margin-top:28px;border-radius:18px;padding:36px;
            text-align:center;position:relative;overflow:hidden;
            background:linear-gradient(135deg,#031810,#042a1e)">
  <div style="position:absolute;inset:0;opacity:.05;
              background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E\");
              background-size:60px"></div>
  <p style="position:relative;font-family:'Amiri',serif;
            font-size:1.7rem;color:rgba(255,255,255,.90);
            line-height:2;margin-bottom:8px">
    ﴿ وَرَتِّلِ الْقُرْآنَ تَرْتِيلًا ﴾
  </p>
  <p style="position:relative;font-size:12.5px;color:#f59e0b;opacity:.80">
    سورة المزمل — الآية ٤
  </p>
</div>

@endsection
