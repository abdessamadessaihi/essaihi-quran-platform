@extends('layouts.app')
@section('title', 'تفاصيل الختمة')

@section('content')

{{-- ══ Header ══ --}}
@if (session('success'))
<div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: bold;">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div style="background: #fef2f2; border: 1px solid #f87171; color: #b91c1c; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: bold;">
    {{ session('error') }}
</div>
@endif

<div class="page-header" style="margin-bottom:28px">
  <div class="page-header-ornament">
    <div class="ornament-line"></div>
    <span class="ornament-icon">✦</span>
    <div class="ornament-line"></div>
  </div>
  <div style="display:flex;align-items:flex-start;
              justify-content:space-between;gap:16px;flex-wrap:wrap">
    <div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
        <a href="{{ route('khatmas.index') }}"
           style="color:var(--text-m);text-decoration:none;font-size:13px;
                  display:flex;align-items:center;gap:4px">
          <svg width="14" height="14" fill="none" stroke="currentColor"
               stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          الختمات
        </a>
        <span style="color:var(--border)">/</span>
        <span style="font-size:13px;color:var(--text)">{{ $khatma->title }}</span>
      </div>
      <h1 class="page-title">{{ $khatma->title }}</h1>
      <div style="display:flex;align-items:center;gap:12px;margin-top:6px;flex-wrap:wrap">
        <span style="font-size:11.5px;padding:3px 12px;border-radius:100px;
                     background:#fdf4ff;color:#7e22ce;border:1px solid #e9d5ff;
                     font-weight:600">{{ match($khatma->type) {
              'ramadan' => '🌙 رمضان',
              'weekly'  => '📅 أسبوعية',
              'family'  => '👨‍👩‍👧 عائلية',
              'individual'=>'👤 فردية',
              default   => '📚 عامة',
            } }}</span>
        <span style="display:flex;align-items:center;gap:5px;
                     font-size:12px;color:var(--text-m)">
          <span style="width:7px;height:7px;border-radius:50%;
                       background:{{ $khatma->status === 'active' ? '#10b981' : '#64748b' }};display:inline-block"></span>
          {{ $khatma->status === 'active' ? 'نشطة حالياً' : 'غير نشطة' }}
        </span>
        <span style="font-size:12px;color:var(--text-m)">
          📅 {{ $khatma->starts_at ? $khatma->starts_at->format('Y-m-d') : 'غير محدد' }} — {{ $khatma->ends_at ? $khatma->ends_at->format('Y-m-d') : 'مفتوحة' }}
        </span>
      </div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <button onclick="copyShareLink()"
              style="display:inline-flex;align-items:center;gap:7px;
                     padding:10px 18px;border-radius:11px;
                     background:var(--card);border:1.5px solid var(--border);
                     font-family:'Tajawal',sans-serif;font-size:13px;
                     font-weight:600;color:var(--text);cursor:pointer">
        📤 مشاركة
      </button>
      
      @if($khatma->created_by === auth()->id())
      <form action="{{ route('khatmas.destroy', $khatma->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الختمة؟ لا يمكن التراجع عن هذا الإجراء.');" style="margin:0;">
        @csrf
        @method('DELETE')
        <button type="submit"
                style="display:inline-flex;align-items:center;gap:7px;
                       padding:10px 18px;border-radius:11px;
                       background:#fef2f2;border:1.5px solid #f87171;
                       font-family:'Tajawal',sans-serif;font-size:13px;
                       font-weight:600;color:#b91c1c;cursor:pointer">
          🗑️ حذف الختمة
        </button>
      </form>
      @endif
    </div>
  </div>
</div>

