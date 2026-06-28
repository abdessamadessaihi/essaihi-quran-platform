@extends('layouts.app')
@section('title', $family->name)

@section('content')

{{-- ══ بانر العائلة ══ --}}
<div class="fam-show-banner">
  <div class="fam-show-banner-bg"
       @if($family->cover_url)
       style="background-image:url('{{ asset($family->cover_url) }}');background-size:cover;background-position:center"
       @endif>
  </div>
  <div class="fam-show-banner-overlay"></div>
  <div class="fam-show-banner-content">
    <div class="fam-show-logo">
      @if($family->logo_url)
        <img src="{{ asset($family->logo_url) }}" alt="{{ $family->name }}"
             style="width:100%;height:100%;object-fit:cover;border-radius:18px">
      @else
        <span style="font-size:40px">👨‍👩‍👧</span>
      @endif
    </div>
    <div>
      <h1 class="fam-show-title">{{ $family->name }}</h1>
      @if($family->description)
      <p class="fam-show-desc">{{ $family->description }}</p>
      @endif
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:10px">
        <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:rgba(255,255,255,.75)">
          <span style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block"></span>
          نشطة
        </span>
        <span style="font-size:12px;color:rgba(255,255,255,.6)">
          أُسّست {{ $family->created_at->locale('ar')->isoFormat('MMMM YYYY') }}
        </span>
        <span style="font-size:12px;color:rgba(255,255,255,.6)">
          بواسطة {{ $family->creator->name }}
        </span>
      </div>
    </div>
  </div>
</div>

