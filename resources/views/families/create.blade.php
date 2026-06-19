@extends('layouts.app')
@section('title', 'إنشاء عائلة')

@section('content')

<div class="page-header" style="margin-bottom:32px;text-align:center">
  <div style="display:flex;align-items:center;justify-content:center;
              gap:14px;margin-bottom:16px">
    <div style="flex:1;max-width:120px;height:1px;
                background:linear-gradient(to right,transparent,#a7f3d0)"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة"
         style="width:56px;height:56px;object-fit:contain;opacity:.85"/>
    <div style="flex:1;max-width:120px;height:1px;
                background:linear-gradient(to left,transparent,#a7f3d0)"></div>
  </div>
  <h1 style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
             color:var(--text);margin-bottom:8px">
    إنشاء عائلة قرآنية
  </h1>
  <p style="font-size:13.5px;color:var(--text-m)">
    أسّس دائرة قرآنية لعائلتك وادعُ أفرادها للمشاركة
  </p>
</div>

<div style="max-width:600px;margin:0 auto">
  <form method="POST" action="{{ route('families.store') }}">
    @csrf

    {{-- اسم العائلة --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">١</span>
        <div>
          <p class="form-section-title">اسم العائلة</p>
          <p class="form-section-sub">اختر اسماً يُعرّف عائلتك</p>
        </div>
      </div>
      <div>
        <label class="field-label">
          اسم العائلة <span style="color:#ef4444">*</span>
        </label>
        <input type="text" name="name"
               value="{{ old('name') }}"
               placeholder="مثال: عائلة  يحيى السيحي"
               class="field-input"
               style="font-family:'Tajawal',sans-serif"/>
        @error('name')
          <p class="field-error">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- وصف العائلة --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">٢</span>
        <div>
          <p class="form-section-title">وصف العائلة</p>
          <p class="form-section-sub">اكتب نبذة مختصرة عن رؤية عائلتك القرآنية</p>
        </div>
      </div>
      <div>
        <label class="field-label">الوصف (اختياري)</label>
        <textarea name="description" rows="4"
                  placeholder="مثال: نسعى معاً نحو بيت قرآني مستقر..."
                  class="field-input"
                  style="font-family:'Tajawal',sans-serif;resize:vertical">{{ old('description') }}</textarea>
        @error('description')
          <p class="field-error">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- ملاحظة --}}
    <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);
                border:1px solid #bfdbfe;border-radius:14px;
                padding:16px 20px;margin-bottom:18px;
                display:flex;align-items:flex-start;gap:12px">
      <span style="font-size:20px;flex-shrink:0">💡</span>
      <div>
        <p style="font-size:13px;font-weight:700;color:#1e40af;margin-bottom:4px">
          ملاحظة
        </p>
        <p style="font-size:12.5px;color:#1d4ed8;line-height:1.7">
          ستصبح تلقائياً <strong>مسؤول العائلة</strong> بعد إنشائها.
          يمكنك دعوة أفراد عائلتك بمشاركة رابط العائلة معهم.
        </p>
      </div>
    </div>

    {{-- أزرار --}}
    <div style="display:flex;gap:12px">
      <a href="{{ route('families.index') }}"
         style="flex:1;padding:14px;border-radius:13px;text-align:center;
                background:var(--card);border:1.5px solid var(--border);
                color:var(--text-m);font-size:14px;font-weight:600;
                text-decoration:none;transition:background .18s"
         onmouseover="this.style.background='var(--bg)'"
         onmouseout="this.style.background='var(--card)'">
        إلغاء
      </a>
      <button type="submit"
              style="flex:2;padding:14px;border-radius:13px;
                     background:linear-gradient(135deg,#0d6b52,#064e3b);
                     border:none;color:#fff;
                     font-family:'Tajawal',sans-serif;
                     font-size:15px;font-weight:700;cursor:pointer;
                     box-shadow:0 6px 22px rgba(13,107,82,.35);
                     transition:transform .18s,box-shadow .18s"
              onmouseover="this.style.transform='translateY(-2px)'"
              onmouseout="this.style.transform='translateY(0)'">
        ✦ إنشاء العائلة
      </button>
    </div>
  </form>
</div>

@endsection

@push('styles')
<style>
.form-section {
  background: var(--card); border: 1px solid var(--border);
  border-radius: 20px; padding: 24px; margin-bottom: 18px;
  position: relative; overflow: hidden;
}
.form-section::before {
  content: ''; position: absolute; top: 0; right: 0;
  width: 60px; height: 60px; border-radius: 0 20px 0 60px;
  background: linear-gradient(135deg,#ecfdf5,transparent);
  opacity: .5; pointer-events: none;
}
.form-section-header {
  display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px;
}
.form-section-num {
  width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-family: 'Amiri', serif; font-size: 1.1rem; font-weight: 700;
  color: #fff; background: linear-gradient(135deg, #064e3b, #0d6b52);
}
.form-section-title { font-size: 14.5px; font-weight: 700; color: var(--text); margin-bottom: 3px; }
.form-section-sub { font-size: 12px; color: var(--text-m); }
.field-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
.field-input {
  width: 100%; padding: 12px 14px;
  border: 1.5px solid var(--border); border-radius: 11px;
  font-size: 14px; color: var(--text); background: var(--bg);
  outline: none; transition: border-color .2s, box-shadow .2s;
}
.field-input:focus {
  border-color: #059669; box-shadow: 0 0 0 4px rgba(5,150,105,.09);
  background: var(--card);
}
.field-error { font-size: 12px; color: #ef4444; margin-top: 5px; }
</style>
@endpush