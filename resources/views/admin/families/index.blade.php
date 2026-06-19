@extends('layouts.app')
@section('title','إدارة العائلات')

@section('content')

<div style="margin-bottom:28px">
  <div style="display:flex;align-items:flex-start;
              justify-content:space-between;gap:16px;flex-wrap:wrap">
    <div>
      <h1 style="font-family:'Amiri',serif;font-size:1.9rem;font-weight:700;
                 color:var(--text);margin-bottom:5px">إدارة العائلات</h1>
      <p style="font-size:13.5px;color:var(--text-m)">عرض وإدارة جميع العائلات المسجّلة</p>
    </div>
  </div>
</div>

{{-- إحصائيات --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);
            gap:14px;margin-bottom:24px">
  @foreach([
    ['👨‍👩‍👧',$stats['total'],      'عائلة مسجّلة','#ecfdf5','#a7f3d0','#059669'],
    ['✅',$stats['active'],      'عائلة نشطة',   '#eff6ff','#bfdbfe','#2563eb'],
    ['👥',$stats['total_members'],'عضو نشط',      '#fffbeb','#fde68a','#d97706'],
  ] as [$icon,$val,$lbl,$bg,$border,$vc])
  <div style="background:{{ $bg }};border:1px solid {{ $border }};
              border-radius:16px;padding:20px;text-align:center">
    <span style="font-size:22px;display:block;margin-bottom:8px">{{ $icon }}</span>
    <p style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
              color:{{ $vc }};line-height:1;margin-bottom:4px">{{ $val }}</p>
    <p style="font-size:12px;color:var(--text-m)">{{ $lbl }}</p>
  </div>
  @endforeach
</div>

{{-- بحث --}}
<div style="background:var(--card);border:1px solid var(--border);
            border-radius:16px;padding:16px;margin-bottom:20px">
  <form method="GET" action="{{ route('admin.families.index') }}"
        style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
    <div style="flex:1;min-width:200px">
      <input type="text" name="search" value="{{ $search }}"
             placeholder="ابحث عن عائلة..."
             style="width:100%;padding:10px 13px;border:1.5px solid var(--border);
                    border-radius:10px;font-family:'Tajawal',sans-serif;
                    font-size:13.5px;color:var(--text);background:var(--bg);outline:none"/>
    </div>
    <button type="submit"
            style="padding:10px 22px;border-radius:10px;
                   background:linear-gradient(135deg,#0d6b52,#064e3b);
                   border:none;color:#fff;font-family:'Tajawal',sans-serif;
                   font-size:13.5px;font-weight:700;cursor:pointer">
      🔍 بحث
    </button>
  </form>
</div>

{{-- شبكة العائلات --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
            gap:18px">
  @forelse($families as $family)
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:20px;overflow:hidden;
              transition:box-shadow .2s,transform .2s"
       onmouseover="this.style.transform='translateY(-2px)';
                    this.style.boxShadow='0 8px 28px rgba(6,78,59,.10)'"
       onmouseout="this.style.transform='translateY(0)';
                   this.style.boxShadow='none'">

    <div style="padding:20px 22px">
      <div style="display:flex;align-items:flex-start;
                  justify-content:space-between;margin-bottom:14px">
        <div>
          <h3 style="font-family:'Amiri',serif;font-size:1.1rem;font-weight:700;
                     color:var(--text);margin-bottom:4px">{{ $family->name }}</h3>
          <p style="font-size:12px;color:var(--text-m)">
            بواسطة {{ $family->creator->name }}
          </p>
        </div>
        <span style="font-size:11px;padding:3px 10px;border-radius:100px;font-weight:700;
                     {{ $family->is_active
                        ? 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0'
                        : 'background:#fef2f2;color:#991b1b;border:1px solid #fecaca' }}">
          {{ $family->is_active ? 'نشطة' : 'موقوفة' }}
        </span>
      </div>

      {{-- إحصائيات --}}
      <div style="display:grid;grid-template-columns:repeat(3,1fr);
                  gap:8px;margin-bottom:16px">
        @foreach([
          [$family->active_count ?? 0,'عضو','👥'],
          [$family->khatmas_count ?? 0,'ختمة','📚'],
          [$family->active_khatmas ?? 0,'نشطة','✅'],
        ] as [$v,$l,$i])
        <div style="text-align:center;padding:10px 6px;
                    background:var(--bg);border-radius:10px;
                    border:1px solid var(--border)">
          <span style="font-size:14px;display:block;margin-bottom:3px">{{ $i }}</span>
          <p style="font-family:'Amiri',serif;font-size:1.2rem;font-weight:700;
                    color:var(--text);line-height:1">{{ $v }}</p>
          <p style="font-size:10px;color:var(--text-m);margin-top:2px">{{ $l }}</p>
        </div>
        @endforeach
      </div>

      {{-- أزرار --}}
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('admin.families.show', $family) }}"
           style="flex:1;padding:9px;border-radius:9px;text-align:center;
                  background:linear-gradient(135deg,#0d6b52,#064e3b);
                  color:#fff;font-size:12.5px;font-weight:700;text-decoration:none">
          عرض
        </a>
        <button onclick="openFamilyNotif({{ $family->id }}, '{{ addslashes($family->name) }}')"
                style="flex:1;padding:9px;border-radius:9px;
                       background:#fffbeb;border:1px solid #fde68a;
                       color:#92400e;font-size:12.5px;font-weight:700;cursor:pointer;
                       font-family:'Tajawal',sans-serif">
          📨 إشعار
        </button>
        <form method="POST"
              action="{{ route('admin.families.update', $family) }}"
              style="margin:0">
          @csrf @method('PATCH')
          <input type="hidden" name="is_active"
                 value="{{ $family->is_active ? '0' : '1' }}"/>
          <button type="submit"
                  style="padding:9px 12px;border-radius:9px;
                         background:{{ $family->is_active ? '#fef2f2' : '#ecfdf5' }};
                         border:1px solid {{ $family->is_active ? '#fecaca' : '#a7f3d0' }};
                         color:{{ $family->is_active ? '#991b1b' : '#065f46' }};
                         font-size:12px;cursor:pointer;font-weight:700;
                         font-family:'Tajawal',sans-serif">
            {{ $family->is_active ? '⛔' : '✅' }}
          </button>
        </form>
      </div>
    </div>
  </div>
  @empty
  <div style="grid-column:1/-1;text-align:center;padding:64px;
              background:var(--card);border:1px solid var(--border);border-radius:20px">
    <span style="font-size:48px;display:block;margin-bottom:16px">👨‍👩‍👧</span>
    <p style="font-size:14px;color:var(--text-m)">لا توجد عائلات</p>
  </div>
  @endforelse
</div>

@if($families->hasPages())
<div style="margin-top:20px">{{ $families->links() }}</div>
@endif

{{-- Modal إشعار العائلة --}}
<div id="familyNotifModal"
     style="display:none;position:fixed;inset:0;z-index:500;
            background:rgba(0,0,0,.45);backdrop-filter:blur(4px);
            align-items:center;justify-content:center">
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:24px;padding:28px;max-width:440px;width:90%;
              box-shadow:0 24px 64px rgba(0,0,0,.18)">
    <h3 style="font-family:'Amiri',serif;font-size:1.3rem;font-weight:700;
               color:var(--text);margin-bottom:6px" id="familyNotifTitle">
      إرسال إشعار للعائلة
    </h3>
    <p style="font-size:13px;color:var(--text-m);margin-bottom:20px">
      سيصل الإشعار لجميع أعضاء العائلة
    </p>
    <form id="familyNotifForm" method="POST">
      @csrf
      <textarea name="message" rows="4"
                placeholder="اكتب رسالتك هنا..."
                required
                style="width:100%;padding:12px;border:1.5px solid var(--border);
                       border-radius:12px;font-family:'Tajawal',sans-serif;
                       font-size:14px;color:var(--text);background:var(--bg);
                       outline:none;resize:none;margin-bottom:16px"></textarea>
      <div style="display:flex;gap:10px">
        <button type="button"
                onclick="document.getElementById('familyNotifModal').style.display='none'"
                style="flex:1;padding:12px;border-radius:11px;
                       background:var(--bg);border:1.5px solid var(--border);
                       font-family:'Tajawal',sans-serif;font-size:14px;
                       font-weight:600;color:var(--text-m);cursor:pointer">
          إلغاء
        </button>
        <button type="submit"
                style="flex:2;padding:12px;border-radius:11px;
                       background:linear-gradient(135deg,#d97706,#b45309);
                       border:none;color:#fff;font-family:'Tajawal',sans-serif;
                       font-size:14px;font-weight:700;cursor:pointer">
          📨 إرسال للعائلة
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function openFamilyNotif(id, name) {
  document.getElementById('familyNotifTitle').textContent =
    `إرسال إشعار لعائلة: ${name}`;
  document.getElementById('familyNotifForm').action = `/admin/families/${id}/notify`;
  document.getElementById('familyNotifModal').style.display = 'flex';
}
document.getElementById('familyNotifModal')
        ?.addEventListener('click', e => {
          if (e.target === e.currentTarget)
            e.currentTarget.style.display = 'none';
        });
</script>
@endpush