{{-- ══ إحصائيات سريعة ══ --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:14px;margin-bottom:28px">
  @foreach([
    [$stats['active_members'],    'عضو نشط',          '#ecfdf5','#a7f3d0','#059669','👥'],
    [$stats['pending_members'],   'طلب انتظار',        '#fffbeb','#fde68a','#d97706','⏳'],
    [$stats['active_khatmas'],    'ختمة نشطة',         '#eff6ff','#bfdbfe','#2563eb','📚'],
    [$stats['completed_khatmas'], 'ختمة مكتملة',       '#ecfdf5','#a7f3d0','#059669','✅'],
  ] as [$val,$lbl,$bg,$border,$vc,$icon])
  <div style="background:{{ $bg }};border:1px solid {{ $border }};border-radius:16px;padding:20px 18px;text-align:center">
    <span style="font-size:22px;display:block;margin-bottom:8px">{{ $icon }}</span>
    <p style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;color:{{ $vc }};line-height:1;margin-bottom:4px">{{ $val }}</p>
    <p style="font-size:12px;color:var(--text-m)">{{ $lbl }}</p>
  </div>
  @endforeach
</div>

<div style="display:grid;grid-template-columns:minmax(0,2fr) 320px;gap:22px">
{{-- ── العمود الرئيسي ── --}}
<div style="display:flex;flex-direction:column;gap:20px">

  {{-- طلبات الانضمام (للمسؤول فقط) --}}
  @if($isAdmin && $pendingMembers->isNotEmpty())
  <div class="card" style="border-color:#fde68a">
    <div style="padding:16px 22px;background:linear-gradient(135deg,#fffbeb,#fef3c7);border-bottom:1px solid #fde68a;display:flex;align-items:center;gap:10px">
      <span style="font-size:20px">⏳</span>
      <div>
        <p style="font-size:14px;font-weight:700;color:#78350f">طلبات الانضمام ({{ $pendingMembers->count() }})</p>
        <p style="font-size:11.5px;color:#b45309;margin-top:2px">بانتظار مراجعتك وموافقتك</p>
      </div>
    </div>
    <div style="padding:16px 22px;display:flex;flex-direction:column;gap:10px">
      @foreach($pendingMembers as $pending)
      <div style="display:flex;align-items:center;gap:14px;padding:14px;border-radius:14px;background:var(--bg);border:1px solid var(--border)">
        <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#064e3b,#0d6b52);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:700;flex-shrink:0">
          {{ mb_substr($pending->user->name, 0, 1) }}
        </div>
        <div style="flex:1;min-width:0">
          <p style="font-size:13.5px;font-weight:700;color:var(--text)">{{ $pending->user->name }}</p>
          <p style="font-size:11.5px;color:var(--text-m);margin-top:2px">{{ $pending->user->email }} • طلب {{ $pending->created_at->locale('ar')->diffForHumans() }}</p>
        </div>
        <div style="display:flex;gap:8px;flex-shrink:0">
          <form method="POST" action="{{ route('families.members.approve', [$family, $pending]) }}">
            @csrf
            <button type="submit" style="padding:8px 16px;border-radius:9px;background:linear-gradient(135deg,#059669,#047857);border:none;color:#fff;font-size:12.5px;font-weight:700;cursor:pointer;font-family:'Tajawal',sans-serif">✓ قبول</button>
          </form>
          <form method="POST" action="{{ route('families.members.reject', [$family, $pending]) }}">
            @csrf
            <button type="submit" style="padding:8px 14px;border-radius:9px;background:#fee2e2;border:1px solid #fecaca;color:#991b1b;font-size:12.5px;font-weight:700;cursor:pointer;font-family:'Tajawal',sans-serif">✗ رفض</button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- الأعضاء النشطون --}}
  <div class="card">
    <div class="card-header">
      <div class="card-header-title">
        <div class="card-icon green">👥</div>
        <div>
          الأعضاء النشطون
          <p style="font-size:11px;color:var(--text-m);font-weight:400;margin-top:1px">{{ $activeMembers->count() }} عضو</p>
        </div>
      </div>
    </div>
    <div style="padding:18px 22px">
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px">
        @forelse($activeMembers as $mem)
        <div style="display:flex;align-items:center;gap:12px;padding:14px;border-radius:14px;background:var(--bg);border:1px solid var(--border);transition:border-color .18s" onmouseover="this.style.borderColor='#a7f3d0'" onmouseout="this.style.borderColor='var(--border)'">
          <div style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#fff;flex-shrink:0;background:linear-gradient(135deg,#064e3b,#0d6b52)">
            {{ mb_substr($mem->user->name, 0, 1) }}
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-size:13px;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
              {{ $mem->user->name }}
              @if($mem->user->id === auth()->id())
                <span style="font-size:9px;padding:1px 6px;border-radius:100px;background:#059669;color:#fff">أنت</span>
              @endif
            </p>
            <div style="display:flex;align-items:center;gap:6px;margin-top:3px">
              @if($mem->role === 'admin')
                <span style="font-size:10.5px;padding:2px 8px;border-radius:100px;background:#fffbeb;color:#92400e;border:1px solid #fde68a;font-weight:700">👑 مسؤول</span>
              @else
                <span style="font-size:10.5px;padding:2px 8px;border-radius:100px;background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;font-weight:700">📖 عضو</span>
              @endif
            </div>
            <p style="font-size:10.5px;color:var(--text-m);margin-top:2px">
              انضم {{ $mem->joined_at?->locale('ar')->isoFormat('D MMM YYYY') ?? '—' }}
            </p>
          </div>
          @if($isAdmin && $mem->user->id !== auth()->id())
          <form method="POST" action="{{ route('families.members.remove', [$family, $mem]) }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('هل تريد إيقاف هذا العضو؟')" style="width:28px;height:28px;border-radius:7px;background:#fee2e2;border:1px solid #fecaca;color:#ef4444;cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center" title="إيقاف العضو">✕</button>
          </form>
          @endif
        </div>
        @empty
        <p style="color:var(--text-m);font-size:13px;padding:20px;text-align:center">لا يوجد أعضاء نشطون بعد</p>
        @endforelse
      </div>
    </div>
  </div>

  {{-- 👶 إدارة الأطفال والبراعم المحدثة كلياً بأجمل مظهر --}}
  @if($isAdmin)
  <div class="card" style="border-color: #bfdbfe; box-shadow: 0 4px 20px rgba(59,130,246,0.06);">
    <div style="padding: 18px 22px; background: linear-gradient(135deg, #f0fdfa, #e0f2fe); border-bottom: 1px solid #bfdbfe; display:flex; align-items:center; gap:12px;">
      <span style="font-size:26px;">👶</span>
      <div>
        <h2 style="font-size:15px; font-weight:800; color:#1e3a8a;">حسابات براعم وأطفال العائلة </h2>
        <p style="font-size:11.5px; color:#1d4ed8; font-weight:500; margin-top:1px;">تحكم بحسابات أطفالك، وتابع تقدمهم القرآني بنفسك.</p>
      </div>
    </div>
    
    <div style="padding: 22px;">
      <h3 style="font-size:13.5px; font-weight:700; margin-bottom:14px; color:var(--text); display:flex; align-items:center; gap:6px;">
        <span style="color:#3b82f6">●</span> الأبطال الحاليون في أسرتك:
      </h3>
      
      <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap:14px; margin-bottom:28px;">
          @forelse(auth()->user()->children as $child)
          <div class="child-box-card">
              <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                  <div class="child-avatar-circle">👦</div>
                  <div style="flex:1; min-width:0;">
                      <h4 class="child-card-name">{{ $child->name }}</h4>
                      <p class="child-card-meta">العمر: {{ $child->age ?? '—' }} سنة • <code class="child-code-badge">{{ $child->username }}</code></p>
                  </div>
              </div>
              
              <div style="display:flex; gap:6px;">
                  {{-- دخول سريع --}}
                  <form method="POST" action="{{ route('children.impersonate', $child) }}" style="flex:1">
                      @csrf
                      <button type="submit" class="child-btn-action login">
                          دخول سريع ←
                      </button>
                  </form>
                  {{-- حذف نهائي للحساب --}}
                  <form method="POST" action="{{ route('children.destroy', $child) }}" onsubmit="return confirm('⚠️ هل أنت متأكد تماماً من حذف حساب الطفل {{ $child->name }} نهائياً؟ سيتم حذف جميع بيانات تقدمه في الورد والحفظ!')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="child-btn-action delete" title="حذف حساب الطفل نهائياً">
                          🗑️
                      </button>
                  </form>
              </div>
          </div>
          @empty
          <div style="grid-column: 1/-1; text-align:center; padding:30px; background:#f8fafc; border-radius:16px; border:1px dashed #cbd5e1;">
              <p style="font-size:13px; color:var(--text-m);">لم تقم بإضافة أي طفل بعد في  عائلتك القرآنية.</p>
          </div>
          @endforelse
      </div>

      <div style="border-top:1px dashed #e2e8f0; margin:24px 0; position:relative;">
          <span style="position:absolute; top:-12px; right:20px; background:var(--card); padding:0 10px; font-size:12px; font-weight:700; color:#3b82f6;">➕ إنشاء حساب بطل جديد</span>
      </div>

      {{-- نموذج الإضافة --}}
      <form method="POST" action="{{ route('children.store') }}" class="child-creation-form">
          @csrf
          <input type="hidden" name="family_id" value="{{ $family->id }}">
          
          <div class="child-form-grid">
              <div class="child-input-group">
                  <label>اسم البطل الصغير *</label>
                  <input type="text" name="name" placeholder="يعقوب السيحي" required>
              </div>
              <div class="child-input-group">
                  <label>العمر بالسنوات</label>
                  <input type="number" name="age" min="3" max="18" placeholder="مثال: 8">
              </div>
              <div class="child-input-group">
                  <label>اسم المستخدم  (بالإنجليزي للدخول) *</label>
                  <input type="text" name="username" placeholder="مثال: mohamed" required>
              </div>
              <div class="child-input-group">
                  <label>رمز PIN السري (4 أرقام) *</label>
                  <input type="password" name="pin_code" maxlength="4" pattern="\d{4}" placeholder="مثال: 1234" required>
              </div>
          </div>

          <button type="submit" class="child-submit-btn-glow">
              ✨ تسجيل الحساب وتفعيله تلقائياً في العائلة ✨
          </button>
      </form>
    </div>
  </div>
  @endif

  {{-- ختمات العائلة --}}
  <div class="card">
    <div class="card-header">
      <div class="card-header-title">
        <div class="card-icon blue">📚</div>
        ختمات العائلة
      </div>
      <a href="{{ route('khatmas.create') }}" style="font-size:12.5px;color:#059669;text-decoration:none;font-weight:600;display:flex;align-items:center;gap:4px">
        + ختمة جديدة
      </a>
    </div>
    <div style="padding:18px 22px">
      @forelse($family->khatmas as $khatma)
      <div style="display:flex;align-items:center;gap:14px;padding:14px;border-radius:12px;border:1px solid var(--border);margin-bottom:10px;background:var(--bg)">
        <div style="flex:1">
          <p style="font-size:13.5px;font-weight:700;color:var(--text)">{{ $khatma->title }}</p>
          <p style="font-size:11.5px;color:var(--text-m);margin-top:2px">{{ $khatma->completed_juz_count }}/30 جزء • {{ $khatma->created_at->locale('ar')->diffForHumans() }}</p>
        </div>
        <div>
          <div style="height:5px;width:80px;background:var(--border);border-radius:100px;overflow:hidden">
            <div style="height:100%;border-radius:100px;background:linear-gradient(to left,#059669,#34d399);width:{{ round(($khatma->completed_juz_count/30)*100) }}%"></div>
          </div>
          <p style="font-size:10px;color:var(--text-m);text-align:center;margin-top:3px">{{ round(($khatma->completed_juz_count/30)*100) }}٪</p>
        </div>
        <a href="{{ route('khatmas.show', $khatma) }}" style="font-size:12px;color:#059669;text-decoration:none;font-weight:600">فتح ←</a>
      </div>
      @empty
      <div style="text-align:center;padding:32px;color:var(--text-m)">
        <span style="font-size:32px;display:block;margin-bottom:10px">📚</span>
        <p style="font-size:13px">لا توجد ختمات بعد</p>
        <a href="{{ route('khatmas.create') }}" style="color:#059669;font-weight:600;font-size:12.5px;text-decoration:none">ابدأ ختمة جديدة ←</a>
      </div>
      @endforelse
    </div>
  </div>

