@extends('layouts.app')
@section('title', $title)

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between; margin-bottom:16px;flex-wrap:wrap;gap:12px">
  <a href="{{ route('mushaf.index') }}" style="display:inline-flex;align-items:center;gap:6px; color:var(--text-m);text-decoration:none;font-size:13px">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M19 12H5M12 19l-7-7 7-7"/>
    </svg>
    المصحف
  </a>
  <h2 style="font-family:'Amiri',serif;font-size:1.2rem; font-weight:700;color:var(--text)">{{ $title }}</h2>
  <a href="{{ $url }}" target="_blank" style="font-size:12.5px;color:#059669;font-weight:600;text-decoration:none">
    🔗 فتح في تبويب جديد
  </a>
</div>

<div style="background:var(--card);border:1px solid var(--border); border-radius:16px;overflow:hidden">
  <div style="padding:12px 18px;border-bottom:1px solid var(--border); display:flex;align-items:center;justify-content:center;gap:12px">
    <button id="prevPage" style="padding:7px 16px;border-radius:9px; background:var(--bg);border:1px solid var(--border); font-family:'Tajawal',sans-serif;font-size:13px; font-weight:600;color:var(--text);cursor:pointer">
      ← السابق
    </button>

    <div style="display:inline-flex;align-items:center;gap:6px;font-family:'Tajawal',sans-serif;font-size:14px;color:var(--text)">
      <span>صفحة</span>
      <input type="number" id="pageInput" value="{{ $currentPage }}" min="1" 
             style="width:60px;padding:5px;border-radius:6px;border:1px solid var(--border);background:var(--bg);color:var(--text);text-align:center;font-weight:700;font-size:14px">
      <span id="totalPagesInfo" style="color:var(--text-m)">من ...</span>
    </div>
    
    <button id="nextPage" style="padding:7px 16px;border-radius:9px; background:linear-gradient(135deg,#0d6b52,#064e3b); border:none;color:#fff;font-family:'Tajawal',sans-serif; font-size:13px;font-weight:600;cursor:pointer">
      التالي ←
    </button>
  </div>

  <div style="display:flex;justify-content:center; padding:20px;background:#111;min-height:75vh">
    <canvas id="pdfCanvas" style="max-width:100%;border-radius:8px; box-shadow:0 8px 32px rgba(0,0,0,.4)"></canvas>
  </div>
</div>

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

let pdfDoc    = null;
let pageNum   = {{ $currentPage }}; 
let rendering = false;
const canvas  = document.getElementById('pdfCanvas');
const ctx     = canvas.getContext('2d');
const url     = '{{ $url }}';
const pageInput = document.getElementById('pageInput');

fetch(url)
  .then(response => response.arrayBuffer())
  .then(data => {
    return pdfjsLib.getDocument({ data: data }).promise;
  })
  .then(doc => {
    pdfDoc = doc;
    if (pageNum > pdfDoc.numPages) pageNum = 1; 
    
    // تحديث إجمالي عدد الصفحات في الواجهة
    document.getElementById('totalPagesInfo').textContent = `من ${doc.numPages}`;
    pageInput.max = doc.numPages;
    pageInput.value = pageNum;

    renderPage(pageNum);
  })
  .catch(err => {
    console.error(err);
  });

function renderPage(num) {
  rendering = true;
  pdfDoc.getPage(num).then(page => {
    const scale = window.innerWidth < 768 ? 0.9 : 1.4;
    const vp    = page.getViewport({ scale });
    canvas.width  = vp.width;
    canvas.height = vp.height;
    page.render({ canvasContext: ctx, viewport: vp }).promise.then(() => {
      rendering = false;
      
      pageInput.value = num;
      
      saveCurrentPage(num);
    });
  });
}

function saveCurrentPage(page) {
    fetch('{{ route("mushaf.save-page") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ page: page })
    });
}

// أزرار التنقل التقليدية
document.getElementById('prevPage').addEventListener('click', () => {
  if (pageNum <= 1 || rendering) return;
  renderPage(--pageNum);
});

document.getElementById('nextPage').addEventListener('click', () => {
  if (!pdfDoc || pageNum >= pdfDoc.numPages || rendering) return;
  renderPage(++pageNum);
});

// الحدث الخاص بإدخال رقم الصفحة يدوياً والانتقال الفوري عند الضغط على Enter
pageInput.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') {
    let targetPage = parseInt(pageInput.value);
    
    // التحقق من صحة الرقم المدخل
    if (isNaN(targetPage) || targetPage < 1 || targetPage > pdfDoc.numPages || rendering) {
      pageInput.value = pageNum; // إعادة القيمة القديمة إذا كان الإدخال خاطئاً
      return;
    }
    
    pageNum = targetPage;
    renderPage(pageNum);
  }
});
</script>
@endpush