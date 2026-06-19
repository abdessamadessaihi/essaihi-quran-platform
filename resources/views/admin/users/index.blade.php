@extends('layouts.app')
@section('title','إدارة المستخدمين')

@section('content')

{{-- ══ رأس الصفحة ══ --}}
<div style="margin-bottom:28px">
  <div style="display:flex;align-items:flex-start;
              justify-content:space-between;gap:16px;flex-wrap:wrap">
    <div>
      <h1 style="font-family:'Amiri',serif;font-size:1.9rem;font-weight:700;
                 color:var(--text);margin-bottom:5px">إدارة المستخدمين</h1>
      <p style="font-size:13.5px;color:var(--text-m)">
        عرض وإدارة جميع أعضاء المنصة
      </p>
    </div>
    {{-- إشعار جماعي --}}
    {{-- 💡 تحسين: إضافة كلاس الزر الجماعي لتوسيع عرضه بشكل متناسق في الهاتف --}}
    <button onclick="document.getElementById('broadcastModal').style.display='flex'"
            class="admin-broadcast-btn"
            style="display:inline-flex;align-items:center;justify-content:center;gap:8px;
                   padding:11px 20px;border-radius:12px;
                   background:linear-gradient(135deg,#0d6b52,#064e3b);
                   color:#fff;font-size:13.5px;font-weight:700;
                   border:none;cursor:pointer;white-space:nowrap;
                   box-shadow:0 4px 16px rgba(13,107,82,.30)">
      📢 إشعار جماعي
    </button>
  </div>
</div>

{{-- ══ إحصائيات ══ --}}
{{-- 💡 تحسين: تحويل شبكة الـ Grid الصلبة إلى minmax مرن لتصبح 2x2 في الهواتف تلقائياً بشكل منظم جداً --}}
<div class="admin-stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));
            gap:14px;margin-bottom:24px">
  @foreach([
    ['👥',$stats['total'],      'إجمالي المستخدمين','#ecfdf5','#a7f3d0','#059669'],
    ['✅',$stats['active'],     'مستخدم نشط',       '#eff6ff','#bfdbfe','#2563eb'],
    ['👑',$stats['admins'],     'مسؤول عائلة',      '#fffbeb','#fde68a','#d97706'],
    ['🆕',$stats['new_week'],   'جديد هذا الأسبوع', '#fdf4ff','#e9d5ff','#9333ea'],
  ] as [$icon,$val,$lbl,$bg,$border,$vc])
  <div class="admin-stat-box" style="background:{{ $bg }};border:1px solid {{ $border }};
              border-radius:16px;padding:20px 14px;text-align:center">
    <span style="font-size:22px;display:block;margin-bottom:8px">{{ $icon }}</span>
    <p style="font-family:'Amiri',serif;font-size:1.8rem;font-weight:700;
              color:{{ $vc }};line-height:1;margin-bottom:4px">{{ $val }}</p>
    <p style="font-size:12px;color:var(--text-m)">{{ $lbl }}</p>
  </div>
  @endforeach
</div>

{{-- ══ فلاتر البحث ══ --}}
<div style="background:var(--card);border:1px solid var(--border);
            border-radius:16px;padding:18px;margin-bottom:20px">
  {{-- 💡 تحسين: إضافة كلاس الاستمارة وإضافة gap-y للفصل العمودي على الشاشات الصغيرة --}}
  <form method="GET" action="{{ route('admin.users.index') }}"
        class="admin-filter-form"
        style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <div class="filter-field-main" style="flex:2;min-width:200px">
      <label style="display:block;font-size:12.5px;font-weight:600;
                     color:var(--text-m);margin-bottom:6px">بحث</label>
      <input type="text" name="search" value="{{ $search }}"
             placeholder="الاسم أو البريد الإلكتروني..."
             style="width:100%;padding:10px 13px;border:1.5px solid var(--border);
                    border-radius:10px;font-family:'Tajawal',sans-serif;
                    font-size:13.5px;color:var(--text);background:var(--bg);outline:none"/>
    </div>
    <div class="filter-field-sub" style="min-width:140px">
      <label style="display:block;font-size:12.5px;font-weight:600;
                     color:var(--text-m);margin-bottom:6px">الدور</label>
      <select name="role"
              style="width:100%;padding:10px 13px;border:1.5px solid var(--border);
                     border-radius:10px;font-family:'Tajawal',sans-serif;
                     font-size:13.5px;color:var(--text);background:var(--bg)">
        <option value="">الكل</option>
        <option value="super_admin" @selected($role==='super_admin')>مدير عام</option>
        <option value="family_admin" @selected($role==='family_admin')>مسؤول عائلة</option>
        <option value="member" @selected($role==='member')>عضو</option>
      </select>
    </div>
    <div class="filter-field-sub" style="min-width:120px">
      <label style="display:block;font-size:12.5px;font-weight:600;
                     color:var(--text-m);margin-bottom:6px">الحالة</label>
      <select name="status"
              style="width:100%;padding:10px 13px;border:1.5px solid var(--border);
                     border-radius:10px;font-family:'Tajawal',sans-serif;
                     font-size:13.5px;color:var(--text);background:var(--bg)">
        <option value="">الكل</option>
        <option value="1" @selected($status==='1')>نشط</option>
        <option value="0" @selected($status==='0')>موقوف</option>
      </select>
    </div>
    {{-- 💡 تحسين: تغليف الأزرار بكلاس للتحكم في تمددها المنظم على الهاتف --}}
    <div class="filter-buttons-group" style="display:flex;gap:8px;">
      <button type="submit"
              style="padding:10px 22px;border-radius:10px;
                     background:linear-gradient(135deg,#0d6b52,#064e3b);
                     border:none;color:#fff;font-family:'Tajawal',sans-serif;
                     font-size:13.5px;font-weight:700;cursor:pointer;white-space:nowrap">
        🔍 بحث
      </button>
      @if($search || $role || $status)
      <a href="{{ route('admin.users.index') }}"
         style="padding:10px 16px;border-radius:10px;
                background:var(--bg);border:1px solid var(--border);
                color:var(--text-m);font-size:13px;font-weight:600;
                text-decoration:none;white-space:nowrap;display:inline-flex;align-items:center">
        ✕ مسح
      </a>
      @endif
    </div>
  </form>
</div>

{{-- ══ جدول المستخدمين ══ --}}
<div class="card" style="overflow:hidden;">
  <div style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
    <table style="width:100%;border-collapse:collapse;font-size:13.5px;min-width:700px;">
      <thead>
        <tr style="background:var(--bg)">
          @foreach(['المستخدم','الدور','الحالة','الأوراد','الحفظ','تاريخ التسجيل','الإجراءات']
                   as $h)
          <th style="padding:13px 16px;text-align:right;font-size:12px;
                     font-weight:700;color:var(--text-m);
                     border-bottom:1px solid var(--border)">
            {{ $h }}
          </th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
        <tr style="border-bottom:1px solid var(--border);
                   transition:background .15s"
            onmouseover="this.style.background='var(--bg)'"
            onmouseout="this.style.background='transparent'">

          {{-- المستخدم --}}
          <td style="padding:14px 16px">
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;
                          display:flex;align-items:center;justify-content:center;
                          font-size:15px;font-weight:700;color:#fff;
                          background:linear-gradient(135deg,#064e3b,#0d6b52)">
                {{ mb_substr($u->name,0,1) }}
              </div>
              <div style="min-width:0">
                <p style="font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $u->name }}</p>
                <p style="font-size:11.5px;color:var(--text-m);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $u->email }}</p>
              </div>
            </div>
          </td>

          {{-- الدور --}}
          <td style="padding:14px 16px;white-space:nowrap">
            <span style="font-size:11.5px;padding:3px 10px;border-radius:100px;
                         font-weight:700;
                         {{ $u->isSuperAdmin()
                            ? 'background:#fdf4ff;color:#7e22ce;border:1px solid #e9d5ff'
                            : ($u->isFamilyAdmin()
                               ? 'background:#fffbeb;color:#92400e;border:1px solid #fde68a'
                               : 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0') }}">
              {{ $u->isSuperAdmin() ? '🌟 مدير عام'
                 : ($u->isFamilyAdmin() ? '👑 مسؤول عائلة' : '📖 عضو') }}
            </span>
          </td>

          {{-- الحالة --}}
          <td style="padding:14px 16px;white-space:nowrap">
            <form method="POST"
                  action="{{ route('admin.users.toggle', $u) }}">
              @csrf
              <button type="submit"
                      style="font-size:11.5px;padding:4px 12px;border-radius:100px;
                             font-weight:700;cursor:pointer;border:none;
                             {{ $u->is_active
                                ? 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0'
                                : 'background:#fef2f2;color:#991b1b;border:1px solid #fecaca' }}">
                {{ $u->is_active ? '✅ نشط' : '⛔ موقوف' }}
              </button>
            </form>
          </td>

          {{-- إحصائيات --}}
          <td style="padding:14px 16px;text-align:center;
                     font-family:'Amiri',serif;font-size:1.1rem;
                     font-weight:700;color:#059669">
            {{ $u->wards_count ?? 0 }}
          </td>
          <td style="padding:14px 16px;text-align:center;
                     font-family:'Amiri',serif;font-size:1.1rem;
                     font-weight:700;color:#2563eb">
            {{ $u->mem_count ?? 0 }}
          </td>

          {{-- التاريخ --}}
          <td style="padding:14px 16px;font-size:12px;color:var(--text-m);white-space:nowrap">
            {{ $u->created_at ? $u->created_at->locale('ar')->isoFormat('D MMM YYYY') : 'غير محدد' }}
          </td>

          {{-- الإجراءات --}}
          <td style="padding:14px 16px">
            <div style="display:flex;align-items:center;gap:6px">

              {{-- عرض --}}
              <a href="{{ route('admin.users.show', $u) }}"
                 style="width:32px;height:32px;border-radius:8px;
                        display:flex;align-items:center;justify-content:center;
                        background:var(--bg);border:1px solid var(--border);
                        text-decoration:none;font-size:14px;transition:all .15s"
                 onmouseover="this.style.background='#ecfdf5';this.style.borderColor='#a7f3d0'"
                 onmouseout="this.style.background='var(--bg)';this.style.borderColor='var(--border)'"
                 title="عرض">👁️</a>

              {{-- إشعار --}}
              <button onclick="openNotifModal({{ $u->id }}, '{{ addslashes($u->name) }}')"
                      style="width:32px;height:32px;border-radius:8px;
                             display:flex;align-items:center;justify-content:center;
                             background:var(--bg);border:1px solid var(--border);
                             cursor:pointer;font-size:14px;transition:all .15s"
                      onmouseover="this.style.background='#fffbeb';this.style.borderColor='#fde68a'"
                      onmouseout="this.style.background='var(--bg)';this.style.borderColor='var(--border)'"
                      title="إرسال إشعار">📨</button>

              {{-- حذف --}}
              @if($u->id !== auth()->id())
              <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                @csrf @method('DELETE')
                <button type="submit"
                        style="width:32px;height:32px;border-radius:8px;
                               display:flex;align-items:center;justify-content:center;
                               background:var(--bg);border:1px solid var(--border);
                               cursor:pointer;font-size:14px;transition:all .15s"
                        onmouseover="this.style.background='#fef2f2';this.style.borderColor='#fecaca'"
                        onmouseout="this.style.background='var(--bg)';this.style.borderColor='var(--border)'"
                        title="حذف">🗑️</button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="padding:48px;text-align:center;color:var(--text-m)">
            لا يوجد مستخدمون
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
  <div style="padding:16px 20px;border-top:1px solid var(--border)">
    {{ $users->links() }}
  </div>
  @endif
</div>

{{-- ══ Modal إشعار فردي ══ --}}
<div id="notifModal"
     style="display:none;position:fixed;inset:0;z-index:500;
            background:rgba(0,0,0,.45);backdrop-filter:blur(4px);
            align-items:center;justify-content:center">
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:24px;padding:28px;max-width:440px;width:90%;
              box-shadow:0 24px 64px rgba(0,0,0,.18)">
    <h3 style="font-family:'Amiri',serif;font-size:1.3rem;font-weight:700;
               color:var(--text);margin-bottom:6px" id="notifTitle">
      إرسال إشعار
    </h3>
    <p style="font-size:13px;color:var(--text-m);margin-bottom:20px" id="notifSubtitle">
      سيصل الإشعار للمستخدم فوراً
    </p>
    <form id="notifForm" method="POST">
      @csrf
      <textarea name="message" rows="4"
                placeholder="اكتب رسالتك هنا..."
                required
                style="width:100%;padding:12px;border:1.5px solid var(--border);
                       border-radius:12px;font-family:'Tajawal',sans-serif;
                       font-size:14px;color:var(--text);background:var(--bg);
                       outline:none;resize:none;margin-bottom:16px"></textarea>
      <div style="display:flex;gap:10px">
        <button type="button" onclick="closeNotifModal()"
                style="flex:1;padding:12px;border-radius:11px;
                       background:var(--bg);border:1.5px solid var(--border);
                       font-family:'Tajawal',sans-serif;font-size:14px;
                       font-weight:600;color:var(--text-m);cursor:pointer">
          إلغاء
        </button>
        <button type="submit"
                style="flex:2;padding:12px;border-radius:11px;
                       background:linear-gradient(135deg,#0d6b52,#064e3b);
                       border:none;color:#fff;font-family:'Tajawal',sans-serif;
                       font-size:14px;font-weight:700;cursor:pointer;
                       box-shadow:0 4px 14px rgba(13,107,82,.3)">
          📨 إرسال
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ══ Modal إشعار جماعي ══ --}}
<div id="broadcastModal"
     style="display:none;position:fixed;inset:0;z-index:500;
            background:rgba(0,0,0,.45);backdrop-filter:blur(4px);
            align-items:center;justify-content:center">
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:24px;padding:28px;max-width:480px;width:90%;
              box-shadow:0 24px 64px rgba(0,0,0,.18)">
    <h3 style="font-family:'Amiri',serif;font-size:1.3rem;font-weight:700;
               color:var(--text);margin-bottom:6px">
      📢 إشعار جماعي
    </h3>
    <p style="font-size:13px;color:var(--text-m);margin-bottom:20px">
      سيصل الإشعار لجميع المستخدمين المحددين
    </p>
    <form method="POST" action="{{ route('admin.users.broadcast') }}">
      @csrf
      <div style="margin-bottom:16px">
        <label style="display:block;font-size:13px;font-weight:600;
                       color:var(--text);margin-bottom:8px">
          المستهدفون
        </label>
        <select name="role"
                style="width:100%;padding:11px 13px;border:1.5px solid var(--border);
                       border-radius:11px;font-family:'Tajawal',sans-serif;
                       font-size:13.5px;color:var(--text);background:var(--bg)">
          <option value="all">جميع المستخدمين</option>
          <option value="family_admin">مسؤولو العائلات فقط</option>
          <option value="member">الأعضاء العاديون فقط</option>
        </select>
      </div>
      <textarea name="message" rows="5"
                placeholder="اكتب رسالتك هنا... مثال: نُعلمكم بأنه سيتم إطلاق ختمة رمضانية جديدة..."
                required
                style="width:100%;padding:12px;border:1.5px solid var(--border);
                       border-radius:12px;font-family:'Tajawal',sans-serif;
                       font-size:14px;color:var(--text);background:var(--bg);
                       outline:none;resize:none;margin-bottom:16px"></textarea>
      <div style="display:flex;gap:10px">
        <button type="button"
                onclick="document.getElementById('broadcastModal').style.display='none'"
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
                       font-size:14px;font-weight:700;cursor:pointer;
                       box-shadow:0 4px 14px rgba(217,119,6,.3)">
          📢 إرسال للجميع
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function openNotifModal(userId, userName) {
  document.getElementById('notifTitle').textContent = `إرسال إشعار إلى: ${userName}`;
  document.getElementById('notifSubtitle').textContent =
    'سيصل الإشعار للمستخدم فوراً في صفحة الإشعارات';
  document.getElementById('notifForm').action = `/admin/users/${userId}/notify`;
  document.getElementById('notifModal').style.display = 'flex';
}
function closeNotifModal() {
  document.getElementById('notifModal').style.display = 'none';
}
document.getElementById('notifModal')
        ?.addEventListener('click', e => {
          if (e.target === e.currentTarget) closeNotifModal();
        });
document.getElementById('broadcastModal')
        ?.addEventListener('click', e => {
          if (e.target === e.currentTarget)
            e.currentTarget.style.display = 'none';
        });
</script>
@endpush

{{-- 💡 التعديل الذكي: التحكم الكامل بالتجاوب للأجهزة الصغيرة من هنا --}}
@push('styles')
<style>
@media (max-width: 768px) {
  /* زر الإشعار الجماعي يأخذ عرضاً كاملاً في الهاتف أسفل العنوان */
  .admin-broadcast-btn {
    width: 100% !important;
  }

  /* جعل كروت الإحصائيات تترتب 2 في كل سطر بشكل أنيق بدلاً من التراكم الضيق */
  .admin-stats-grid {
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 10px !important;
  }
  
  .admin-stat-box {
    padding: 14px 10px !important;
  }
  .admin-stat-box p {
    font-size: 1.5rem !important;
  }

  /* استمارة البحث تترتب عمودياً بشكل منسق مع مسافات (Ecart) لمنع الالتصاق */
  .admin-filter-form {
    flex-direction: column !important;
    align-items: stretch !important;
    gap: 14px !important;
  }
  .filter-field-main, .filter-field-sub, .filter-buttons-group {
    width: 100% !important;
  }
  .filter-buttons-group button, .filter-buttons-group a {
    flex: 1;
    justify-content: center;
    text-align: center;
  }
}
</style>
@endpush