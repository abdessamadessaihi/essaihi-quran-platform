@extends('layouts.app')
@section('title','كتابة مقال')

@section('content')

<div style="max-width:760px;margin:0 auto">

  <div style="text-align:center;margin-bottom:32px">
    <h1 style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
               color:var(--text);margin-bottom:6px">كتابة مقال تدبري</h1>
    <p style="font-size:13.5px;color:var(--text-m)">
      شارك تأملاتك وأفكارك القرآنية مع العائلة
    </p>
  </div>

  <form method="POST" action="{{ route('articles.store') }}"
        enctype="multipart/form-data">
    @csrf

    {{-- العنوان --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">1</span>
        <div>
          <p class="form-section-title">عنوان المقال</p>
          <p class="form-section-sub">اختر عنواناً واضحاً ومعبراً</p>
        </div>
      </div>
      <input type="text" name="title"
             value="{{ old('title') }}"
             placeholder="مثال: تدبر في سورة الكهف — آيات النور والظلام"
             class="field-input"
             style="font-size:16px;font-family:'Amiri',serif"/>
      @error('title') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    {{-- الفئة + الصورة --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">2</span>
        <div>
          <p class="form-section-title">الفئة والصورة</p>
          <p class="form-section-sub">صنّف مقالك وأضف صورة غلاف اختيارية</p>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
          <label class="field-label">الفئة *</label>
          <select name="category" class="field-input">
            @foreach($categories as $key => $label)
            <option value="{{ $key }}" @selected(old('category','tadabbur') === $key)>
              {{ $label }}
            </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="field-label">صورة الغلاف (اختياري)</label>
          <label style="display:flex;align-items:center;gap:8px;
                         padding:11px 14px;border-radius:11px;cursor:pointer;
                         border:1.5px dashed var(--border);background:var(--bg);
                         font-size:13px;color:var(--text-m);transition:all .18s"
                 onmouseover="this.style.borderColor='#059669'"
                 onmouseout="this.style.borderColor='var(--border)'">
            🖼️ اختر صورة
            <input type="file" name="cover" accept="image/*"
                   style="display:none"
                   onchange="document.getElementById('coverName').textContent=this.files[0]?.name||''"/>
          </label>
          <p id="coverName" style="font-size:11px;color:var(--text-m);margin-top:4px"></p>
        </div>
      </div>
    </div>

    {{-- المقتطف --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">3</span>
        <div>
          <p class="form-section-title">مقتطف المقال</p>
          <p class="form-section-sub">جملة أو جملتان تلخصان فكرة المقال</p>
        </div>
      </div>
      <textarea name="excerpt" rows="3"
                placeholder="لخّص فكرة مقالك في سطرين..."
                class="field-input"
                style="resize:vertical">{{ old('excerpt') }}</textarea>
      @error('excerpt') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    {{-- المحتوى --}}
    <div class="form-section">
      <div class="form-section-header">
        <span class="form-section-num">4</span>
        <div>
          <p class="form-section-title">محتوى المقال</p>
          <p class="form-section-sub">اكتب تأملاتك وأفكارك بحرية</p>
        </div>
      </div>
      <textarea name="content" rows="14"
                placeholder="ابدأ بسم الله...&#10;&#10;اكتب مقالك هنا..."
                class="field-input"
                style="resize:vertical;font-size:14px;line-height:1.9">{{ old('content') }}</textarea>
      @error('content') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    {{-- الأزرار --}}
    <div style="display:flex;gap:12px;flex-wrap:wrap">
      <a href="{{ route('articles.index') }}"
         style="flex:1;padding:13px;border-radius:12px;text-align:center;
                background:var(--card);border:1.5px solid var(--border);
                color:var(--text-m);font-size:14px;font-weight:600;text-decoration:none;
                min-width:100px">
        إلغاء
      </a>
      <button type="submit" name="status" value="draft"
              style="flex:1;padding:13px;border-radius:12px;
                     background:var(--bg);border:1.5px solid var(--border);
                     font-family:'Tajawal',sans-serif;font-size:14px;
                     font-weight:600;color:var(--text-m);cursor:pointer;
                     min-width:120px">
        💾 حفظ مسودة
      </button>
      <button type="submit" name="status" value="published"
              style="flex:2;padding:13px;border-radius:12px;
                     background:linear-gradient(135deg,#0d6b52,#064e3b);
                     border:none;color:#fff;font-family:'Tajawal',sans-serif;
                     font-size:15px;font-weight:700;cursor:pointer;
                     box-shadow:0 4px 16px rgba(13,107,82,.3);
                     min-width:160px">
         نشر المقال
      </button>
    </div>
  </form>
</div>

@endsection

@push('styles')
<style>
.form-section{
  background:var(--card);border:1px solid var(--border);
  border-radius:20px;padding:24px;margin-bottom:18px;
  position:relative;overflow:hidden;
}
.form-section::before{
  content:'';position:absolute;top:0;right:0;
  width:60px;height:60px;border-radius:0 20px 0 60px;
  background:linear-gradient(135deg,#ecfdf5,transparent);
  opacity:.5;pointer-events:none;
}
.form-section-header{display:flex;align-items:flex-start;gap:14px;margin-bottom:18px}
.form-section-num{
  width:32px;height:32px;border-radius:9px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-family:'Amiri',serif;font-size:1.1rem;font-weight:700;color:#fff;
  background:linear-gradient(135deg,#064e3b,#0d6b52);
}
.form-section-title{font-size:14.5px;font-weight:700;color:var(--text);margin-bottom:3px}
.form-section-sub{font-size:12px;color:var(--text-m)}
.field-label{display:block;font-size:13px;font-weight:600;color:var(--text);margin-bottom:8px}
.field-input{
  width:100%;padding:12px 14px;
  border:1.5px solid var(--border);border-radius:11px;
  font-size:14px;color:var(--text);background:var(--bg);
  outline:none;transition:border-color .2s,box-shadow .2s;
  font-family:'Tajawal',sans-serif;
}
.field-input:focus{
  border-color:#059669;box-shadow:0 0 0 4px rgba(5,150,105,.09);
  background:var(--card);
}
.field-error{font-size:12px;color:#ef4444;margin-top:5px}
@media(max-width:640px){
  div[style*="grid-template-columns:1fr 1fr"]{
    grid-template-columns:1fr !important;
  }
  div[style*="display:flex;gap:12px;flex-wrap:wrap"] button,
  div[style*="display:flex;gap:12px;flex-wrap:wrap"] a{
    flex:1 1 100% !important;
  }
}
</style>
@endpush