</div>

{{-- ── الشريط الجانبي ── --}}
<div style="display:flex;flex-direction:column;gap:18px">
  <div class="card">
    <div style="padding:16px 20px;background:linear-gradient(135deg,#022c22,#064e3b);display:flex;align-items:center;gap:10px">
      <span style="font-size:20px">ℹ️</span>
      <p style="color:#fff;font-size:13.5px;font-weight:700">معلومات العائلة</p>
    </div>
    <div style="padding:16px 20px;display:flex;flex-direction:column;gap:12px">
      @foreach([
        ['المنشئ', $family->creator->name],
        ['تاريخ الإنشاء', $family->created_at->locale('ar')->isoFormat('D MMMM YYYY')],
        ['عدد الأعضاء', $stats['active_members'] . ' عضو نشط'],
        ['الحالة', 'نشطة ✅'],
      ] as [$key, $val])
      <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border)">
        <span style="font-size:12.5px;color:var(--text-m)">{{ $key }}</span>
        <span style="font-size:12.5px;font-weight:700;color:var(--text)">{{ $val }}</span>
      </div>
      @endforeach
    </div>
  </div>

  @if($membership)
  <div class="card" style="border-color:#fde68a">
    <div style="padding:14px 18px;background:linear-gradient(135deg,#fffbeb,#fef3c7);border-bottom:1px solid #fde68a;display:flex;align-items:center;gap:10px">
      <span style="font-size:20px">{{ $membership->role === 'admin' ? '👑' : '📖' }}</span>
      <p style="font-size:13.5px;font-weight:700;color:#78350f">دوري في العائلة</p>
    </div>
    <div style="padding:18px 20px;text-align:center">
      <div style="width:60px;height:60px;border-radius:16px;background:linear-gradient(135deg,#064e3b,#0d6b52);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;color:#fff;margin:0 auto 14px">
        {{ mb_substr(auth()->user()->name, 0, 1) }}
      </div>
      <p style="font-size:14px;font-weight:700;color:var(--text);margin-bottom:6px">{{ auth()->user()->name }}</p>
      <span style="font-size:12px;padding:4px 14px;border-radius:100px;font-weight:700;{{ $membership->role === 'admin' ? 'background:#fffbeb;color:#92400e;border:1px solid #fde68a' : 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0' }}">
        {{ $membership->role === 'admin' ? '👑 مسؤول العائلة' : '📖 عضو العائلة' }}
      </span>
      @if($membership->joined_at)
      <p style="font-size:11.5px;color:var(--text-m);margin-top:12px">عضو منذ {{ $membership->joined_at->locale('ar')->isoFormat('D MMM YYYY') }}</p>
      @endif
    </div>
  </div>
  @endif

  @if($isAdmin)
  <div class="card">
    <div class="card-header">
      <div class="card-header-title">
        <div class="card-icon gold">🔗</div>
        دعوة أعضاء جدد
      </div>
    </div>
    <div style="padding:16px 20px">
      <p style="font-size:12.5px;color:var(--text-m);margin-bottom:12px;line-height:1.7">شارك رابط العائلة مع أفراد عائلتك ليتمكنوا من إرسال طلبات الانضمام</p>
      <div style="display:flex;gap:8px">
        <input type="text" readonly value="{{ route('families.show', $family) }}" style="flex:1;padding:9px 12px;border-radius:9px;border:1.5px solid var(--border);background:var(--bg);color:var(--text);font-size:11.5px;outline:none;font-family:'Tajawal',sans-serif" id="familyLink"/>
        <button onclick="navigator.clipboard.writeText(document.getElementById('familyLink').value); this.textContent='✓'; setTimeout(()=>this.textContent='نسخ',2000);" style="padding:9px 14px;border-radius:9px;background:linear-gradient(135deg,#059669,#047857);border:none;color:#fff;font-size:12px;font-weight:700;cursor:pointer;font-family:'Tajawal',sans-serif;transition:opacity .18s">نسخ</button>
      </div>
    </div>
  </div>
  @endif
</div>
</div>

@endsection

@push('styles')
<style>
.fam-show-banner { position: relative; border-radius: 20px; overflow: hidden; margin-bottom: 24px; min-height: 180px; }
.fam-show-banner-bg { position: absolute; inset: 0; background: linear-gradient(135deg,#022c22,#064e3b,#0a6647); }
.fam-show-banner-overlay { position: absolute; inset: 0; background: linear-gradient(to left, transparent, rgba(2,44,34,.6)); }
.fam-show-banner-content { position: relative; z-index: 10; padding: 32px; display: flex; align-items: flex-end; gap: 22px; }
.fam-show-logo { width: 80px; height: 80px; border-radius: 18px; flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.12); border: 2px solid rgba(255,255,255,.25); box-shadow: 0 8px 24px rgba(0,0,0,.3); }
.fam-show-title { font-family: 'Amiri', serif; font-size: 1.9rem; font-weight: 700; color: #fff; margin-bottom: 4px; line-height: 1.3; }
.fam-show-desc { font-size: 13px; color: rgba(255,255,255,.7); line-height: 1.6; max-width: 500px; }

/* ── استايلات إدارة الأطفال المحدثة ── */
.child-box-card { background: var(--bg); border: 1px solid #cbd5e1; border-radius: 16px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease; }
.child-box-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.04); border-color: #93c5fd; }
.child-avatar-circle { width: 44px; height: 44px; background: #e0f2fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.child-card-name { font-size: 13.5px; font-weight: 800; color: var(--text); }
.child-card-meta { font-size: 11px; color: var(--text-m); margin-top: 2px; }
.child-code-badge { background: #f1f5f9; padding: 1px 5px; border-radius: 4px; font-family: monospace; color: #334155; }
.child-btn-action { font-family: 'Tajawal'; border: none; font-weight: 700; font-size: 12px; padding: 8px 12px; border-radius: 10px; cursor: pointer; transition: background 0.15s; }
.child-btn-action.login { background: #2563eb; color: #fff; text-align: center; text-decoration: none; }
.child-btn-action.login:hover { background: #1d4ed8; }
.child-btn-action.delete { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 8px 10px; }
.child-btn-action.delete:hover { background: #fca5a5; }

.child-creation-form { background: #f8fafc; padding: 18px; border-radius: 16px; border: 1px solid #e2e8f0; }
.child-form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; }
.child-input-group { display: flex; flex-direction: column; gap: 6px; }
.child-input-group label { font-size: 12.5px; font-weight: 700; color: #334155; }
.child-input-group input { padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-family: 'Tajawal'; font-size: 13.5px; outline: none; background: #fff; transition: border-color 0.15s; }
.child-input-group input:focus { border-color: #3b82f6; }
.child-submit-btn-glow { margin-top: 16px; width: 100%; padding: 12px; border: none; border-radius: 12px; font-family: 'Tajawal'; font-weight: 800; font-size: 14px; color: #fff; background: linear-gradient(135deg, #3b82f6, #1d4ed8); cursor: pointer; box-shadow: 0 4px 15px rgba(29,78,216,0.3); transition: opacity 0.2s; }
.child-submit-btn-glow:hover { opacity: 0.95; }

@media (max-width: 1024px) {
  div[style*="grid-template-columns:minmax(0,2fr) 320px"] { grid-template-columns: 1fr !important; }
}
</style>
@endpush