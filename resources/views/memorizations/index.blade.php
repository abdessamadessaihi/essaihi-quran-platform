@extends('layouts.app')
@section('title', 'سجل الحفظ')

@section('content')

@php
  $masteryLabels = [
    'weak'      => 'ضعيف',
    'fair'      => 'متوسط',
    'good'      => 'جيد',
    'excellent' => 'ممتاز',
  ];
  $masteryColors = [
    'weak'      => ['bg'=>'#fef2f2','border'=>'#fecaca','text'=>'#991b1b','dot'=>'#ef4444'],
    'fair'      => ['bg'=>'#fffbeb','border'=>'#fde68a','text'=>'#92400e','dot'=>'#f59e0b'],
    'good'      => ['bg'=>'#eff6ff','border'=>'#bfdbfe','text'=>'#1e40af','dot'=>'#3b82f6'],
    'excellent' => ['bg'=>'#ecfdf5','border'=>'#a7f3d0','text'=>'#065f46','dot'=>'#10b981'],
  ];
  $surahs = \App\Models\DailyWard::SURAHS;
  $totalAll = $memorizations->total();
@endphp

{{-- ══ رأس الصفحة ══ --}}
<div class="mem-header">
  <div class="mem-ornament">
    <div class="mem-ornament-line"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة"
         style="width:60px;height:60px;object-fit:contain;opacity:.85"/>
    <div class="mem-ornament-line"></div>
  </div>
  <div class="mem-header-content">
    <div>
      <h1 class="mem-title">سجل الحفظ</h1>
      <p class="mem-subtitle">
        تابع حفظك للقرآن الكريم آية بآية وسورة بسورة، وقيّم مستوى إتقانك
      </p>
    </div>
    <a href="{{ route('memorizations.create') }}" class="mem-btn-primary">
      <svg width="15" height="15" fill="none" stroke="currentColor"
           stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      إضافة محفوظ جديد
    </a>
  </div>
</div>

{{-- ══ بطاقات الإحصاء ══ --}}
<div class="mem-stats">

  <div class="mem-stat">
    <img src="{{ asset('images/quran.png') }}" alt="القرآن"
         style="width:38px;height:38px;object-fit:contain">
    <div>
      <p class="mem-stat-value">{{ number_format($totalAyahs) }}</p>
      <p class="mem-stat-label">آية محفوظة</p>
      <p class="mem-stat-sub">إجمالي الآيات المسجّلة</p>
    </div>
  </div>

  <div class="mem-stat">
    <img src="{{ asset('images/injaz.png') }}" alt="إنجاز"
         style="width:38px;height:38px;object-fit:contain">
    <div>
      <p class="mem-stat-value">{{ $totalSurahs }}</p>
      <p class="mem-stat-label">سورة مسجّلة</p>
      <p class="mem-stat-sub">{{ $totalMemorizations }} سجل حفظ</p>
    </div>
  </div>

  <div class="mem-stat" style="border-color:#a7f3d0">
    <img src="{{ asset('images/target2.png') }}" alt="هدف"
         style="width:38px;height:38px;object-fit:contain">
    <div>
      <p class="mem-stat-value" style="color:#059669">{{ $excellentCount }}</p>
      <p class="mem-stat-label">إتقان ممتاز</p>
      <p class="mem-stat-sub">
        @if($totalAll > 0)
          {{ round(($excellentCount / $totalAll) * 100) }}٪ من المحفوظات
        @else
          لا توجد سجلات بعد
        @endif
      </p>
    </div>
  </div>

  <div class="mem-stat" style="border-color:#fde68a">
    <img src="{{ asset('images/brain.png') }}" alt="مراجعة"
         style="width:38px;height:38px;object-fit:contain">
    <div>
      <p class="mem-stat-value" style="color:#d97706">{{ $pendingReviewCount }}</p>
      <p class="mem-stat-label">تحتاج مراجعة</p>
      <p class="mem-stat-sub">
        <a href="{{ route('revisions.index') }}"
           style="color:#d97706;text-decoration:none;font-weight:600">
          عرض المراجعات ←
        </a>
      </p>
    </div>
  </div>

</div>

{{-- ══ Layout رئيسي ══ --}}
<div class="mem-layout">

  {{-- ── العمود الرئيسي ── --}}
  <div class="mem-main">

    {{-- ══ سجلات الحفظ ══ --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <img src="{{ asset('images/injaz.png') }}" alt="حفظ"
               style="width:32px;height:32px;object-fit:contain">
          <div>
            سجلات الحفظ
            <p style="font-size:11px;color:var(--text-m);font-weight:400;margin-top:1px">
              {{ $totalAll }} سجل إجمالاً
            </p>
          </div>
        </div>
        <a href="{{ route('memorizations.create') }}"
           style="display:inline-flex;align-items:center;gap:6px;
                  padding:8px 16px;border-radius:10px;
                  background:linear-gradient(135deg,#0d6b52,#064e3b);
                  color:#fff;font-size:12.5px;font-weight:700;
                  text-decoration:none;transition:transform .18s"
           onmouseover="this.style.transform='translateY(-1px)'"
           onmouseout="this.style.transform='translateY(0)'">
          <svg width="13" height="13" fill="none" stroke="currentColor"
               stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"/>
          </svg>
          إضافة
        </a>
      </div>

      @if($memorizations->count())
      
      {{-- [1] حاوية الكمبيوتر: تظهر على الشاشات الكبيرة فقط --}}
      <div class="desktop-table-container">
        <table class="mem-table">
          <thead>
            <tr>
              <th>السورة</th>
              <th>الآيات</th>
              <th>عدد الآيات</th>
              <th>مستوى الإتقان</th>
              <th>تاريخ الحفظ</th>
              <th>آخر مراجعة</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($memorizations as $mem)
            @php
              $color = $masteryColors[$mem->mastery_level] ?? $masteryColors['fair'];
              $surahName = $surahs[$mem->surah_number] ?? 'سورة '.$mem->surah_number;
            @endphp
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="mem-surah-num">{{ $mem->surah_number }}</div>
                  <div>
                    <p style="font-weight:700;font-size:13.5px;color:var(--text)">
                      {{ $surahName }}
                    </p>
                    <p style="font-size:11px;color:var(--text-m)">
                      سورة رقم {{ $mem->surah_number }}
                    </p>
                  </div>
                </div>
              </td>
              <td>
                <span class="mem-ayah-badge">
                  {{ $mem->ayah_from }} — {{ $mem->ayah_to }}
                </span>
              </td>
              <td>
                <span style="font-family:'Amiri',serif;font-size:1.15rem;
                             font-weight:700;color:var(--text)">
                  {{ $mem->ayah_count }}
                </span>
                <span style="font-size:11px;color:var(--text-m);margin-right:3px">آية</span>
              </td>
              <td>
                <span class="mem-mastery-badge"
                      style="background:{{ $color['bg'] }};
                             color:{{ $color['text'] }};
                             border-color:{{ $color['border'] }}">
                  <span class="mem-mastery-dot"
                        style="background:{{ $color['dot'] }}"></span>
                  {{ $masteryLabels[$mem->mastery_level] ?? $mem->mastery_level }}
                </span>
              </td>
              <td>
                <span style="font-size:13px;font-weight:600;color:var(--text)">
                  {{ $mem->memorized_at->locale('ar')->isoFormat('D MMM YYYY') }}
                </span>
              </td>
              <td>
                @if($mem->last_reviewed_at)
                  <span style="font-size:12px;color:var(--text-m)">
                    {{ $mem->last_reviewed_at->locale('ar')->diffForHumans() }}
                  </span>
                @else
                  <span style="font-size:12px;color:#ef4444;font-weight:600">
                    لم تُراجع بعد
                  </span>
                @endif
              </td>
              <td>
                <form method="POST"
                      action="{{ route('memorizations.destroy', $mem) }}"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                  @csrf @method('DELETE')
                  <button type="submit" class="mem-del-btn" title="حذف">
                    <svg width="13" height="13" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                      <path d="M3 6h18M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4h6v2"/>
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- [2] حاوية الهاتف الذكية: تظهر على الهواتف بشكل بطاقات مريحة وتمنع تمزق التصميم --}}
      <div class="mobile-cards-container">
        @foreach($memorizations as $mem)
        @php
          $color = $masteryColors[$mem->mastery_level] ?? $masteryColors['fair'];
          $surahName = $surahs[$mem->surah_number] ?? 'سورة '.$mem->surah_number;
        @endphp
        <div class="m-mem-card">
          <div class="m-mem-card-header">
            <div style="display:flex;align-items:center;gap:10px">
              <div class="mem-surah-num">{{ $mem->surah_number }}</div>
              <div>
                <h4 style="margin:0;font-size:14px;font-weight:700;color:var(--text)">{{ $surahName }}</h4>
                <span style="font-size:11px;color:var(--text-m)">{{ $mem->memorized_at->locale('ar')->isoFormat('D MMMM YYYY') }}</span>
              </div>
            </div>
            <span class="mem-mastery-badge"
                  style="background:{{ $color['bg'] }};
                         color:{{ $color['text'] }};
                         border-color:{{ $color['border'] }}">
              <span class="mem-mastery-dot" style="background:{{ $color['dot'] }}"></span>
              {{ $masteryLabels[$mem->mastery_level] ?? $mem->mastery_level }}
            </span>
          </div>
          
          <div class="m-mem-card-body">
            <div class="m-mem-info-pill">
              <span class="m-mem-label">الآيات:</span>
              <span class="mem-ayah-badge" style="margin:0">{{ $mem->ayah_from }} — {{ $mem->ayah_to }}</span>
            </div>
            <div class="m-mem-info-pill">
              <span class="m-mem-label">العدد:</span>
              <strong>{{ $mem->ayah_count }} آية</strong>
            </div>
          </div>

          <div class="m-mem-card-footer">
            <span style="font-size:11.5px;color:var(--text-m)">
              🔄 آخر مراجعة: 
              @if($mem->last_reviewed_at)
                <strong style="color:var(--text)">{{ $mem->last_reviewed_at->locale('ar')->diffForHumans() }}</strong>
              @else
                <strong style="color:#ef4444">لم تُراجع بعد</strong>
              @endif
            </span>
            <form method="POST" action="{{ route('memorizations.destroy', $mem) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
              @csrf @method('DELETE')
              <button type="submit" class="mem-del-btn" style="width:32px;height:32px">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M3 6h18M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4h6v2"/>
                </svg>
              </button>
            </form>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($memorizations->hasPages())
      <div style="padding:16px 22px;border-top:1px solid var(--border)">
        {{ $memorizations->links() }}
      </div>
      @endif

      @else
      {{-- حالة فارغة --}}
      <div class="mem-empty">
        <img src="{{ asset('images/quran.png') }}" alt="قرآن"
             style="width:80px;height:80px;object-fit:contain;
                    opacity:.45;margin-bottom:20px;
                    filter:drop-shadow(0 4px 8px rgba(0,0,0,.1))">
        <p style="font-family:'Amiri',serif;font-size:1.4rem;font-weight:700;
                  color:var(--text);margin-bottom:8px">
          لا توجد سجلات حفظ بعد
        </p>
        <p style="font-size:13.5px;color:var(--text-m);margin-bottom:28px;
                  line-height:1.7">
          ابدأ بتسجيل أول محفوظاتك من القرآن الكريم<br/>
          <span style="font-size:12px;opacity:.7">
            كل آية تُسجّلها هي خطوة نحو بيت قرآني
          </span>
        </p>
        <a href="{{ route('memorizations.create') }}" class="mem-btn-primary">
          <svg width="15" height="15" fill="none" stroke="currentColor"
               stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"/>
          </svg>
          تسجيل أول محفوظ
        </a>
      </div>
      @endif
    </div>

    {{-- ══ تصنيف مستوى الإتقان ══ --}}
    @if($totalAll > 0)
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon green">📊</div>
          توزيع مستويات الإتقان
        </div>
      </div>
      <div class="card-body">
        <div class="mastery-distribution-grid">
          @foreach($masteryLabels as $key => $label)
          @php
            $count = $masteryDistribution[$key] ?? 0;
            $pct   = $totalAll > 0 ? round(($count / $totalAll) * 100) : 0;
            $c     = $masteryColors[$key];
          @endphp
          <div style="text-align:center;padding:18px 12px;border-radius:14px;
                      background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }}">
            <p style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
                      color:{{ $c['dot'] }};line-height:1;margin-bottom:6px">
              {{ $count }}
            </p>
            <p style="font-size:12.5px;font-weight:700;color:{{ $c['text'] }};
                      margin-bottom:2px">
              {{ $label }}
            </p>
            <p style="font-size:11px;color:{{ $c['dot'] }};opacity:.8">
              {{ $pct }}٪
            </p>
            {{-- شريط --}}
            <div style="margin-top:10px;height:4px;border-radius:100px;
                        background:rgba(0,0,0,.06);overflow:hidden">
              <div style="height:100%;border-radius:100px;
                          background:{{ $c['dot'] }};width:{{ $pct }}%;
                          transition:width .6s ease"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

  </div>

  {{-- ── الشريط الجانبي ── --}}
  <aside class="mem-side">

    {{-- نصيحة قرآنية --}}
    <div class="card">
      <div style="padding:16px 20px;
                  background:linear-gradient(135deg,#022c22,#064e3b);
                  display:flex;align-items:center;gap:10px">
        <img src="{{ asset('images/idea.png') }}" alt="نصيحة"
             style="width:28px;height:28px;object-fit:contain">
        <p style="color:#fff;font-size:13.5px;font-weight:700">
          نصيحة للحافظ
        </p>
      </div>
      <div style="padding:18px 20px">
        <p style="font-size:13px;color:var(--text-m);line-height:2;font-style:italic">
          "المداومة على تلاوة القرآن وتكراره هي أقوى وسيلة لتثبيته في الذاكرة.
          سجّل ما حفظت، وراجعه بانتظام، فالحفظ بلا مراجعة كالماء بلا إناء."
        </p>
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
          <p style="font-family:'Amiri',serif;font-size:1.15rem;
                    color:var(--text);text-align:center;line-height:2">
            ﴿ إِنَّ هَذَا الْقُرْآنَ يَهْدِي لِلَّتِي هِيَ أَقْوَمُ ﴾
          </p>
          <p style="font-size:11px;color:var(--text-m);text-align:center;margin-top:4px">
            سورة الإسراء — الآية ٩
          </p>
        </div>
      </div>
    </div>

    {{-- مستويات الإتقان شرح --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon gold">🏅</div>
          مستويات الإتقان
        </div>
      </div>
      <div style="padding:16px 20px;display:flex;flex-direction:column;gap:10px">
        @foreach($masteryColors as $key => $c)
        <div style="display:flex;align-items:center;gap:12px;
                    padding:10px 14px;border-radius:10px;
                    background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }}">
          <span style="width:10px;height:10px;border-radius:50%;
                       background:{{ $c['dot'] }};flex-shrink:0"></span>
          <div>
            <p style="font-size:12.5px;font-weight:700;color:{{ $c['text'] }}">
              {{ $masteryLabels[$key] ?? $key }}
            </p>
            <p style="font-size:11px;color:var(--text-m);margin-top:1px">
              @switch($key)
                @case('weak')    يحتاج مزيداً من التكرار @break
                @case('fair')    إتقان متوسط — استمر في المراجعة @break
                @case('good')    إتقان جيد — راجع بانتظام @break
                @case('excellent') إتقان تام — حافظ عليه @break
              @endswitch
            </p>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    {{-- روابط سريعة --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon blue">🔗</div>
          روابط سريعة
        </div>
      </div>
      <div style="padding:12px">
        <a href="{{ route('memorizations.create') }}"
           style="display:flex;align-items:center;gap:10px;
                  padding:11px 14px;border-radius:10px;
                  color:var(--text);text-decoration:none;
                  transition:background .15s;font-size:13px;font-weight:600"
           onmouseover="this.style.background='var(--bg)'"
           onmouseout="this.style.background='transparent'">
          <span>➕</span> تسجيل محفوظ جديد
        </a>
        <a href="{{ route('revisions.index') }}"
           style="display:flex;align-items:center;gap:10px;
                  padding:11px 14px;border-radius:10px;
                  color:var(--text);text-decoration:none;
                  transition:background .15s;font-size:13px;font-weight:600"
           onmouseover="this.style.background='var(--bg)'"
           onmouseout="this.style.background='transparent'">
          <span>🔄</span> جدول المراجعة
          @if($pendingReviewCount > 0)
            <span style="margin-right:auto;font-size:10px;font-weight:700;
                         padding:2px 8px;border-radius:100px;
                         background:rgba(239,68,68,.15);color:#ef4444">
              {{ $pendingReviewCount }}
            </span>
          @endif
        </a>
        <a href="{{ route('ward.index') }}"
           style="display:flex;align-items:center;gap:10px;
                  padding:11px 14px;border-radius:10px;
                  color:var(--text);text-decoration:none;
                  transition:background .15s;font-size:13px;font-weight:600"
           onmouseover="this.style.background='var(--bg)'"
           onmouseout="this.style.background='transparent'">
          <span>📖</span> الورد اليومي
        </a>
      </div>
    </div>

  </aside>

</div>

@endsection

@push('styles')
<style>
/* ══ Header ══════════════════════════════════════════════ */
.mem-header { margin-bottom: 28px; }
.mem-ornament {
  display: flex; align-items: center;
  gap: 14px; margin-bottom: 16px;
}
.mem-ornament-line {
  flex: 1; height: 1px;
  background: linear-gradient(to left, transparent, #a7f3d0);
}
.mem-ornament-line:last-child {
  background: linear-gradient(to right, transparent, #a7f3d0);
}
.mem-header-content {
  display: flex; justify-content: space-between;
  align-items: flex-start; gap: 18px; flex-wrap: wrap;
}
.mem-title {
  font-family: 'Amiri', serif;
  font-size: 1.95rem; font-weight: 700;
  color: var(--text); margin-bottom: 6px;
}
.mem-subtitle { color: var(--text-m); font-size: 13px; line-height: 1.7; }

/* ══ Stats ═══════════════════════════════════════════════ */
.mem-stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px; margin-bottom: 22px;
}
.mem-stat {
  display: flex; align-items: center; gap: 14px;
  padding: 18px; background: var(--card);
  border: 1px solid var(--border); border-radius: 16px;
  transition: transform .2s, box-shadow .2s;
}
.mem-stat:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 24px rgba(0,0,0,.06);
}
.mem-stat-value {
  font-family: 'Amiri', serif;
  font-size: 1.8rem; font-weight: 700;
  color: var(--text); line-height: 1;
}
.mem-stat-label { font-size: 13px; font-weight: 700; color: var(--text); margin-top: 4px; }
.mem-stat-sub { font-size: 11px; color: var(--text-m); margin-top: 2px; }

/* ══ Layout ══════════════════════════════════════════════ */
.mem-layout {
  display: grid;
  grid-template-columns: minmax(0, 2fr) 300px;
  gap: 22px; align-items: start;
}
.mem-main, .mem-side {
  display: flex; flex-direction: column; gap: 18px;
}

/* ══ التجاوب والتحكم في عرض الجداول ═════════════════════ */
.desktop-table-container { display: block; overflow-x: auto; }
.mobile-cards-container { display: none; padding: 4px 16px 16px 16px; }
.mastery-distribution-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }

.mem-table {
  width: 100%; border-collapse: collapse;
  font-size: 13px;
}
.mem-table thead { background: var(--bg); }
.mem-table th {
  padding: 12px 18px;
  text-align: right; font-size: 12px;
  font-weight: 700; color: var(--text-m);
  letter-spacing: .3px;
  border-bottom: 1px solid var(--border);
}
.mem-table td {
  padding: 14px 18px;
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}
.mem-table tbody tr:last-child td { border-bottom: none; }
.mem-table tbody tr:hover { background: var(--bg); }

/* ══ بطاقات الحفظ للهاتف ════════════════════════════════ */
.m-mem-card {
  background: var(--card); border: 1px solid var(--border); border-radius: 14px;
  padding: 14px; margin-bottom: 12px; display: flex; flex-direction: column; gap: 12px;
}
.m-mem-card-header { display: flex; justify-content: space-between; align-items: center; }
.m-mem-card-body { display: flex; gap: 10px; background: var(--bg); padding: 10px; border-radius: 10px; border: 1px solid var(--border); }
.m-mem-info-pill { flex: 1; display: flex; flex-direction: column; gap: 4px; font-size: 12.5px; }
.m-mem-label { font-size: 11px; color: var(--text-m); }
.m-mem-card-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 10px; }

/* ══ Badges ══════════════════════════════════════════════ */
.mem-surah-num {
  width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Amiri', serif; font-size: 1rem; font-weight: 700;
  color: #fff;
  background: linear-gradient(135deg, #064e3b, #0d6b52);
}
.mem-ayah-badge {
  display: inline-flex; align-items: center;
  padding: 4px 10px; border-radius: 8px;
  font-size: 12px; font-weight: 700;
  background: var(--bg); border: 1px solid var(--border);
  color: var(--text); font-family: 'Amiri', serif;
  letter-spacing: .5px;
}
.mem-mastery-badge {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 4px 10px; border-radius: 100px;
  font-size: 12px; font-weight: 700;
  border: 1px solid;
}
.mem-mastery-dot {
  width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
}

/* ══ Delete button ═══════════════════════════════════════ */
.mem-del-btn {
  display: flex; align-items: center; justify-content: center;
  width: 30px; height: 30px; border-radius: 8px;
  border: 1px solid var(--border); background: var(--bg);
  color: var(--text-m); cursor: pointer; transition: all .15s;
}
.mem-del-btn:hover {
  background: #fee2e2; border-color: #fca5a5; color: #ef4444;
}

/* ══ Primary button ══════════════════════════════════════ */
.mem-btn-primary {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 11px 22px; border-radius: 12px;
  background: linear-gradient(135deg, #0d6b52, #064e3b);
  color: #fff; font-size: 13.5px; font-weight: 700;
  text-decoration: none;
  box-shadow: 0 4px 16px rgba(13,107,82,.3);
  transition: transform .18s, box-shadow .18s;
  white-space: nowrap;
}
.mem-btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 22px rgba(13,107,82,.4);
}

/* ══ Empty state ═════════════════════════════════════════ */
.mem-empty {
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  padding: 64px 40px; text-align: center;
}

/* ══ شاشات الهواتف والتابلت (Responsive Setup) ═══════════ */
@media (max-width: 1200px) {
  .mem-stats { grid-template-columns: repeat(2, 1fr); }
  .mastery-distribution-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 1024px) {
  .mem-layout { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  /* الحماية السحرية: إخفاء الجدول وإظهار بطاقات الهاتف */
  .desktop-table-container { display: none !important; }
  .mobile-cards-container { display: block !important; }
  
  .mem-stats { grid-template-columns: 1fr; gap: 10px; }
  .mastery-distribution-grid { grid-template-columns: 1fr; }
  .mem-header-content { flex-direction: column; align-items: stretch; text-align: center; gap: 14px; }
  .mem-btn-primary { justify-content: center; }
  .mem-title { font-size: 1.6rem; }
}
</style>
@endpush