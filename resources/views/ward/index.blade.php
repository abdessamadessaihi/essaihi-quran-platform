@extends('layouts.app')
@section('title', 'الورد اليومي')

@section('content')
@php
  $unitLabels     = \App\Models\DailyWard::UNIT_LABELS;
  $locationLabels = \App\Models\DailyWard::LOCATION_LABELS;
  $surahs         = \App\Models\DailyWard::SURAHS;
  $todayProgress  = $wardToday?->adherence_pct ?? 0;
  $completedCount = $allWards->getCollection()->where('is_completed', true)->count();
  $totalCount     = $allWards->getCollection()->count();
  $completionRate = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
@endphp

{{-- ══ Page Header ══ --}}
<div class="ward-header">
  <div class="ward-ornament">
    <div class="ward-ornament-line"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة"
         style="width:64px;height:64px;object-fit:contain;opacity:.85"/>
    <div class="ward-ornament-line"></div>
  </div>
  <div class="ward-header-content">
    <div>
      <h1 class="ward-title">الورد اليومي</h1>
      <p class="ward-subtitle">
        خطط وردك، سجّل موضع القراءة، وتابع إنجازك اليومي بهدوء وانتظام
      </p>
    </div>
    <span class="ward-date-pill">
      {{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
    </span>
  </div>
</div>

{{-- ══ بطاقات الإحصاء ══ --}}
<div class="ward-stats">

  <div class="ward-stat">
    <img src="{{ asset('images/target2.png') }}" alt="Idée" style="width:30px;height:30px;object-fit:contain">    
    <div>
      <p class="ward-stat-value">
        @if($wardToday)
          @if($wardToday->target_unit === 'pages')
            الصفحات من {{ $wardToday->start_page }} إلى {{ $wardToday->end_page }}
          @elseif($wardToday->target_unit === 'surahs')
            سورة {{ $surahs[$wardToday->start_surah] ?? $wardToday->start_surah }}
          @elseif($wardToday->target_unit === 'hizbs')
            الحزب {{ $wardToday->start_hizb ?? $wardToday->specific_hizb }}
          @elseif($wardToday->target_unit === 'juzs')
            الجزء {{ $wardToday->start_juz ?? $wardToday->specific_juz }}
          @elseif($wardToday->target_unit === 'ayahs')
            ورد مستقل ({{ (int)$wardToday->target_value }} آيات)
          @endif
        @else
          —
        @endif
      </p>
      <p class="ward-stat-label">هدف اليوم</p>
      <p class="ward-stat-sub">
        @if($wardToday)
          @if($wardToday->target_unit === 'surahs') سورة كاملة
          @elseif($wardToday->target_unit === 'hizbs') الحزب الحالي
          @elseif($wardToday->target_unit === 'juzs') الجزء الحالي
          @else صفحات ومواضع @endif
        @else
          لم يحدد بعد
        @endif
      </p>
    </div>
  </div>

  <div class="ward-stat">
    <img src="{{ asset('images/injaz.png') }}" alt="Idée" style="width:30px;height:30px;object-fit:contain">    
    <div>
      <p class="ward-stat-value">{{ $todayProgress }}٪</p>
      <p class="ward-stat-label">إنجاز اليوم</p>
      <p class="ward-stat-sub">
        {{ $wardToday?->is_completed ? 'مكتمل بحمد الله ✅' : 'قيد المتابعة' }}
      </p>
    </div>
  </div>

  <div class="ward-stat">
    <img src="{{ asset('images/day.png') }}" alt="Idée" style="width:30px;height:30px;object-fit:contain">    
    <div>
      <p class="ward-stat-value">{{ $streak->current_streak }}</p>
      <p class="ward-stat-label">أيام متتالية</p>
      <p class="ward-stat-sub">الأطول: {{ $streak->longest_streak }} يوم</p>
    </div>
  </div>

  <div class="ward-stat">
    <img src="{{ asset('images/target2.png') }}" alt="Idée" style="width:30px;height:30px;object-fit:contain">    
    <div>
      <p class="ward-stat-value">
        {{ $completedCount }} / {{ $totalCount }}
      </p>
      <p class="ward-stat-label">معدل الإنجاز العام</p>
      <p class="ward-stat-sub">نسبة النجاح الكلية: {{ $completionRate }}٪</p>
    </div>
  </div>

</div>

{{-- ══ Layout رئيسي ══ --}}
<div class="ward-layout">

  {{-- ── العمود الرئيسي ── --}}
  <div class="ward-main">

    @if($wardToday)
    {{-- ══ ورد اليوم الحالي ══ --}}
    <div class="card ward-today-card">
      <div class="card-header">
        <div class="card-header-title">
          <img src="{{ asset('images/wird.png') }}" alt="Idée" style="width:30px;height:30px;object-fit:contain">          
          <div>
            ورد اليوم الحالي
            <p class="ward-card-note">
              {{ $wardToday->ward_date->locale('ar')->isoFormat('dddd، D MMMM') }}
            </p>
          </div>
        </div>
        <span class="ward-status {{ $wardToday->is_completed ? 'completed' : 'pending' }}">
          {{ $wardToday->is_completed ? '✅ مكتمل' : '⏳ جارٍ' }}
        </span>
      </div>

      <div class="card-body">
        {{-- شريط التقدم --}}
        <div class="ward-progress-head">
          <div>
            <p class="ward-progress-title">
              @if($wardToday->target_unit === 'pages')
                تمت قراءة {{ rtrim(rtrim($wardToday->actual_value,'0'),'.') }} صفحات
              @elseif($wardToday->target_unit === 'surahs' && isset($surahs[$wardToday->start_surah ?? $wardToday->specific_surah]))
                سورة {{ $surahs[$wardToday->start_surah ?? $wardToday->specific_surah] }}
              @elseif($wardToday->target_unit === 'hizbs')
                الحزب {{ $wardToday->start_hizb ?? $wardToday->specific_hizb }}
              @elseif($wardToday->target_unit === 'juzs')
                الجزء {{ $wardToday->start_juz ?? $wardToday->specific_juz }}
              @else
                {{ rtrim(rtrim($wardToday->actual_value,'0'),'.') }} من {{ rtrim(rtrim($wardToday->target_value,'0'),'.') }}
              @endif
            </p>
            <p class="ward-progress-sub">
              @if($wardToday->khatma)
                مرتبط بختمة: <strong>{{ $wardToday->khatma->title }}</strong>
              @else
                ورد مستقل
              @endif
            </p>
          </div>
          <span class="ward-progress-number">{{ $todayProgress }}٪</span>
        </div>

        <div class="ward-progress-bar">
          <div class="ward-progress-fill" style="width:{{ $todayProgress }}%"></div>
        </div>

        {{-- الموضع المحدث بناءً على اختيار المدخلات الفعلي --}}
        <div class="ward-location-box">
          <span class="ward-location-label">📍 الموضع الحالي</span>
          <span class="ward-location-value">
            @if($wardToday->target_unit === 'pages' && $wardToday->start_page && $wardToday->end_page)
              من صفحة {{ $wardToday->start_page }} إلى {{ $wardToday->end_page }}
            @elseif($wardToday->target_unit === 'surahs' && isset($surahs[$wardToday->start_surah ?? $wardToday->specific_surah]))
              سورة {{ $surahs[$wardToday->start_surah ?? $wardToday->specific_surah] }} كاملة
            @elseif($wardToday->target_unit === 'hizbs')
              الحزب رقم {{ $wardToday->start_hizb ?? $wardToday->specific_hizb }}
            @elseif($wardToday->target_unit === 'juzs')
              الجزء رقم {{ $wardToday->start_juz ?? $wardToday->specific_juz }}
            @else
              الموضع المحدد للورد اليومي
            @endif
          </span>
        </div>

        {{-- الملاحظات --}}
        @if($wardToday->notes)
        <p class="ward-notes">
          <span style="opacity:.6">📝</span> {{ $wardToday->notes }}
        </p>
        @endif

        {{-- الأزرار --}}
        <div class="ward-actions">
          @unless($wardToday->is_completed)
          <form method="POST" action="{{ route('ward.complete') }}" class="ward-action-form">
            @csrf
            <button type="submit" class="ward-btn primary wide">
              ✓ اعتماد إكمال الورد
            </button>
          </form>
          @endunless

          <form method="POST" action="{{ route('ward.update', $wardToday) }}" class="ward-update-form">
            @csrf @method('PATCH')
            <input name="actual_value" type="number" min="0" step="0.01"
                   value="{{ old('actual_value', rtrim(rtrim($wardToday->actual_value,'0'),'.')) }}"
                   placeholder="المنجز فعلياً"/>
            <button type="submit" class="ward-btn secondary">تحديث</button>
          </form>
        </div>
      </div>
    </div>
    @else
    {{-- ══ إضافة ورد اليوم الجديد والمنظم ══ --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon green">➕</div>
          إضافة ورد اليوم
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('ward.store') }}" x-data="{ target_unit: '{{ old('target_unit', '') }}' }">
          @csrf

          {{-- 🎯 قسم تحديد الهدف والموضع المدمج --}}
          <div class="ward-form-section-title">🎯 تحديد الهدف والموضع</div>
          
          <div class="ward-field" style="margin-bottom:16px">
            <label>نوع الهدف والموضع *</label>
            <select name="target_unit" x-model="target_unit" required>
              <option value="">اختر نوع الهدف والتقسيم...</option>
              <option value="pages">بالصفحات (١ — ٦٠٤)</option>
              <option value="surahs">بالسور</option>
              <option value="hizbs">بالأحزاب (١ — ٦٠)</option>
              <option value="juzs">بالأجزاء (١ — ٣٠)</option>
            </select>
            @error('target_unit') <p class="ward-error">{{ $message }}</p> @enderror
          </div>

          {{-- 1. حقول الصفحات --}}
          <div x-show="target_unit === 'pages'" x-transition style="display:none; margin-bottom:16px;">
            <div class="ward-form-grid">
              <div class="ward-field">
                <label>من صفحة *</label>
                <select name="start_page" :required="target_unit === 'pages'">
                  <option value="">اختر الصفحة</option>
                  @foreach(range(1, 604) as $page)
                    <option value="{{ $page }}" @selected(old('start_page') == $page)>الصفحة {{ $page }}</option>
                  @endforeach
                </select>
              </div>
              <div class="ward-field">
                <label>إلى صفحة *</label>
                <select name="end_page" :required="target_unit === 'pages'">
                  <option value="">اختر الصفحة</option>
                  @foreach(range(1, 604) as $page)
                    <option value="{{ $page }}" @selected(old('end_page') == $page)>الصفحة {{ $page }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          {{-- 2. حقل السور --}}
          <div x-show="target_unit === 'surahs'" x-transition style="display:none; margin-bottom:16px;" class="ward-field">
            <label>السورة المستهدفة *</label>
            <select name="specific_surah" :required="target_unit === 'surahs'">
              <option value="">اختر السورة من القائمة</option>
              @foreach($surahs as $num => $name)
                <option value="{{ $num }}" @selected(old('specific_surah') == $num)>{{ $num }} — {{ $name }}</option>
              @endforeach
            </select>
          </div>

          {{-- 3. حقل الأحزاب --}}
          <div x-show="target_unit === 'hizbs'" x-transition style="display:none; margin-bottom:16px;" class="ward-field">
            <label>الحزب المستهدف *</label>
            <select name="specific_hizb" :required="target_unit === 'hizbs'">
              <option value="">اختر الحزب</option>
              @foreach(range(1, 60) as $hizb)
                <option value="{{ $hizb }}" @selected(old('specific_hizb') == $hizb)>الحزب {{ $hizb }}</option>
              @endforeach
            </select>
          </div>

          {{-- 4. حقل الأجزاء --}}
          <div x-show="target_unit === 'juzs'" x-transition style="display:none; margin-bottom:16px;" class="ward-field">
            <label>الجزء المستهدف *</label>
            <select name="specific_juz" :required="target_unit === 'juzs'">
              <option value="">اختر الجزء من القرآن</option>
              @foreach(range(1, 30) as $juz)
                <option value="{{ $juz }}" @selected(old('specific_juz') == $juz)>الجزء {{ $juz }}</option>
              @endforeach
            </select>
          </div>

          {{-- حقول التوقيت والارتباط --}}
          <div class="ward-form-grid">
            <div class="ward-field">
              <label>التاريخ *</label>
              <input name="ward_date" type="date" dir="ltr" value="{{ old('ward_date', today()->toDateString()) }}" required/>
              @error('ward_date') <p class="ward-error">{{ $message }}</p> @enderror
            </div>
            <div class="ward-field">
              <label>الختمة المرتبطة (اختياري)</label>
              <select name="khatma_id">
                <option value="">ورد مستقل غير تابع لختمة</option>
                @foreach($khatmas as $khatma)
                <option value="{{ $khatma->id }}" @selected(old('khatma_id') == $khatma->id)>
                  {{ $khatma->title }}
                </option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- ملاحظات --}}
          <div class="ward-field" style="margin-top:16px">
            <label>ملاحظات إضافية</label>
            <textarea name="notes" rows="3" maxlength="500" placeholder="اكتب تدوينتك أو ملاحظاتك حول قراءة اليوم هنا..."></textarea>
            @error('notes') <p class="ward-error">{{ $message }}</p> @enderror
          </div>

          <button type="submit" class="ward-btn primary wide" style="margin-top:20px">حفظ وجدولة ورد اليوم</button>
        </form>
      </div>
    </div>
    @endif

    {{-- ══ سجل الأوراد التاريخي المعزول ══ --}}
    <div class="card" style="margin-top:20px">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon blue">🗂️</div>
          سجل الأوراد التاريخي
        </div>
        <span style="font-size:12px;color:var(--text-m)">{{ $allWards->total() }} سجل</span>
      </div>
      <div class="card-body" style="padding:0">

        @if($allWards->count())
        
        {{-- نسخة الكمبيوتر --}}
        <div class="desktop-table-wrapper">
          <table class="ward-table">
            <thead>
              <tr>
                <th style="width: 15%;">التاريخ</th>
                <th style="width: 30%;">الهدف / الموضع</th>
                <th style="width: 15%;">المنجز</th>
                <th style="width: 20%;">نسبة الإنجاز</th>
                <th style="width: 10%;">الحالة</th>
                <th style="width: 10%; text-align: center;">إجراءات</th>
              </tr>
            </thead>
            <tbody>
              @foreach($allWards as $ward)
              <tr>
                <td>
                  <span style="font-weight:600">{{ $ward->ward_date->locale('ar')->isoFormat('D MMM') }}</span>
                  <br>
                  <span style="font-size:11px;color:var(--text-m)">{{ $ward->ward_date->format('Y') }}</span>
                </td>
                <td>
                  <span class="ward-table-badge">
                    @if($ward->target_unit === 'pages')
                        الصفحات: من {{ $ward->start_page }} إلى {{ $ward->end_page }}
                    @elseif($ward->target_unit === 'surahs')
                        سورة: {{ $surahs[$ward->start_surah] ?? $ward->start_surah }}
                    @elseif($ward->target_unit === 'hizbs')
                        الحزب رقم: {{ $ward->start_hizb ?? $ward->specific_hizb }}
                    @elseif($ward->target_unit === 'juzs')
                        الجزء رقم: {{ $ward->start_juz ?? $ward->specific_juz }}
                    @elseif($ward->target_unit === 'ayahs')
                        {{ (int)$ward->target_value }} آيات
                    @endif
                  </span>
                </td>
                <td>
                    <strong>
                      @if($ward->target_unit === 'pages')
                          {{ (int)$ward->actual_value }} صفحة
                      @elseif($ward->target_unit === 'surahs')
                          {{ (int)$ward->actual_value }} سورة
                      @elseif($ward->target_unit === 'hizbs')
                          {{ (int)$ward->actual_value }} حزب
                      @elseif($ward->target_unit === 'juzs')
                          {{ (int)$ward->actual_value }} جزء
                      @elseif($ward->target_unit === 'ayahs')
                          {{ (int)$ward->actual_value }} آية
                      @endif
                    </strong>
                </td>
                <td>
                    <div class="ward-table-progress-container">
                        <span class="ward-table-progress-text">{{ $ward->adherence_pct }}%</span>
                        <div class="ward-table-progress-bar">
                            <div class="ward-table-progress-fill" style="width: {{ $ward->adherence_pct }}%"></div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($ward->is_completed)
                        <span class="ward-table-status completed">✔️ مكتمل</span>
                    @else
                        <span class="ward-table-status pending">⏳ غير مكتمل</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <form action="{{ route('ward.destroy', $ward->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ward-table-delete-btn">حذف</button>
                    </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- نسخة الجوال --}}
        <div class="mobile-cards-wrapper">
          @foreach($allWards as $ward)
          <div class="mobile-ward-card">
            <div class="m-card-row">
              <span class="m-card-date">{{ $ward->ward_date->locale('ar')->isoFormat('D MMMM YYYY') }}</span>
              <span class="ward-status {{ $ward->is_completed ? 'completed' : 'pending' }}">
                {{ $ward->is_completed ? '✅ مكتمل' : '⏳ غير مكتمل' }}
              </span>
            </div>
            <div class="m-card-info">
              <div>
                <span>الموضع:</span> 
                <strong>
                  @if($ward->target_unit === 'pages' && $ward->start_page && $ward->end_page)
                    من {{ $ward->start_page }} إلى {{ $ward->end_page }} (صفحات)
                  @elseif($ward->target_unit === 'surahs' && isset($surahs[$ward->start_surah ?? $ward->specific_surah]))
                    سورة {{ $surahs[$ward->start_surah ?? $ward->specific_surah] }}
                  @elseif($ward->target_unit === 'hizbs')
                    الحزب {{ $ward->start_hizb ?? $ward->specific_hizb }}
                  @elseif($ward->target_unit === 'juzs')
                    الجزء {{ $ward->start_juz ?? $ward->specific_juz }}
                  @else
                    {{ rtrim(rtrim($ward->target_value,'0'),'.') }} {{ $unitLabels[$ward->target_unit] ?? '' }}
                  @endif
                </strong>
              </div>
              <div>
                <span>المنجز:</span> 
                <strong>
                  {{ rtrim(rtrim($ward->actual_value,'0'),'.') }}
                  @if($ward->target_unit === 'surahs') سور @elseif($ward->target_unit === 'hizbs') أحزاب @elseif($ward->target_unit === 'juzs') أجزاء @else صفحات @endif
                </strong>
              </div>
            </div>
            <div class="m-card-progress-row">
              <div style="flex:1;height:6px;background:var(--bg);border-radius:100px;overflow:hidden;">
                <div style="height:100%;background:linear-gradient(to left,#059669,#34d399);width:{{ $ward->adherence_pct }}%"></div>
              </div>
              <span class="m-card-pct">{{ $ward->adherence_pct }}٪</span>
            </div>
            <div class="m-card-footer">
              <form method="POST" action="{{ route('ward.destroy', $ward) }}" style="width:100%">
                @csrf @method('DELETE')
                <button type="submit" class="ward-delete-btn" style="width:100%; text-align:center" onclick="return confirm('هل تريد حذف هذا السجل؟')">حذف السجل</button>
              </form>
            </div>
          </div>
          @endforeach
        </div>

        <div style="padding:16px 22px">{{ $allWards->links() }}</div>
        @else
        <div class="ward-empty">
          <span>📖</span>
          <p>لا توجد سجلات ورد حتى الآن</p>
          <p style="font-size:12px;margin-top:6px">ابدأ بتسجيل وردك اليومي أعلاه</p>
        </div>
        @endif

      </div>
    </div>

  </div>

  {{-- ── الشريط الجانبي ── --}}
  <aside class="ward-side">

    {{-- نصيحة ثابته --}}
    <div class="card">
      <div style="padding:16px 20px;background:linear-gradient(135deg,#022c22,#064e3b);display:flex;align-items:center;gap:10px">
        <img src="{{ asset('images/idea.png') }}" alt="Idée" style="width:30px;height:30px;object-fit:contain">        
        <p style="color:#fff;font-size:13.5px;font-weight:700">إشارة عملية</p>
      </div>
      <div style="padding:18px 20px">
        <p style="font-size:13px;color:var(--text-m);line-height:1.9;font-style:italic">
          "اجعل هدف الورد قابلاً للإنجاز اليوم. الاستمرار على مقدار قليل أوضح أثرًا من هدف كبير ينقطع سريعًا."
        </p>
        <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
          <p style="font-family:'Amiri',serif;font-size:1.1rem;color:var(--text);text-align:center;line-height:1.8">
            ﴿ وَرَتِّلِ الْقُرْآنَ تَرْتِيلًا ﴾
          </p>
          <p style="font-size:11px;color:var(--text-m);text-align:center;margin-top:4px">سورة المزمل — الآية ٤</p>
        </div>
      </div>
    </div>

    {{-- إحصاء الـ Streak --}}
    <div class="card" style="border-color:#fde68a">
      <div style="padding:14px 18px;background:linear-gradient(135deg,#fffbeb,#fef3c7);border-bottom:1px solid #fde68a;display:flex;align-items:center;gap:10px">
        <img src="{{ asset('images/day.png') }}" alt="Idée" style="width:30px;height:30px;object-fit:contain">        
        <p style="font-size:13.5px;font-weight:700;color:#78350f">سلسلة الأيام</p>
      </div>
      <div style="padding:20px;text-align:center">
        <p style="font-family:'Amiri',serif;font-size:3rem;font-weight:700;color:#d97706;line-height:1;margin-bottom:4px">
          {{ $streak->current_streak }}
        </p>
        <p style="font-size:12px;color:var(--text-m);margin-bottom:16px">يوم متتالي</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px">
          <div style="padding:10px;border-radius:10px;background:var(--bg);border:1px solid var(--border)">
            <p style="font-size:1.2rem;font-weight:700;color:var(--text);font-family:'Amiri',serif">{{ $streak->longest_streak }}</p>
            <p style="font-size:10.5px;color:var(--text-m)">الأطول</p>
          </div>
          <div style="padding:10px;border-radius:10px;background:var(--bg);border:1px solid var(--border)">
            <p style="font-size:1.2rem;font-weight:700;color:var(--text);font-family:'Amiri',serif">{{ $streak->total_active_days }}</p>
            <p style="font-size:10.5px;color:var(--text-m)">إجمالي</p>
          </div>
        </div>
      </div>
    </div>

    {{-- حالة الأسبوع --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <img src="{{ asset('images/calendar.png') }}" alt="Idée" style="width:30px;height:30px;object-fit:contain">          
          أيام هذا الأسبوع
        </div>
      </div>
      <div style="padding:16px 20px">
        <div class="week-days-container" style="display:flex;gap:6px;justify-content:center">
          @php
            $weekDays = collect(range(0,6))->map(fn($i) => now()->startOfWeek()->addDays($i));
          @endphp
          @foreach($weekDays as $day)
          @php
            $dayWard = $allWards->getCollection()->firstWhere('ward_date', $day->toDateString());
            $isToday  = $day->isToday();
            $isFuture = $day->isFuture();
            $isDone   = $dayWard?->is_completed;
          @endphp
          <div style="text-align:center;flex:1">
            <p style="font-size:9.5px;color:var(--text-m);margin-bottom:5px">{{ $day->locale('ar')->isoFormat('dd') }}</p>
            <div class="week-day-box" style="
              width:100%;aspect-ratio:1;border-radius:9px;
              display:flex;align-items:center;justify-content:center;
              font-size:13px;font-weight:700;
              {{ $isDone   ? 'background:#d1fae5;color:#065f46;border:1.5px solid #a7f3d0;' : '' }}
              {{ $isToday && !$isDone ? 'background:#fffbeb;color:#d97706;border:1.5px solid #fde68a;' : '' }}
              {{ $isFuture ? 'background:var(--bg);color:var(--text-m);border:1.5px solid var(--border);' : '' }}
              {{ !$isDone && !$isToday && !$isFuture ? 'background:#fee2e2;color:#991b1b;border:1.5px solid #fecaca;' : '' }}
            ">
              {{ $isDone ? '✓' : ($isToday ? '◉' : ($isFuture ? '·' : '✗')) }}
            </div>
            <p style="font-size:9px;color:var(--text-m);margin-top:4px">{{ $day->format('d') }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>

  </aside>

</div>

@endsection

@push('styles')
<style>
/* --- الأنماط الأساسية والهيدر --- */
.ward-header { margin-bottom: 28px; }
.ward-ornament { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
.ward-ornament-line { flex: 1; height: 1px; background: linear-gradient(to left, transparent, #a7f3d0); }
.ward-ornament-line:last-child { background: linear-gradient(to right, transparent, #a7f3d0); }
.ward-header-content { display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; flex-wrap: wrap; }
.ward-title { font-family: 'Amiri', serif; font-size: 1.95rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }
.ward-subtitle { color: var(--text-m); font-size: 13px; line-height: 1.7; }
.ward-card-note { color: var(--text-m); font-size: 12px; font-weight: 400; }
.ward-date-pill { display: inline-flex; align-items: center; padding: 7px 14px; border-radius: 100px; font-size: 12px; font-weight: 600; color: #065f46; background: #d1fae5; border: 1px solid #a7f3d0; white-space: nowrap; }

/* --- الأزرار --- */
.ward-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 11px 20px;
  font-family: 'Tajawal', sans-serif;
  font-size: 13.5px;
  font-weight: 700;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  outline: none;
  gap: 8px;
}
.ward-btn.primary {
  background: linear-gradient(135deg, #059669, #047857);
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
}
.ward-btn.primary:hover {
  background: linear-gradient(135deg, #047857, #065f46);
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(5, 150, 105, 0.25);
}
.ward-btn.secondary {
  background: #f3f4f6;
  color: #374151;
  border: 1px solid #e5e7eb;
}
.ward-btn.secondary:hover {
  background: #e5e7eb;
  color: #1f2937;
}
.ward-btn.wide { width: 100%; }

/* --- بطاقات الإحصائيات --- */
.ward-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
.ward-stat { display: flex; align-items: center; gap: 14px; padding: 18px; background: var(--card); border: 1px solid var(--border); border-radius: 16px; transition: transform .2s, box-shadow .2s; }
.ward-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.06); }
.ward-stat-value { font-family: 'Amiri', serif; font-size: 1.35rem; font-weight: 700; color: var(--text); line-height: 1.3; }
.ward-stat-label { font-size: 13px; font-weight: 700; color: var(--text); margin-top: 4px; }
.ward-stat-sub { font-size: 11px; color: var(--text-m); margin-top: 2px; }

/* --- تخطيط الصفحة --- */
.ward-layout { display: grid; grid-template-columns: minmax(0,2fr) 300px; gap: 22px; align-items: start; }
.ward-main, .ward-side { display: flex; flex-direction: column; gap: 18px; }

/* --- بطاقة اليوم والتقدم --- */
.ward-today-card { border-color: #a7f3d0; }
.ward-status { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 700; }
.ward-status.completed { background: #d1fae5; color: #065f46; }
.ward-status.pending   { background: #fef3c7; color: #92400e; }

.ward-progress-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px; }
.ward-progress-title { font-size: 15px; font-weight: 700; color: var(--text); }
.ward-progress-sub { font-size: 12px; color: var(--text-m); margin-top: 3px; }
.ward-progress-number { font-family: 'Amiri', serif; font-size: 1.7rem; font-weight: 700; color: #059669; line-height: 1; flex-shrink: 0; }
.ward-progress-bar { height: 10px; border-radius: 100px; background: var(--bg); border: 1px solid var(--border); overflow: hidden; margin-bottom: 14px; }
.ward-progress-fill { height: 100%; border-radius: 100px; background: linear-gradient(to left, #059669, #34d399); transition: width .6s ease; }

.ward-location-box { display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: var(--bg); border-radius: 10px; border: 1px solid var(--border); margin-bottom: 12px; }
.ward-location-label { font-size: 12.5px; color: var(--text-m); }
.ward-location-value { font-size: 13px; font-weight: 700; color: var(--text); }
.ward-notes { font-size: 13px; color: var(--text-m); line-height: 1.7; padding: 10px 0; border-top: 1px solid var(--border); margin-top: 8px; }

/* --- أشكال الفورم --- */
.ward-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--border); align-items: center; }
.ward-action-form { display: block; width: auto; }
.ward-update-form { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.ward-update-form input { padding: 10px 12px; border-radius: 10px; border: 1.5px solid var(--border); background: var(--bg); color: var(--text); font-size: 13px; width: 130px; outline: none; }
.ward-update-form input:focus { border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,.09); }

.ward-form-section-title { font-size: 13px; font-weight: 700; color: #047857; margin-bottom: 12px; border-bottom: 1px dashed var(--border); padding-bottom: 6px; }
.ward-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 4px; }
.ward-field { display: flex; flex-direction: column; gap: 7px; width: 100%; }
.ward-field label { font-size: 12.5px; font-weight: 600; color: var(--text); }
.ward-field input, .ward-field select, .ward-field textarea { padding: 11px 13px; border: 1.5px solid var(--border); border-radius: 10px; background: var(--bg); color: var(--text); font-family: 'Tajawal', sans-serif; font-size: 13.5px; outline: none; transition: border-color .2s, box-shadow .2s; width: 100%; box-sizing: border-box; }
.ward-field input:focus, .ward-field select:focus, .ward-field textarea:focus { border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,.09); background: var(--card); }
.ward-error { font-size: 11.5px; color: #ef4444; margin-top: 2px; }

/* --- 🌟 الأنماط الحصرية والمعزولة للجدول التاريخي --- */
.desktop-table-wrapper { display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.ward-table { width: 100%; border-collapse: collapse; min-width: 700px; table-layout: fixed; }
.ward-table th, .ward-table td { padding: 14px 16px; text-align: right; border-bottom: 1px solid var(--border); font-size: 13px; color: var(--text); vertical-align: middle; word-wrap: break-word; }
.ward-table th { font-size: 12px; font-weight: 700; color: var(--text-m); background: var(--bg); }
.ward-table tr:last-child td { border-bottom: none; }
.ward-table tr:hover td { background: rgba(0,0,0,0.01); }

.ward-table-badge { display: inline-block; padding: 6px 12px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; font-size: 12.5px; color: var(--text); font-weight: 500; }
.ward-table-status { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }
.ward-table-status.completed { background: #d1fae5; color: #065f46; }
.ward-table-status.pending { background: #fffbeb; color: #b45309; }

.ward-table-progress-container { display: flex; align-items: center; gap: 8px; width: 100%; }
.ward-table-progress-text { font-size: 12px; font-weight: 600; min-width: 35px; text-align: left; }
.ward-table-progress-bar { flex: 1; height: 6px; background: var(--bg); border-radius: 100px; overflow: hidden; position: relative; }
.ward-table-progress-fill { height: 100%; background: #10b981; border-radius: 100px; }

.ward-table-delete-btn { padding: 6px 12px; border-radius: 6px; background: #fee2e2; color: #991b1b; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: background .2s; }
.ward-table-delete-btn:hover { background: #fca5a5; }

/* --- ريسبونسيف الجوال --- */
.mobile-cards-wrapper { display: none; padding: 12px; flex-direction: column; gap: 12px; }
.mobile-ward-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 14px; display: flex; flex-direction: column; gap: 10px; }
.m-card-row { display: flex; justify-content: space-between; align-items: center; }
.m-card-date { font-weight: 700; font-size: 13.5px; }
.m-card-info { background: var(--bg); padding: 10px; border-radius: 8px; display: flex; flex-direction: column; gap: 6px; font-size: 12.5px; }
.m-card-info div { display: flex; justify-content: space-between; }
.m-card-info div span { color: var(--text-m); }
.m-card-progress-row { display: flex; align-items: center; gap: 10px; }
.m-card-pct { font-size: 12px; font-weight: 700; color: #059669; }
.m-card-footer { border-top: 1px solid var(--border); padding-top: 8px; margin-top: 4px; }
.ward-delete-btn { display: block; padding: 8px; border-radius: 8px; background: #fee2e2; color: #991b1b; font-size: 12px; font-weight: 700; border: none; cursor: pointer; }

.ward-empty { text-align: center; padding: 40px 20px; color: var(--text-m); }
.ward-empty span { font-size: 40px; display: block; margin-bottom: 12px; }

@media (max-width: 991px) {
  .ward-layout { grid-template-columns: 1fr; }
  .ward-stats { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 650px) {
  .ward-stats { grid-template-columns: 1fr; }
  .desktop-table-wrapper { display: none; }
  .mobile-cards-wrapper { display: flex; }
  .ward-header-content { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush