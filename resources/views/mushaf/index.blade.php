@extends('layouts.app')
@section('title','المصحف الشريف')

@section('content')

{{-- ══ رأس الصفحة ══ --}}
<div style="margin-bottom:28px">
  <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
    <div style="flex:1;height:1px;background:linear-gradient(to left,transparent,#a7f3d0)"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة"
         style="width:60px;height:60px;object-fit:contain;opacity:.85"/>
    <div style="flex:1;height:1px;background:linear-gradient(to right,transparent,#a7f3d0)"></div>
  </div>
  <div style="text-align:center">
    <h1 style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
               color:var(--text);margin-bottom:8px">
      المصحف الشريف
    </h1>
    <p style="font-size:13.5px;color:var(--text-m);max-width:500px;
              margin:0 auto;line-height:1.8">
      اختر المصدر الذي تريد القراءة منه — جميع المصادر معتمدة وموثوقة
    </p>
  </div>
</div>

{{-- ══ آية ══ --}}
<div style="text-align:center;padding:32px;border-radius:20px;
            background:linear-gradient(135deg,#031810,#042a1e);
            margin-bottom:28px;position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;opacity:.04;
              background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M30 0L60 30L30 60L0 30Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E\");
              background-size:60px"></div>
  <p style="position:relative;font-family:'Amiri',serif;font-size:1.7rem;
            color:rgba(255,255,255,.92);line-height:2;margin-bottom:8px">
    ﴿ إِنَّ هَٰذَا الْقُرْآنَ يَهْدِي لِلَّتِي هِيَ أَقْوَمُ ﴾
  </p>
  <p style="position:relative;font-size:12.5px;color:#f59e0b;opacity:.85">
    سورة الإسراء — الآية ٩
  </p>
</div>

{{-- ══ المصادر المتاحة ══ --}}
<h2 style="font-family:'Amiri',serif;font-size:1.4rem;font-weight:700;
           color:var(--text);margin-bottom:16px;
           display:flex;align-items:center;gap:10px">
  <span style="width:4px;height:24px;border-radius:4px;
               background:linear-gradient(135deg,#064e3b,#10b981);
               display:inline-block"></span>
  مصادر القراءة المتاحة
</h2>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
            gap:18px;margin-bottom:32px">
  @foreach($sources as $src)
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:20px;overflow:hidden;
              transition:box-shadow .25s,transform .25s;cursor:pointer"
       onmouseover="this.style.transform='translateY(-3px)';
                    this.style.boxShadow='0 10px 36px rgba(6,78,59,.10)'"
       onmouseout="this.style.transform='translateY(0)';
                   this.style.boxShadow='none'">

    {{-- رأس البطاقة --}}
    <div style="padding:22px 22px 18px">
      <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:14px">
        <div style="width:56px;height:56px;border-radius:14px;flex-shrink:0;
                    display:flex;align-items:center;justify-content:center;
                    font-size:26px;
                    background:{{ $src['color']['bg'] }};
                    border:1.5px solid {{ $src['color']['border'] }}">
          {{ $src['icon'] }}
        </div>
        <div style="flex:1">
          <h3 style="font-size:15px;font-weight:700;color:var(--text);
                     margin-bottom:5px">{{ $src['title'] }}</h3>
          <p style="font-size:12.5px;color:var(--text-m);line-height:1.6">
            {{ $src['description'] }}
          </p>
        </div>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;
                  padding-top:14px;border-top:1px solid var(--border)">
        <span style="font-size:11px;padding:3px 10px;border-radius:100px;
                     font-weight:700;
                     background:{{ $src['color']['bg'] }};
                     color:{{ $src['color']['text'] }};
                     border:1px solid {{ $src['color']['border'] }}">
          {{ $src['type'] === 'pdf' ? '📄 PDF' : '🌐 موقع ويب' }}
        </span>

        @if($src['type'] === 'pdf')
        {{-- فتح PDF داخل المنصة --}}
        <a href="{{ route('mushaf.reader', ['url' => $src['url']]) }}"
           style="display:inline-flex;align-items:center;gap:7px;
                  padding:9px 18px;border-radius:10px;
                  background:linear-gradient(135deg,#0d6b52,#064e3b);
                  color:#fff;font-size:13px;font-weight:700;
                  text-decoration:none;
                  box-shadow:0 3px 12px rgba(13,107,82,.3)">
          📖 فتح للقراءة
        </a>
        @else
        {{-- فتح الموقع في iframe أو تبويب جديد --}}
        <div style="display:flex;gap:8px">
          <button onclick="openInFrame('{{ $src['url'] }}', '{{ $src['title'] }}')"
                  style="padding:9px 14px;border-radius:10px;
                         background:linear-gradient(135deg,#0d6b52,#064e3b);
                         border:none;color:#fff;font-size:13px;font-weight:700;
                         cursor:pointer;font-family:'Tajawal',sans-serif">
            📖 فتح هنا
          </button>
          <a href="{{ $src['url'] }}" target="_blank"
             style="padding:9px 12px;border-radius:10px;
                    background:var(--bg);border:1px solid var(--border);
                    color:var(--text-m);font-size:13px;text-decoration:none;
                    display:flex;align-items:center">
            🔗
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- ══ عارض iframe ══ --}}
<div id="frameContainer"
     style="display:none;background:var(--card);
            border:1px solid var(--border);
            border-radius:20px;overflow:hidden;margin-bottom:24px">

  {{-- شريط العنوان --}}
  <div style="padding:14px 18px;border-bottom:1px solid var(--border);
              display:flex;align-items:center;justify-content:space-between;
              background:linear-gradient(135deg,#022c22,#064e3b)">
    <div style="display:flex;align-items:center;gap:10px">
      <span style="font-size:18px">📖</span>
      <p id="frameTitle" style="color:#fff;font-size:14px;font-weight:700">
        المصحف الشريف
      </p>
    </div>
    <div style="display:flex;gap:10px">
      <a id="frameLink" href="#" target="_blank"
         style="padding:7px 14px;border-radius:8px;
                background:rgba(255,255,255,.12);
                color:rgba(255,255,255,.8);font-size:12.5px;
                font-weight:600;text-decoration:none;
                border:1px solid rgba(255,255,255,.2)">
        🔗 فتح في تبويب
      </a>
      <button onclick="closeFrame()"
              style="padding:7px 14px;border-radius:8px;
                     background:rgba(239,68,68,.2);
                     border:1px solid rgba(239,68,68,.3);
                     color:#fca5a5;font-size:12.5px;font-weight:700;
                     cursor:pointer;font-family:'Tajawal',sans-serif">
        ✕ إغلاق
      </button>
    </div>
  </div>

  {{-- الـ iframe --}}
  <iframe id="mushafFrame"
          src=""
          style="width:100%;height:80vh;border:none;display:block"
          allowfullscreen
          title="المصحف الشريف">
  </iframe>
</div>

{{-- ══ الإشارة المرجعية ══ --}}
@if($bookmark)
<div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);
            border:1px solid #fde68a;border-radius:16px;
            padding:18px 22px;
            display:flex;align-items:center;gap:14px">
  <span style="font-size:24px;flex-shrink:0">📍</span>
  <div style="flex:1">
    <p style="font-size:14px;font-weight:700;color:#78350f;margin-bottom:3px">
      توقفت عند
    </p>
    <p style="font-size:13px;color:#92400e">
      سورة رقم {{ $bookmark->surah_number }}
      — آية {{ $bookmark->ayah_number }}
    </p>
  </div>
  @if($bookmark->note)
  <p style="font-size:12px;color:#b45309;
             border-right:2px solid #fde68a;padding-right:14px">
    {{ $bookmark->note }}
  </p>
  @endif
</div>
@endif

{{-- ══ نصائح للقراء ══ --}}
<div style="margin-top:24px;display:grid;
            grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px">
  @foreach([
    ['🌙','ورد اليومي','سجّل قراءتك اليومية وحافظ على سلسلة الأيام المتتالية',route('ward.index')],
    ['🧠','الحفظ','سجّل ما وفّقك الله لحفظه وتابع مراجعاتك',route('memorizations.index')],
    ['📚','الختمات','شارك في ختمة جماعية مع أفراد عائلتك',route('khatmas.index')],
  ] as [$icon,$title,$desc,$url])
  <a href="{{ $url }}"
     style="padding:18px;border-radius:16px;
            background:var(--card);border:1px solid var(--border);
            text-decoration:none;transition:all .2s"
     onmouseover="this.style.transform='translateY(-2px)';
                  this.style.boxShadow='0 6px 20px rgba(6,78,59,.08)'"
     onmouseout="this.style.transform='translateY(0)';
                 this.style.boxShadow='none'">
    <span style="font-size:24px;display:block;margin-bottom:10px">{{ $icon }}</span>
    <p style="font-size:13.5px;font-weight:700;color:var(--text);margin-bottom:5px">
      {{ $title }}
    </p>
    <p style="font-size:12px;color:var(--text-m);line-height:1.6">{{ $desc }}</p>
  </a>
  @endforeach
</div>

@endsection

@push('scripts')
<script>
function openInFrame(url, title) {
  const container = document.getElementById('frameContainer');
  const frame     = document.getElementById('mushafFrame');
  const titleEl   = document.getElementById('frameTitle');
  const linkEl    = document.getElementById('frameLink');

  titleEl.textContent = title;
  linkEl.href         = url;
  frame.src           = url;
  container.style.display = 'block';
  container.scrollIntoView({ behavior:'smooth', block:'start' });
}

function closeFrame() {
  const frame     = document.getElementById('mushafFrame');
  const container = document.getElementById('frameContainer');
  frame.src = '';
  container.style.display = 'none';
}
</script>
@endpush

@push('styles')
<style>
@media(max-width:640px){
  div[style*="grid-template-columns:repeat(3,1fr)"]{
    grid-template-columns:1fr !important;
  }
}
</style>
@endpush