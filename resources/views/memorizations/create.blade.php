@extends('layouts.app')

@section('title', 'إضافة محفوظ جديد')

@section('content')

{{-- ══ رأس الصفحة ══ --}}
<div class="page-header" style="margin-bottom:32px; text-align: center;">
  <div class="page-header-ornament" style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 14px;">
    <div class="ornament-line" style="width: 80px; height: 1px; background: linear-gradient(to right, transparent, #a7f3d0);"></div>
    <span class="ornament-icon" style="font-size: 18px; color: #059669;">✦</span>
    <div class="ornament-line" style="width: 80px; height: 1px; background: linear-gradient(to left, transparent, #a7f3d0);"></div>
  </div>
  <h1 class="page-title" style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--text); margin-bottom: 8px;">تسجيل محفوظ جديد</h1>
  <p class="page-subtitle" style="font-size: 13.5px; color: var(--text-m); line-height: 1.7;">سجّل ما وفّقك الله لحفظه وتابع تقدّمك يوماً بيوم</p>
</div>

<div style="max-width:720px;margin:0 auto">

  <form method="POST" action="{{ route('memorizations.store') }}" id="memForm" x-data="memorizationForm()">
    @csrf

    {{-- ── القسم ١: السورة ── --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">١</span>
        <div>
          <p class="form-section-title">اختيار السورة</p>
          <p class="form-section-sub">حدد السورة التي وفّقك الله لحفظها</p>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:16px">
        <div>
          <label class="field-label">السورة <span style="color:#ef4444">*</span></label>
          <select name="surah_number" x-model="surah" class="field-input" style="font-family:'Tajawal',sans-serif">
            @foreach($surahs as $num => $name)
            <option value="{{ $num }}" @selected(old('surah_number', 1) == $num)>
              {{ $num }} — {{ $name }}
            </option>
            @endforeach
          </select>
          @error('surah_number')
            <p class="field-error">{{ $message }}</p>
          @enderror
        </div>

        {{-- معاينة اسم السورة المختارة --}}
        <div style="display:flex;align-items:center;gap:14px; padding:14px 18px;border-radius:14px; background:linear-gradient(135deg,#ecfdf5,#d1fae5); border:1px solid #a7f3d0">
          <div style="width:42px;height:42px;border-radius:12px; background:linear-gradient(135deg,#064e3b,#0d6b52); display:flex;align-items:center;justify-content:center; font-family:'Amiri',serif;font-size:1.1rem; font-weight:700;color:#fff;flex-shrink:0" x-text="surah"></div>
          <div>
            <p style="font-family:'Amiri',serif;font-size:1.3rem; font-weight:700;color:#064e3b;line-height:1.2" x-text="surahNames[surah] || '---'"></p>
            <p style="font-size:11.5px;color:#065f46;margin-top:2px">السورة المختارة</p>
          </div>
        </div>
      </div>
    </div>

    {{-- ── القسم ٢: نطاق الآيات ── --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">٢</span>
        <div>
          <p class="form-section-title">نطاق الآيات</p>
          <p class="form-section-sub">حدد رقم أول وآخر آية في هذا المحفوظ</p>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
          <label class="field-label">من الآية <span style="color:#ef4444">*</span></label>
          <input type="number" name="ayah_from" x-model="ayahFrom" min="1" placeholder="مثال: 1" class="field-input"/>
          @error('ayah_from')
            <p class="field-error">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="field-label">إلى الآية <span style="color:#ef4444">*</span></label>
          <input type="number" name="ayah_to" x-model="ayahTo" min="1" placeholder="مثال: 7" class="field-input"/>
          @error('ayah_to')
            <p class="field-error">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- عداد الآيات الديناميكي --}}
      <div x-show="ayahFrom && ayahTo && parseInt(ayahTo) >= parseInt(ayahFrom)" x-transition style="margin-top:14px;display:none">
        <div style="display:flex;align-items:center;justify-content:space-between; padding:12px 16px;border-radius:12px; background:var(--bg);border:1px solid var(--border)">
          <span style="font-size:12.5px;color:var(--text-m)">عدد الآيات المحفوظة</span>
          <span style="font-family:'Amiri',serif;font-size:1.5rem;font-weight:700;color:#059669" x-text="(parseInt(ayahTo) - parseInt(ayahFrom) + 1) + ' آية'"></span>
        </div>
      </div>
    </div>

    {{-- ── القسم ٣: مستوى الإتقان (الحل الذكي المصلح) ── --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">٣</span>
        <div>
          <p class="form-section-title">مستوى الإتقان</p>
          <p class="form-section-sub">قيّم مستوى حفظك الحالي للمتابعة بدقة</p>
        </div>
      </div>

      <div>
        <label class="field-label">درجة الإتقان <span style="color:#ef4444">*</span></label>
        <select name="mastery_level" class="field-input" style="font-family:'Tajawal',sans-serif; background-color: var(--bg);">
          <option value="weak" @selected(old('mastery_level') == 'weak')>😕 ضعيف (يحتاج تكراراً كثيراً)</option>
          <option value="fair" @selected(old('mastery_level') == 'fair')>😊 متوسط (إتقان لا بأس به)</option>
          <option value="good" @selected(old('mastery_level') == 'good')>😎 جيد (إتقان جيد مع مراجعة)</option>
          <option value="excellent" @selected(old('mastery_level', 'excellent') == 'excellent')>🌟 ممتاز (إتقان تام بحمد الله)</option>
        </select>
        @error('mastery_level')
          <p class="field-error">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- ── القسم ٤: التاريخ ── --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">٤</span>
        <div>
          <p class="form-section-title">تاريخ الحفظ</p>
          <p class="form-section-sub">متى أتممت حفظ هذه الآيات؟</p>
        </div>
      </div>

      <div>
        <label class="field-label">تاريخ الحفظ <span style="color:#ef4444">*</span></label>
        <input type="date" name="memorized_at" value="{{ old('memorized_at', today()->toDateString()) }}" max="{{ today()->toDateString() }}" class="field-input" dir="ltr"/>
        @error('memorized_at')
          <p class="field-error">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- ── أزرار الإرسال ── --}}
    <div style="display:flex;gap:12px;padding-top:8px;margin-bottom:40px">
      <a href="{{ route('memorizations.index') }}" style="flex:1;padding:14px;border-radius:13px; text-align:center;background:var(--card); border:1.5px solid var(--border); color:var(--text-m);font-size:14px; font-weight:600;text-decoration:none; transition:background .18s">
        إلغاء
      </a>
      <button type="submit" style="flex:2;padding:14px;border-radius:13px; background:linear-gradient(135deg,#0d6b52,#064e3b); border:none;color:#fff; font-family:'Tajawal',sans-serif; font-size:15px;font-weight:700;cursor:pointer; box-shadow:0 6px 22px rgba(13,107,82,.35);">
        ✦ حفظ السجل
      </button>
    </div>

  </form>
</div>

@endsection

@push('styles')
<style>
.form-section {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 24px;
  margin-bottom: 18px;
  position: relative;
  overflow: hidden;
}
.form-section::before {
  content: '';
  position: absolute; top: 0; right: 0;
  width: 60px; height: 60px; border-radius: 0 20px 0 60px;
  background: linear-gradient(135deg,#ecfdf5,transparent);
  opacity: .5; pointer-events: none;
}
.form-section-header { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px; }
.form-section-num {
  width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Amiri', serif; font-size: 1.1rem; font-weight: 700; color: #fff;
  background: linear-gradient(135deg, #064e3b, #0d6b52);
}
.form-section-title { font-size: 14.5px; font-weight: 700; color: var(--text); margin-bottom: 3px; }
.form-section-sub { font-size: 12px; color: var(--text-m); }
.field-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
.field-input {
  width: 100%; padding: 12px 14px;
  border: 1.5px solid var(--border); border-radius: 11px;
  font-size: 14px; color: var(--text);
  background: var(--bg); outline: none;
  transition: border-color .2s, box-shadow .2s;
  font-family: 'Tajawal', sans-serif;
}
.field-input:focus {
  border-color: #059669;
  box-shadow: 0 0 0 4px rgba(5,150,105,.09);
}
.field-error { font-size: 12px; color: #ef4444; margin-top: 5px; }
</style>
@endpush

@push('scripts')
<script>
window.SURAHS_DATA = @json($surahs);

function memorizationForm() {
  return {
    surah:      '{{ old('surah_number', 1) }}',
    ayahFrom:   '{{ old('ayah_from', '') }}',
    ayahTo:     '{{ old('ayah_to', '') }}',
    surahNames: window.SURAHS_DATA,
  };
}
</script>
@endpush