@extends('layouts.app')
@section('title', 'قراءة — ' . $mushaf->title)

@section('content')

<div style="margin-bottom:16px;display:flex;align-items:center;
            justify-content:space-between;flex-wrap:wrap;gap:12px">
  <a href="{{ route('mushaf.index') }}"
     style="display:inline-flex;align-items:center;gap:6px;
            color:var(--text-m);text-decoration:none;font-size:13px">
    <svg width="14" height="14" fill="none" stroke="currentColor"
         stroke-width="2" viewBox="0 0 24 24">
      <path d="M19 12H5M12 19l-7-7 7-7"/>
    </svg>
    مصحفي
  </a>
  <h2 style="font-family:'Amiri',serif;font-size:1.2rem;
             font-weight:700;color:var(--text)">
    {{ $mushaf->title }}
  </h2>
  <span style="font-size:12px;color:var(--text-m)">
    📍 الصفحة {{ $mushaf->last_page }}
  </span>
</div>

@if($mushaf->file_type === 'pdf')

{{-- ══ عارض PDF ══ --}}
<div style="background:var(--card);border:1px solid var(--border);
            border-radius:16px;overflow:hidden">

  {{-- أدوات التنقل --}}
  <div style="padding:14px 18px;border-bottom:1px solid var(--border);
              display:flex;align-items:center;justify-content:center;gap:14px;
              flex-wrap:wrap">
    <button id="prevPage"
            style="padding:8px 16px;border-radius:9px;
                   background:var(--bg);border:1px solid var(--border);
                   font-family:'Tajawal',sans-serif;font-size:13px;font-weight:600;
                   color:var(--text);cursor:pointer">
      ← السابق
    </button>
    <span id="pageInfo"
          style="font-family:'Amiri',serif;font-size:1.1rem;
                 color:var(--text);font-weight:700;min-width:120px;text-align:center">
      صفحة {{ $mushaf->last_page }}
    </span>
    <button id="nextPage"
            style="padding:8px 16px;border-radius:9px;
                   background:linear-gradient(135deg,#0d6b52,#064e3b);
                   border:none;color:#fff;font-family:'Tajawal',sans-serif;
                   font-size:13px;font-weight:600;cursor:pointer">
      التالي ←
    </button>
  </div>

  {{-- عرض PDF --}}
  <div style="display:flex;justify-content:center;padding:20px;
              background:#1a1a1a;min-height:70vh">
    <canvas id="pdfCanvas"
            style="max-width:100%;border-radius:8px;
                   box-shadow:0 8px 32px rgba(0,0,0,.3)">
    </canvas>
  </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc =
  'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

let pdfDoc    = null;
let pageNum   = {{ $mushaf->last_page }};
let rendering = false;
const canvas  = document.getElementById('pdfCanvas');
const ctx     = canvas.getContext('2d');
const url     = '{{ asset($mushaf->file_url) }}';
const mushafId = {{ $mushaf->id }};

pdfjsLib.getDocument(url).promise.then(doc => {
  pdfDoc = doc;
  renderPage(pageNum);
  document.getElementById('pageInfo').textContent =
    `صفحة ${pageNum} من ${doc.numPages}`;
});

function renderPage(num) {
  rendering = true;
  pdfDoc.getPage(num).then(page => {
    const vp = page.getViewport({ scale: window.innerWidth < 768 ? 1.0 : 1.5 });
    canvas.width  = vp.width;
    canvas.height = vp.height;
    page.render({ canvasContext: ctx, viewport: vp }).promise.then(() => {
      rendering = false;
      document.getElementById('pageInfo').textContent =
        `صفحة ${num} من ${pdfDoc.numPages}`;
      // حفظ الصفحة
      fetch(`/mushaf/${mushafId}/page`, {
        method:'POST',
        headers:{
          'Content-Type':'application/json',
          'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ page: num })
      });
    });
  });
}

document.getElementById('prevPage').addEventListener('click', () => {
  if (pageNum <= 1 || rendering) return;
  pageNum--;
  renderPage(pageNum);
});
document.getElementById('nextPage').addEventListener('click', () => {
  if (!pdfDoc || pageNum >= pdfDoc.numPages || rendering) return;
  pageNum++;
  renderPage(pageNum);
});
</script>
@endpush

@else
{{-- ══ عرض صورة ══ --}}
<div style="background:var(--card);border:1px solid var(--border);
            border-radius:16px;padding:20px;text-align:center">
  <img src="{{ asset($mushaf->file_url) }}" alt="{{ $mushaf->title }}"
       style="max-width:100%;border-radius:12px;
              box-shadow:0 8px 32px rgba(0,0,0,.15)"/>
</div>
@endif

@endsection