{{-- ══ إحصائيات سريعة ══ --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);
            gap:14px;margin-bottom:28px">
  @foreach([
    [$khatma->completed_juz_count,'جزءاً مكتملاً','من ٣٠','#ecfdf5','#a7f3d0','#059669'],
    [30 - $khatma->completed_juz_count, 'أجزاء متبقية','حتى الختم','#fffbeb','#fde68a','#d97706'],
    [$khatma->juzAllocations->whereNotNull('user_id')->unique('user_id')->count(),'مشاركاً','عضو نشط','#eff6ff','#bfdbfe','#2563eb'],
    [$khatma->completion_percentage,'٪ مكتمل','نسبة الإنجاز','#fdf4ff','#e9d5ff','#7e22ce'],
  ] as [$v,$l,$s,$bg,$border,$vc])
  <div style="background:{{ $bg }};border:1px solid {{ $border }};
              border-radius:16px;padding:20px 18px;text-align:center">
    <p style="font-family:'Amiri',serif;font-size:2rem;
              font-weight:700;color:{{ $vc }};line-height:1;
              margin-bottom:5px">{{ $v }}</p>
    <p style="font-size:12.5px;font-weight:700;color:var(--text);
              margin-bottom:2px">{{ $l }}</p>
    <p style="font-size:11px;color:var(--text-m)">{{ $s }}</p>
  </div>
  @endforeach
</div>

{{-- ══ الشبكة التفاعلية للأجزاء ══ --}}
<div style="background:var(--card);border:1px solid var(--border);
            border-radius:20px;overflow:hidden;margin-bottom:24px">

  {{-- رأس القسم --}}
  <div style="padding:20px 24px;border-bottom:1px solid var(--border);
              display:flex;align-items:center;justify-content:space-between;
              flex-wrap:wrap;gap:12px">
    <div style="display:flex;align-items:center;gap:10px">
      <div style="width:36px;height:36px;border-radius:10px;
                  background:linear-gradient(135deg,#ecfdf5,#d1fae5);
                  display:flex;align-items:center;justify-content:center;
                  font-size:18px">📖</div>
      <div>
        <p style="font-size:14.5px;font-weight:700;color:var(--text)">
          شبكة الأجزاء الثلاثين
        </p>
        <p style="font-size:11.5px;color:var(--text-m);margin-top:2px">
          اضغط على الجزء المتاح لحجزه
        </p>
      </div>
    </div>
    {{-- مفتاح الألوان --}}
    <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
      @foreach([
        ['#e2e8f0','#64748b','متاح'],
        ['#bfdbfe','#1d4ed8','محجوز'],
        ['#fde68a','#92400e','قيد القراءة'],
        ['#6ee7b7','#065f46','مكتمل'],
      ] as [$bg2,$tc,$lbl])
      <div style="display:flex;align-items:center;gap:5px">
        <div style="width:14px;height:14px;border-radius:4px;
                    background:{{ $bg2 }}"></div>
        <span style="font-size:11.5px;color:var(--text-m)">{{ $lbl }}</span>
      </div>
      @endforeach
    </div>
  </div>

  {{-- الشبكة --}}
  <div style="padding:28px 24px">
    <div style="display:grid;grid-template-columns:repeat(6,1fr);
                gap:12px" id="juzGrid">
      @php
        $allocations = $khatma->juzAllocations->keyBy('juz_number');
        $juzNames = [
          'الفاتحة','البقرة','آل عمران','النساء','المائدة','الأنعام',
          'الأعراف','الأنفال','التوبة','يونس','هود','يوسف',
          'الرعد','إبراهيم','الحجر','النحل','الإسراء','الكهف',
          'مريم','طه','الأنبياء','الحج','المؤمنون','النور',
          'الفرقان','الشعراء','النمل','القصص','العنكبوت','الروم',
        ];
      @endphp
      @for($i = 1; $i <= 30; $i++)
      @php
        $alloc = $allocations->get($i);
        $st = $alloc ? $alloc->status : 'available';
        $isMine = $alloc && $alloc->user_id === auth()->id() ? 'true' : 'false';
        $cfg = match($st) {
          'completed' => ['#ecfdf5','#6ee7b7','#065f46','✓',  'مكتمل'],
          'reading'   => ['#fffbeb','#fde68a','#92400e','◐',  'قيد القراءة'],
          'reserved'  => ['#eff6ff','#bfdbfe','#1d4ed8','◉',  'محجوز'],
          default     => ['#f8fafc','#e2e8f0','#64748b','',   'متاح'],
        };
      @endphp
      <div class="juz-cell-full"
           style="background:{{ $cfg[0] }};border:1.5px solid {{ $cfg[1] }};
                  color:{{ $cfg[2] }}; {{ ($st === 'available' || $isMine === 'true') ? 'cursor:pointer;' : 'cursor:not-allowed; opacity:0.8;' }}"
           title="{{ $juzNames[$i-1] }} - {{ $cfg[4] }}"
           onclick="handleJuzClick({{ $i }}, '{{ $st }}', '{{ $isMine }}')">
        <span class="juz-num">{{ $i }}</span>
        <span class="juz-status-icon">{{ $cfg[3] }}</span>
        <span class="juz-label">جزء</span>
        <span class="juz-surah">{{ mb_substr($juzNames[$i-1], 0, 6) }}</span>
      </div>
      @endfor
    </div>
  </div>
</div>

{{-- ══ المشاركون ══ --}}
<div style="background:var(--card);border:1px solid var(--border);
            border-radius:20px;overflow:hidden">
  <div style="padding:18px 24px;border-bottom:1px solid var(--border);
              display:flex;align-items:center;gap:10px">
    <div style="width:34px;height:34px;border-radius:9px;
                background:linear-gradient(135deg,#fffbeb,#fef3c7);
                display:flex;align-items:center;justify-content:center;
                font-size:17px">👥</div>
    <p style="font-size:14px;font-weight:700;color:var(--text)">
      المشاركون ({{ $khatma->juzAllocations->whereNotNull('user_id')->unique('user_id')->count() }} أعضاء)
    </p>
  </div>
  <div style="padding:20px 24px">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
                gap:12px">
      @forelse($khatma->juzAllocations->whereNotNull('user_id')->groupBy('user_id') as $userId => $userAllocs)
      @php
        $user = $userAllocs->first()->user;
        $name = $user ? $user->name : 'مجهول';
        $juzes = $userAllocs->count();
        $st = $userAllocs->contains('status', 'reading') ? 'reading' : ($userAllocs->contains('status', 'reserved') ? 'reserved' : 'completed');
        $stColors = [
          'completed'=>['#ecfdf5','#059669','مكتمل'],
          'reading'  =>['#fffbeb','#d97706','يقرأ'],
          'reserved' =>['#eff6ff','#2563eb','محجوز'],
          'available'=>['#f8fafc','#64748b','لم يحجز'],
        ];
        [$stBg,$stC,$stL] = $stColors[$st];
      @endphp
      <div style="display:flex;align-items:center;gap:12px;
                  padding:14px;border-radius:14px;
                  background:var(--bg);border:1px solid var(--border)">
        <div style="width:40px;height:40px;border-radius:11px;flex-shrink:0;
                    display:flex;align-items:center;justify-content:center;
                    font-size:16px;font-weight:700;color:#fff;
                    background:linear-gradient(135deg,#064e3b,#0d6b52)">
          {{ mb_substr($name, 0, 1) }}
        </div>
        <div style="flex:1;min-width:0">
          <p style="font-size:13px;font-weight:700;color:var(--text);
                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
            {{ $name }}
          </p>
          <div style="display:flex;align-items:center;gap:6px;margin-top:3px">
            <span style="font-size:10.5px;padding:2px 8px;border-radius:100px;
                         background:{{ $stBg }};color:{{ $stC }};font-weight:600">
              {{ $stL }}
            </span>
            <span style="font-size:11px;color:var(--text-m)">
              {{ $juzes }} {{ $juzes > 1 ? 'أجزاء' : 'جزء' }}
            </span>
          </div>
        </div>
      </div>
      @empty
        <p style="font-size:13px;color:var(--text-m)">لا يوجد مشاركين حتى الآن.</p>
      @endforelse
    </div>
  </div>
</div>

{{-- Modal حجز الجزء --}}
<div id="juzModal"
     style="display:none;position:fixed;inset:0;z-index:500;
            background:rgba(0,0,0,.45);backdrop-filter:blur(4px);
            align-items:center;justify-content:center">
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:24px;padding:32px;max-width:380px;width:90%;
              box-shadow:0 24px 64px rgba(0,0,0,.18);
              animation:modalIn .25s ease"
       @click.stop>
    <div style="text-align:center;margin-bottom:24px">
      <div style="width:64px;height:64px;border-radius:18px;
                  background:linear-gradient(135deg,#ecfdf5,#d1fae5);
                  border:2px solid #a7f3d0;
                  display:flex;align-items:center;justify-content:center;
                  font-size:28px;margin:0 auto 16px">📖</div>
      <h3 style="font-family:'Amiri',serif;font-size:1.4rem;
                 font-weight:700;color:var(--text);margin-bottom:6px"
          id="modalTitle">حجز الجزء ٢٥</h3>
      <p style="font-size:13px;color:var(--text-m)" id="modalDesc">
        هل تريد حجز هذا الجزء والبدء في قراءته؟
      </p>
    </div>
    <div style="display:flex;gap:10px">
      <button onclick="closeModal()" type="button"
              style="flex:1;padding:12px;border-radius:12px;
                     background:var(--bg);border:1.5px solid var(--border);
                     font-family:'Tajawal',sans-serif;font-size:14px;
                     font-weight:600;color:var(--text-m);cursor:pointer">
        إلغاء
      </button>
      <form id="actionForm" method="POST" action="" style="flex:2;margin:0;">
        @csrf
        <button id="confirmBtn" type="submit"
                style="width:100%;padding:12px;border-radius:12px;
                       background:linear-gradient(135deg,#0d6b52,#065f46);
                       border:none;color:#fff;
                       font-family:'Tajawal',sans-serif;font-size:14px;
                       font-weight:700;cursor:pointer;
                       box-shadow:0 4px 16px rgba(13,107,82,.35)">
          ✓ تأكيد الحجز
        </button>
      </form>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
.juz-cell-full {
  aspect-ratio: 1; border-radius: 14px;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  cursor: pointer; transition: all .22s;
  position: relative; overflow: hidden;
  gap: 2px;
}
.juz-cell-full::after {
  content: '';
  position: absolute; inset: 0; border-radius: 14px;
  background: rgba(255,255,255,0);
  transition: background .18s;
}
.juz-cell-full:hover { transform: scale(1.06); box-shadow: 0 6px 20px rgba(0,0,0,.12); }
.juz-cell-full:hover::after { background: rgba(255,255,255,.15); }
.juz-num  { font-size: 1.1rem; font-weight: 800; line-height: 1; }
.juz-status-icon { font-size: 10px; line-height: 1; }
.juz-label{ font-size: 9px; opacity: .6; }
.juz-surah{ font-size: 9px; opacity: .7; }

@keyframes modalIn {
  from { opacity: 0; transform: scale(.94) translateY(10px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}
</style>
@endpush

@push('scripts')
<script>
let selectedJuz = null;
const khatmaId = {{ $khatma->id }};

function handleJuzClick(num, status, isMine) {
  if (status !== 'available' && isMine !== 'true') return;
  
  selectedJuz = num;
  
  const titleEl = document.getElementById('modalTitle');
  const descEl = document.getElementById('modalDesc');
  const btnEl = document.getElementById('confirmBtn');
  const formEl = document.getElementById('actionForm');

  if (status === 'available') {
    titleEl.textContent = 'حجز الجزء ' + num;
    descEl.textContent = 'هل تريد حجز هذا الجزء والبدء في قراءته؟';
    btnEl.textContent = '✓ تأكيد الحجز';
    formEl.action = `/khatmas/${khatmaId}/juz/${num}/claim`;
  } else if (status === 'reserved') {
    titleEl.textContent = 'بدء قراءة الجزء ' + num;
    descEl.textContent = 'هل أنت مستعد لبدء قراءة هذا الجزء الآن؟';
    btnEl.textContent = '📖 بدء القراءة';
    formEl.action = `/khatmas/${khatmaId}/juz/${num}/start`;
  } else if (status === 'reading') {
    titleEl.textContent = 'إتمام الجزء ' + num;
    descEl.textContent = 'هل أتممت قراءة هذا الجزء بفضل الله؟';
    btnEl.textContent = '🎉 تأكيد الإكمال';
    formEl.action = `/khatmas/${khatmaId}/juz/${num}/complete`;
  } else {
    return;
  }

  const modal = document.getElementById('juzModal');
  modal.style.display = 'flex';
}

function closeModal() {
  document.getElementById('juzModal').style.display = 'none';
}

function copyShareLink() {
  navigator.clipboard.writeText(window.location.href).then(() => {
    alert('تم نسخ رابط الختمة للمشاركة بنجاح! 📋');
  });
}

document.getElementById('juzModal').addEventListener('click', closeModal);
</script>
@endpush