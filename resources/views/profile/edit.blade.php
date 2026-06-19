@extends('layouts.app')
@section('title','تعديل الملف الشخصي')

@section('content')

<div style="max-width:680px;margin:0 auto">

  <div style="text-align:center;margin-bottom:32px">
    <h1 style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
               color:var(--text);margin-bottom:6px">تعديل الملف الشخصي</h1>
    <p style="font-size:13.5px;color:var(--text-m)">حدّث بياناتك الشخصية</p>
  </div>

  {{-- ══ البيانات الشخصية ══ --}}
  <div class="form-section">
    <div class="form-section-header">
      <span class="form-section-num">١</span>
      <div>
        <p class="form-section-title">البيانات الشخصية</p>
        <p class="form-section-sub">اسمك ورقم هاتفك</p>
      </div>
    </div>

    <form method="POST" action="{{ route('profile.update') }}"
          enctype="multipart/form-data">
      @csrf @method('PATCH')

      {{-- الصورة الشخصية --}}
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:22px">
        <div style="width:72px;height:72px;border-radius:16px;
                    overflow:hidden;flex-shrink:0;
                    background:linear-gradient(135deg,#064e3b,#0d6b52);
                    display:flex;align-items:center;justify-content:center;
                    border:2px solid var(--border)">
          @if($user->avatar_url)
            <img src="{{ asset($user->avatar_url) }}" id="avatarPreview"
                 style="width:100%;height:100%;object-fit:cover"/>
          @else
            <span id="avatarText" style="font-family:'Amiri',serif;
                  font-size:2rem;font-weight:700;color:#f59e0b">
              {{ mb_substr($user->name,0,1) }}
            </span>
          @endif
        </div>
        <div>
          <label style="display:inline-flex;align-items:center;gap:7px;
                         padding:9px 18px;border-radius:10px;cursor:pointer;
                         background:var(--bg);border:1.5px solid var(--border);
                         font-size:13px;font-weight:600;color:var(--text);
                         transition:border-color .18s"
                 onmouseover="this.style.borderColor='#059669'"
                 onmouseout="this.style.borderColor='var(--border)'">
            📷 تغيير الصورة
            <input type="file" name="avatar" accept="image/*"
                   style="display:none"
                   onchange="previewAvatar(this)"/>
          </label>
          <p style="font-size:11px;color:var(--text-m);margin-top:6px">
            JPG، PNG — حد أقصى 2MB
          </p>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:16px">
        <div>
          <label class="field-label">الاسم الكامل *</label>
          <input type="text" name="name"
                 value="{{ old('name',$user->name) }}"
                 class="field-input" placeholder="أدخل اسمك الكامل"/>
          @error('name') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="field-label">رقم الهاتف</label>
          <input type="tel" name="phone"
                 value="{{ old('phone',$user->phone) }}"
                 class="field-input" placeholder="+212 6XX XXX XXX" dir="ltr"/>
        </div>

        
      </div>

      <div style="display:flex;gap:12px;margin-top:22px">
        <a href="{{ route('profile.show') }}"
           style="flex:1;padding:13px;border-radius:12px;text-align:center;
                  background:var(--card);border:1.5px solid var(--border);
                  color:var(--text-m);font-size:14px;font-weight:600;text-decoration:none">
          إلغاء
        </a>
        <button type="submit"
                style="flex:2;padding:13px;border-radius:12px;
                       background:linear-gradient(135deg,#0d6b52,#064e3b);
                       border:none;color:#fff;font-family:'Tajawal',sans-serif;
                       font-size:14px;font-weight:700;cursor:pointer;
                       box-shadow:0 4px 16px rgba(13,107,82,.3)">
          ✓ حفظ التغييرات
        </button>
      </div>
    </form>
  </div>

  {{-- ══ تغيير كلمة المرور ══ --}}
  <div class="form-section" style="border-color:#fecaca">
    <div class="form-section-header">
      <span class="form-section-num"
            style="background:linear-gradient(135deg,#dc2626,#b91c1c)">٢</span>
      <div>
        <p class="form-section-title">تغيير كلمة المرور</p>
        <p class="form-section-sub">استخدم كلمة مرور قوية وآمنة</p>
      </div>
    </div>

    <form method="POST" action="{{ route('profile.password') }}">
      @csrf @method('PATCH')

      <div style="display:flex;flex-direction:column;gap:14px">
        <div>
          <label class="field-label">كلمة المرور الحالية *</label>
          <input type="password" name="current_password"
                 class="field-input" placeholder="••••••••"/>
          @error('current_password') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="field-label">كلمة المرور الجديدة *</label>
          <input type="password" name="password"
                 class="field-input" placeholder="٨ أحرف على الأقل"/>
          @error('password') <p class="field-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="field-label">تأكيد كلمة المرور الجديدة *</label>
          <input type="password" name="password_confirmation"
                 class="field-input" placeholder="أعد إدخال كلمة المرور"/>
        </div>
      </div>

      <button type="submit"
              style="margin-top:18px;width:100%;padding:13px;border-radius:12px;
                     background:linear-gradient(135deg,#dc2626,#b91c1c);
                     border:none;color:#fff;font-family:'Tajawal',sans-serif;
                     font-size:14px;font-weight:700;cursor:pointer">
        🔐 تغيير كلمة المرور
      </button>
    </form>
  </div>

</div>

@endsection

@push('styles')
<style>
.form-section {
  background:var(--card);border:1px solid var(--border);
  border-radius:20px;padding:24px;margin-bottom:18px;
  position:relative;overflow:hidden;
}
.form-section::before {
  content:'';position:absolute;top:0;right:0;
  width:60px;height:60px;border-radius:0 20px 0 60px;
  background:linear-gradient(135deg,#ecfdf5,transparent);
  opacity:.5;pointer-events:none;
}
.form-section-header {
  display:flex;align-items:flex-start;gap:14px;margin-bottom:20px;
}
.form-section-num {
  width:32px;height:32px;border-radius:9px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-family:'Amiri',serif;font-size:1.1rem;font-weight:700;color:#fff;
  background:linear-gradient(135deg,#064e3b,#0d6b52);
}
.form-section-title { font-size:14.5px;font-weight:700;color:var(--text);margin-bottom:3px; }
.form-section-sub   { font-size:12px;color:var(--text-m); }
.field-label { display:block;font-size:13px;font-weight:600;color:var(--text);margin-bottom:8px; }
.field-input {
  width:100%;padding:12px 14px;
  border:1.5px solid var(--border);border-radius:11px;
  font-size:14px;color:var(--text);background:var(--bg);
  outline:none;transition:border-color .2s,box-shadow .2s;
  font-family:'Tajawal',sans-serif;
}
.field-input:focus {
  border-color:#059669;box-shadow:0 0 0 4px rgba(5,150,105,.09);
  background:var(--card);
}
.field-error { font-size:12px;color:#ef4444;margin-top:5px; }
</style>
@endpush

@push('scripts')
<script>
function previewAvatar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const preview = document.getElementById('avatarPreview');
      const text    = document.getElementById('avatarText');
      if (preview) {
        preview.src = e.target.result;
      } else if (text) {
        text.style.display = 'none';
        const img = document.createElement('img');
        img.id    = 'avatarPreview';
        img.src   = e.target.result;
        img.style.cssText = 'width:100%;height:100%;object-fit:cover';
        text.parentNode.insertBefore(img, text);
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush