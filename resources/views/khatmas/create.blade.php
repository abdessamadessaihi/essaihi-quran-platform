@extends('layouts.app')

@section('title', 'ختمة جديدة')

@section('content')
<div class="page-header" style="margin-bottom:32px;text-align:center">
  <div style="display:flex;align-items:center;justify-content:center; gap:14px;margin-bottom:16px">
    <div style="flex:1;max-width:120px;height:1px; background:linear-gradient(to right,transparent,#a7f3d0)"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة" style="width:56px;height:56px;object-fit:contain;opacity:.85"/>
    <div style="flex:1;max-width:120px;height:1px; background:linear-gradient(to left,transparent,#a7f3d0)"></div>
  </div>
  <h1 class="page-title">إنشاء ختمة جديدة</h1>
  <p class="page-subtitle">ابدأ ختمة قرآنية جماعية أو فردية بإذن الله</p>
</div>

<div style="max-width:680px;margin:0 auto">
  <form method="POST" action="{{ route('khatmas.store') }}">
    @csrf

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #f87171; color: #b91c1c; padding: 16px; border-radius: 12px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- نوع الختمة --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">١</span>
        <div>
          <p class="form-section-title">نوع الختمة</p>
          <p class="form-section-sub">اختر النوع الذي يناسبك</p>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
        @foreach([
          ['family', 'عائلية',    'ختمة لجميع أفراد العائلة'],
          ['individual', 'فردية',     'ختمتك الخاصة'],
          ['ramadan', 'رمضانية',   'ختمة شهر رمضان'],
          ['weekly', 'أسبوعية',   'تنتهي في أسبوع'],
          ['monthly',  'شهرية',     'تنتهي في شهر'],
          ['platform', 'عامة',      'مفتوحة للجميع'],
        ] as [$val,$label,$desc])
        <label class="custom-selector-label" style="position:relative;cursor:pointer">
          <input type="radio" name="type" value="{{ $val }}" class="sr-only type-radio-{{ $val }}" 
                 {{ old('type', 'family') == $val ? 'checked' : '' }}/>
          
          <div class="selector-card" style="padding:16px 12px;border-radius:14px; border:2px solid var(--border);background:var(--card);text-align:center; transition:all .18s">
            <p style="font-size:13px;font-weight:700; color:var(--text);margin-bottom:3px">{{ $label }}</p>
            <p style="font-size:11px;color:var(--text-m);line-height:1.4">{{ $desc }}</p>
          </div>
          
          <div class="checkmark-badge" style="position:absolute;top:8px;left:8px; width:20px;height:20px;border-radius:50%; background:#059669; display:none;align-items:center;justify-content:center">
            <svg width="11" height="11" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
          </div>
        </label>
        @endforeach
      </div>
    </div>

    {{-- اختيار العائلة (تظهر وتختفي تلقائياً، وتتيح للمستخدم تحديد أي عائلة يريد) --}}
    <div class="form-section family-select-section">
      <div class="form-section-header">
        <span class="form-section-num">🏠</span>
        <div>
          <p class="form-section-title">العائلة المستهدفة</p>
          <p class="form-section-sub">اختر العائلة التي تود مشاركة الختمة معها</p>
        </div>
      </div>
      <div>
        <label class="field-label">اختر العائلة <span style="color:#ef4444">*</span></label>
        <select name="family_id" class="field-input" style="font-family:'Tajawal',sans-serif; cursor: pointer;">
          <option value="" disabled {{ old('family_id') == '' ? 'selected' : '' }}>-- اختر العائلة من القائمة --</option>
          @foreach($userFamilies as $f)
            <option value="{{ $f->id }}" {{ old('family_id') == $f->id ? 'selected' : '' }}>
              👪 {{ $f->name }}
            </option>
          @endforeach
        </select>
        @error('family_id') <p class="field-error">{{ $message }}</p> @enderror
      </div>
    </div>

    {{-- تفاصيل الختمة --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">٢</span>
        <div>
          <p class="form-section-title">تفاصيل الختمة</p>
          <p class="form-section-sub">عنوان وتواريخ البدء والانتهاء</p>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;gap:16px">
        <div>
          <label class="field-label">عنوان الختمة <span style="color:#ef4444">*</span></label>
          <input type="text" name="title" value="{{ old('title') }}" placeholder="مثال: ختمة رمضان ١٤٤٦" class="field-input" style="font-family:'Tajawal',sans-serif"/>
          @error('title') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
          <div>
            <label class="field-label">تاريخ البدء</label>
            <input type="date" name="starts_at" value="{{ old('starts_at', now()->format('Y-m-d')) }}" class="field-input" dir="ltr"/>
          </div>
          <div>
            <label class="field-label">تاريخ الانتهاء</label>
            <input type="date" name="ends_at" value="{{ old('ends_at') }}" class="field-input" dir="ltr"/>
          </div>
        </div>
      </div>
    </div>

    {{-- توزيع الأجزاء --}}
    <div class="form-section distribution-section">
      <div class="form-section-header">
        <span class="form-section-num">٣</span>
        <div>
          <p class="form-section-title">توزيع الأجزاء</p>
          <p class="form-section-sub">آلية توزيع الأجزاء على المشاركين</p>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        @foreach([
          ['1', '🤖', 'توزيع تلقائي', 'يوزّع النظام الأجزاء تلقائياً بالتساوي على المشاركين'],
          ['0', '✋', 'اختيار يدوي', 'يختار كل عضو الجزء الذي يريد قراءته بنفسه'],
        ] as [$val,$icon,$label,$desc])
        <label class="custom-selector-label" style="cursor:pointer; position: relative; display: block;">
          <input type="radio" name="auto_distribute" value="{{ $val }}" class="sr-only"
                 {{ old('auto_distribute', '1') == $val ? 'checked' : '' }}/>
          <div class="selector-card" style="padding:18px;border-radius:14px;border:2px solid var(--border); background:var(--card);text-align:center;transition:all .18s;">
            <span style="font-size:26px;display:block;margin-bottom:8px">{{ $icon }}</span>
            <p style="font-size:13px;font-weight:700; color:var(--text);margin-bottom:4px">{{ $label }}</p>
            <p style="font-size:11.5px;color:var(--text-m);line-height:1.6">{{ $desc }}</p>
          </div>
        </label>
        @endforeach
      </div>
    </div>

    {{-- أزرار الإرسال --}}
    <div style="display:flex;gap:12px;padding-top:8px">
      <a href="{{ route('khatmas.index') }}" style="flex:1;padding:14px;border-radius:13px; text-align:center;background:var(--card); border:1.5px solid var(--border); color:var(--text-m);font-size:14px; font-weight:600;text-decoration:none;transition:background .18s" onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='var(--card)'">إلغاء</a>
      <button type="submit" style="flex:2;padding:14px;border-radius:13px; background:linear-gradient(135deg,#0d6b52,#065f46); border:none;color:#fff; font-family:'Tajawal',sans-serif; font-size:15px;font-weight:700;cursor:pointer; box-shadow:0 6px 22px rgba(13,107,82,.35); transition:transform .18s,box-shadow .18s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">✦ إنشاء الختمة</button>
    </div>
  </form>
</div>
@endsection

@push('styles')
<style>
/* ══ السحر الخاص بالـ CSS لإخفاء وإظهار قسم اختيار العائلة وقسم التوزيع تلقائياً ══ */
.family-select-section, .distribution-section {
  display: none; /* مخفي افتراضياً لجميع الأنواع */
}

/* إذا تم تحديد راديو العائلة، قم بإظهار حقل اختيار العائلة وقسم التوزيع فوراً */
body:has(.type-radio-family:checked) .family-select-section,
body:has(.type-radio-family:checked) .distribution-section {
  display: block !important;
}

/* التنسيقات العامة للحفظ والتحديد */
.custom-selector-label input:checked + .selector-card {
  border-color: #059669 !important;
  background: #ecfdf5 !important;
}
.custom-selector-label input:checked ~ .checkmark-badge {
  display: flex !important;
}

/* ══ Form Sections ══════════════════════════════════════ */
.form-section {
  background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 24px; margin-bottom: 18px; position: relative; overflow: hidden;
}
.form-section::before {
  content: ''; position: absolute; top: 0; right: 0; width: 60px; height: 60px; border-radius: 0 20px 0 60px; background: linear-gradient(135deg,#ecfdf5,transparent); opacity: .5; pointer-events: none;
}
.form-section-header { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px; }
.form-section-num {
  width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-family: 'Amiri', serif; font-size: 1.1rem; font-weight: 700; color: #fff; background: linear-gradient(135deg, #064e3b, #0d6b52);
}
.form-section-title { font-size: 14.5px; font-weight: 700; color: var(--text); margin-bottom: 3px; }
.form-section-sub { font-size: 12px; color: var(--text-m); }
.field-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
.field-input {
  width: 100%; padding: 12px 14px; border: 1.5px solid var(--border); border-radius: 11px; font-size: 14px; color: var(--text); background: var(--bg); outline: none; transition: border-color .2s, box-shadow .2s;
}
.field-input:focus { border-color: #059669; box-shadow: 0 0 0 4px rgba(5,150,105,.09); background: var(--card); }
.field-error { font-size: 12px; color: #ef4444; margin-top: 5px; }
.sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; }
</style>
@endpush