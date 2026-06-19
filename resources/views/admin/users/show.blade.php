@extends('layouts.app')
@section('title', 'ملف المستخدم')

@section('content')

<a href="{{ route('admin.users.index') }}"
   style="display:inline-flex;align-items:center;gap:6px;
          color:var(--text-m);text-decoration:none;font-size:13px;
          margin-bottom:20px">
  <svg width="14" height="14" fill="none" stroke="currentColor"
       stroke-width="2" viewBox="0 0 24 24">
    <path d="M19 12H5M12 19l-7-7 7-7"/>
  </svg>
  إدارة المستخدمين
</a>

<div style="display:grid;grid-template-columns:minmax(0,2fr) 300px;gap:22px">

  {{-- ── العمود الرئيسي ── --}}
  <div style="display:flex;flex-direction:column;gap:18px">

    {{-- بانر المستخدم --}}
    <div style="background:linear-gradient(135deg,#022c22,#064e3b);
                border-radius:20px;padding:28px;
                display:flex;align-items:center;gap:18px;flex-wrap:wrap">
      <div style="width:72px;height:72px;border-radius:18px;flex-shrink:0;
                  background:rgba(245,158,11,.25);
                  border:2px solid rgba(245,158,11,.4);
                  display:flex;align-items:center;justify-content:center;
                  font-size:2rem;font-weight:700;color:#f59e0b">
        {{ mb_substr($user->name,0,1) }}
      </div>
      <div style="flex:1">
        <h1 style="font-family:'Amiri',serif;font-size:1.6rem;font-weight:700;
                   color:#fff;margin-bottom:4px">{{ $user->name }}</h1>
        <p style="font-size:13px;color:rgba(255,255,255,.65);margin-bottom:8px">
          {{ $user->email }}
        </p>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <span style="font-size:11.5px;padding:3px 12px;border-radius:100px;
                       font-weight:700;
                       {{ $user->is_active
                          ? 'background:rgba(16,185,129,.2);color:#6ee7b7'
                          : 'background:rgba(239,68,68,.2);color:#fca5a5' }}">
            {{ $user->is_active ? '✅ نشط' : '⛔ موقوف' }}
          </span>
          <span style="font-size:11.5px;padding:3px 12px;border-radius:100px;
                       background:rgba(245,158,11,.2);color:#fcd34d;font-weight:700">
            {{ $user->isSuperAdmin() ? '🌟 مدير عام'
               : ($user->isFamilyAdmin() ? '👑 مسؤول' : '📖 عضو') }}
          </span>
        </div>
      </div>
    </div>

    {{-- إحصائيات --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
      @foreach([
        ['⭐',$xp,'نقطة XP','#fffbeb','#fde68a','#d97706'],
        ['🔥',$streak->current_streak,'يوم متتالي','#fff7ed','#fed7aa','#ea580c'],
        ['📖',$user->dailyWards()->where('is_completed',true)->count(),'ورد مكتمل','#ecfdf5','#a7f3d0','#059669'],
        ['🧠',$user->memorizations()->count(),'سجل حفظ','#eff6ff','#bfdbfe','#2563eb'],
      ] as [$icon,$val,$lbl,$bg,$border,$vc])
      <div style="background:{{ $bg }};border:1px solid {{ $border }};
                  border-radius:14px;padding:18px;text-align:center">
        <span style="font-size:20px;display:block;margin-bottom:6px">{{ $icon }}</span>
        <p style="font-family:'Amiri',serif;font-size:1.7rem;font-weight:700;
                  color:{{ $vc }};line-height:1;margin-bottom:4px">{{ $val }}</p>
        <p style="font-size:11px;color:var(--text-m)">{{ $lbl }}</p>
      </div>
      @endforeach
    </div>

    {{-- تعديل الدور --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon gold">⚙️</div>
          تعديل البيانات
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
          @csrf @method('PATCH')
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;font-size:12.5px;font-weight:600;
                             color:var(--text-m);margin-bottom:7px">الاسم</label>
              <input type="text" name="name" value="{{ $user->name }}"
                     style="width:100%;padding:11px 13px;border:1.5px solid var(--border);
                            border-radius:10px;font-family:'Tajawal',sans-serif;
                            font-size:13.5px;color:var(--text);background:var(--bg);outline:none"/>
            </div>
            <div>
              <label style="display:block;font-size:12.5px;font-weight:600;
                             color:var(--text-m);margin-bottom:7px">الدور</label>
              <select name="role"
                      style="width:100%;padding:11px 13px;border:1.5px solid var(--border);
                             border-radius:10px;font-family:'Tajawal',sans-serif;
                             font-size:13.5px;color:var(--text);background:var(--bg)">
                <option value="member"       @selected($user->role==='member')>عضو</option>
                <option value="family_admin" @selected($user->role==='family_admin')>مسؤول عائلة</option>
                <option value="super_admin"  @selected($user->role==='super_admin')>مدير عام</option>
              </select>
            </div>
          </div>
          <div style="display:flex;gap:10px">
            <button type="submit"
                    style="padding:11px 24px;border-radius:11px;
                           background:linear-gradient(135deg,#0d6b52,#064e3b);
                           border:none;color:#fff;font-family:'Tajawal',sans-serif;
                           font-size:13.5px;font-weight:700;cursor:pointer">
              ✓ حفظ التغييرات
            </button>
            <form method="POST"
                  action="{{ route('admin.users.toggle', $user) }}"
                  style="margin:0">
              @csrf
              <button type="submit"
                      style="padding:11px 20px;border-radius:11px;
                             background:{{ $user->is_active ? '#fef2f2' : '#ecfdf5' }};
                             border:1.5px solid {{ $user->is_active ? '#fecaca' : '#a7f3d0' }};
                             color:{{ $user->is_active ? '#991b1b' : '#065f46' }};
                             font-family:'Tajawal',sans-serif;font-size:13.5px;
                             font-weight:700;cursor:pointer">
                {{ $user->is_active ? '⛔ إيقاف الحساب' : '✅ تفعيل الحساب' }}
              </button>
            </form>
          </div>
        </form>
      </div>
    </div>

    {{-- العائلات --}}
    @if($user->families->isNotEmpty())
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon green">👨‍👩‍👧</div>
          العائلات المنتمي إليها
        </div>
      </div>
      <div style="padding:14px 18px;display:flex;flex-direction:column;gap:8px">
        @foreach($user->families as $f)
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:12px 14px;border-radius:12px;
                    background:var(--bg);border:1px solid var(--border)">
          <div>
            <p style="font-size:13.5px;font-weight:700;color:var(--text)">{{ $f->name }}</p>
            <p style="font-size:11.5px;color:var(--text-m)">
              الدور: {{ $f->pivot->role === 'admin' ? '👑 مسؤول' : '📖 عضو' }}
            </p>
          </div>
          <a href="{{ route('admin.families.show', $f) }}"
             style="font-size:12px;color:#059669;font-weight:600;text-decoration:none">
            عرض ←
          </a>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>

  {{-- ── الشريط الجانبي ── --}}
  <div style="display:flex;flex-direction:column;gap:16px">

    {{-- إرسال إشعار --}}
    <div class="card" style="border-color:#fde68a">
      <div style="padding:14px 18px;
                  background:linear-gradient(135deg,#fffbeb,#fef3c7);
                  border-bottom:1px solid #fde68a">
        <p style="font-size:13.5px;font-weight:700;color:#78350f">📨 إرسال إشعار</p>
      </div>
      <div style="padding:18px">
        <form method="POST" action="{{ route('admin.users.notify', $user) }}">
          @csrf
          <textarea name="message" rows="4"
                    placeholder="اكتب رسالتك هنا..."
                    required
                    style="width:100%;padding:11px;border:1.5px solid var(--border);
                           border-radius:10px;font-family:'Tajawal',sans-serif;
                           font-size:13.5px;color:var(--text);background:var(--bg);
                           outline:none;resize:none;margin-bottom:12px"></textarea>
          <button type="submit"
                  style="width:100%;padding:12px;border-radius:11px;
                         background:linear-gradient(135deg,#d97706,#b45309);
                         border:none;color:#fff;font-family:'Tajawal',sans-serif;
                         font-size:13.5px;font-weight:700;cursor:pointer;
                         box-shadow:0 4px 14px rgba(217,119,6,.3)">
            📨 إرسال
          </button>
        </form>
      </div>
    </div>

    {{-- معلومات --}}
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon blue">ℹ️</div>
          معلومات الحساب
        </div>
      </div>
      <div style="padding:14px 18px">
        @foreach([
          ['رقم الهاتف', $user->phone ?? '—'],
          ['اللغة',      $user->locale === 'ar' ? 'العربية' : $user->locale],
          ['تاريخ التسجيل', $user->created_at->locale('ar')->isoFormat('D MMM YYYY')],
          ['آخر تحديث', $user->updated_at->locale('ar')->diffForHumans()],
        ] as [$k,$v])
        <div style="display:flex;justify-content:space-between;
                    padding:10px 0;border-bottom:1px solid var(--border)">
          <span style="font-size:12px;color:var(--text-m)">{{ $k }}</span>
          <span style="font-size:12.5px;font-weight:600;color:var(--text)">{{ $v }}</span>
        </div>
        @endforeach
      </div>
    </div>

  </div>
</div>

@endsection