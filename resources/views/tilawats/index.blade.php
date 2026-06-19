@extends('layouts.app')
@section('title', 'مكتبة التلاوات الخاشعة')

@push('styles')
<style>
/* ============================================================
   TILAWAT PAGE — منصة آل السيحي القرآنية
============================================================ */
:root {
    --tw-green-deep:   #042a1e;
    --tw-green-main:   #0d6b52;
    --tw-green-mid:    #065f46;
    --tw-green-light:  #ecfdf5;
    --tw-gold:         #d97706;
    --tw-gold-bg:      rgba(217,119,6,.10);
    --tw-gold-border:  rgba(217,119,6,.28);
    --tw-card-bg:      var(--card, #ffffff);
    --tw-border:       var(--border, #e5e7eb);
    --tw-text:         var(--text, #111827);
    --tw-text-muted:   var(--text-m, #6b7280);
}

/* wrapper */
.tl-page { max-width: 1160px; margin: 0 auto; padding: 0 0 60px; }

/* -------- HERO CARD -------- */
.tl-hero {
    position: relative; overflow: hidden;
    background: var(--tw-green-deep);
    border-radius: 20px;
    padding: 40px 36px;
    margin-bottom: 36px;
    border: 1px solid rgba(255,255,255,.06);
}
.tl-hero-pat {
    position: absolute; inset: 0; opacity: .05;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='64'%3E%3Cpath d='M32 0L64 32L32 64L0 32Z' fill='none' stroke='%23fff' stroke-width='1'/%3E%3Ccircle cx='32' cy='32' r='13' fill='none' stroke='%23fff' stroke-width='.6'/%3E%3C/svg%3E");
    background-size: 64px;
}
.tl-hero-glow {
    position: absolute; top: -80px; left: -80px;
    width: 300px; height: 300px; border-radius: 50%;
    background: radial-gradient(circle, rgba(13,107,82,.35), transparent 70%);
}
.tl-hero-inner {
    position: relative; z-index: 2;
    display: flex; align-items: center; gap: 22px;
}
.tl-hero-icon {
    width: 66px; height: 66px; flex-shrink: 0;
    border-radius: 16px;
    background: rgba(255,255,255,.09);
    border: 1px solid rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center;
    font-size: 30px;
}
.tl-hero h1 {
    font-family: 'Amiri', serif;
    font-size: clamp(1.4rem, 2.8vw, 1.9rem);
    font-weight: 700; color: #fff; margin: 0 0 8px;
}
.tl-hero p {
    font-size: 13.5px; color: rgba(255,255,255,.68);
    line-height: 1.8; margin: 0; max-width: 560px;
}
.tl-hero-stats {
    margin-right: auto; flex-shrink: 0;
    display: flex; gap: 24px;
}
.tl-hs {
    text-align: center;
    padding: 10px 18px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px;
}
.tl-hs-n {
    font-family: 'Amiri', serif;
    font-size: 1.6rem; font-weight: 700; color: #fcd34d;
    line-height: 1;
}
.tl-hs-l { font-size: 11px; color: rgba(255,255,255,.55); margin-top: 3px; }

/* -------- ADMIN UPLOAD BOX -------- */
.tl-admin-box {
    background: var(--tw-card-bg);
    border: 1.5px dashed var(--tw-green-main);
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 32px;
}
.tl-admin-head {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 20px; padding-bottom: 14px;
    border-bottom: 1px solid var(--tw-border);
}
.tl-admin-head h2 {
    font-size: 15px; font-weight: 700;
    color: var(--tw-text); margin: 0;
}
.tl-admin-badge {
    padding: 3px 10px; border-radius: 100px;
    background: rgba(13,107,82,.10);
    color: var(--tw-green-main);
    font-size: 11px; font-weight: 700;
    border: 1px solid rgba(13,107,82,.2);
}
.tl-alert-success {
    display: flex; align-items: center; gap: 8px;
    background: #d1fae5; color: #065f46;
    border: 1px solid #6ee7b7;
    padding: 10px 16px; border-radius: 10px;
    margin-bottom: 16px; font-size: 13px; font-weight: 600;
}
.tl-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 14px; margin-bottom: 14px;
}
.tl-field label {
    display: block; font-size: 12px; font-weight: 700;
    color: var(--tw-text-muted); margin-bottom: 5px;
}
.tl-field input[type="text"],
.tl-field input[type="url"],
.tl-field input[type="file"] {
    width: 100%; padding: 9px 12px;
    border: 1px solid var(--tw-border);
    border-radius: 8px;
    background: var(--tw-card-bg);
    font-family: 'Tajawal', sans-serif;
    font-size: 13px; color: var(--tw-text);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.tl-field input:focus {
    border-color: var(--tw-green-main);
    box-shadow: 0 0 0 3px rgba(13,107,82,.10);
}
.tl-media-box {
    background: rgba(0,0,0,.022);
    border: 1px solid var(--tw-border);
    border-radius: 10px;
    padding: 16px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px; margin-bottom: 14px;
}
.tl-media-or {
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700;
    color: var(--tw-text-muted); padding: 0 4px;
}
.tl-form-footer {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap; gap: 12px;
    padding-top: 14px;
    border-top: 1px solid var(--tw-border);
}
.tl-check-label {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 600;
    color: var(--tw-text); cursor: pointer;
}
.tl-check-label input { accent-color: var(--tw-green-main); cursor: pointer; }
.tl-submit-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 24px; border-radius: 9px;
    background: var(--tw-green-main); color: #fff;
    font-family: 'Tajawal', sans-serif;
    font-size: 13.5px; font-weight: 700;
    border: none; cursor: pointer;
    transition: background .2s, transform .15s;
}
.tl-submit-btn:hover { background: #0a4d3c; transform: translateY(-1px); }

/* -------- SECTION HEADER -------- */
.tl-section { margin-bottom: 36px; }
.tl-sec-head {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 24px;
}
.tl-sec-icon { font-size: 20px; flex-shrink: 0; }
.tl-sec-head h2 {
    font-family: 'Amiri', serif;
    font-size: 19px; font-weight: 700;
    color: var(--tw-text); margin: 0; white-space: nowrap;
}
.tl-sec-line {
    flex: 1; height: 1px;
    background: var(--tw-border);
}
.tl-sec-line.gold { background: linear-gradient(to left, transparent, var(--tw-gold)); }
.tl-sec-count {
    font-size: 12px; font-weight: 700;
    padding: 3px 12px; border-radius: 100px;
    background: var(--tw-gold-bg);
    color: var(--tw-gold);
    border: 1px solid var(--tw-gold-border);
    white-space: nowrap;
}
.tl-sec-count.green {
    background: rgba(13,107,82,.08);
    color: var(--tw-green-main);
    border-color: rgba(13,107,82,.2);
}

/* -------- GRIDS -------- */
.tl-grid-featured {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
}
.tl-grid-all {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(275px, 1fr));
    gap: 18px;
}

/* -------- CARDS -------- */
.tl-card {
    background: var(--tw-card-bg);
    border: 1px solid var(--tw-border);
    border-radius: 16px; overflow: hidden;
    display: flex; flex-direction: column;
    position: relative;
    transition: transform .25s, box-shadow .25s, border-color .25s;
}
.tl-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 36px rgba(0,0,0,.07);
    border-color: #a7f3d0;
}
.tl-card.featured { border: 1.5px solid var(--tw-gold); }
.tl-card.featured:hover { border-color: var(--tw-gold); box-shadow: 0 12px 36px rgba(217,119,6,.12); }

/* media */
.tl-media {
    position: relative; width: 100%; padding-top: 56.25%;
    background: #0f172a;
}
.tl-media iframe,
.tl-media video {
    position: absolute; inset: 0;
    width: 100%; height: 100%; border: 0;
}
.tl-media-err {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    color: #ef4444; font-size: 13px;
    background: #1f2937;
}

/* audio */
.tl-audio {
    position: relative; width: 100%; height: 150px;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 10px;
}
.tl-audio-disc {
    font-size: 32px;
    animation: tl-pulse 2.5s ease-in-out infinite;
}
@keyframes tl-pulse { 0%,100%{transform:scale(1);opacity:.8} 50%{transform:scale(1.12);opacity:1} }
.tl-audio audio {
    width: 88%; border-radius: 30px; height: 32px;
}

/* badge type */
.tl-type-badge {
    position: absolute; top: 10px; left: 10px; z-index: 10;
    font-size: 10px; font-weight: 700;
    padding: 3px 9px; border-radius: 6px;
    letter-spacing: .4px;
}
.tl-type-yt   { background: rgba(239,68,68,.88); color: #fff; }
.tl-type-mp4  { background: rgba(13,107,82,.88); color: #fff; }
.tl-type-mp3  { background: rgba(30,64,175,.88); color: #fff; }

/* featured star */
.tl-featured-star {
    position: absolute; top: 10px; right: 10px; z-index: 10;
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--tw-gold);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; box-shadow: 0 2px 8px rgba(217,119,6,.4);
}

/* admin delete */
.tl-del-btn {
    position: absolute; top: 10px; right: 10px; z-index: 20;
    width: 30px; height: 30px; border-radius: 8px;
    background: rgba(239,68,68,.90); border: none;
    color: #fff; font-size: 14px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s, transform .1s;
}
.tl-del-btn:hover { background: #dc2626; transform: scale(1.07); }

/* card body */
.tl-card-body { padding: 16px 18px; display: flex; flex-direction: column; flex: 1; }
.tl-surah-badge {
    align-self: flex-start;
    font-size: 11px; font-weight: 700;
    padding: 3px 11px; border-radius: 100px;
    margin-bottom: 9px;
}
.tl-surah-badge.gold {
    background: var(--tw-gold-bg); color: var(--tw-gold);
    border: 1px solid var(--tw-gold-border);
}
.tl-surah-badge.green {
    background: rgba(13,107,82,.09); color: var(--tw-green-main);
    border: 1px solid rgba(13,107,82,.2);
}
.tl-card-title {
    font-size: 14px; font-weight: 700;
    color: var(--tw-text); line-height: 1.55;
    margin: 0 0 12px; flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
}
.tl-card-footer {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 10px; border-top: 1px solid var(--tw-border);
    font-size: 12px;
}
.tl-reciter { color: var(--tw-text-muted); font-weight: 500; }
.tl-views { color: #9ca3af; }

/* -------- EMPTY STATE -------- */
.tl-empty {
    text-align: center; padding: 56px 20px;
    border: 1.5px dashed var(--tw-border);
    border-radius: 16px;
    background: rgba(0,0,0,.014);
}
.tl-empty-icon { font-size: 44px; margin-bottom: 12px; }
.tl-empty p { font-size: 14px; color: var(--tw-text-muted); }

/* -------- PAGINATION -------- */
.tl-pagination { margin-top: 32px; display: flex; justify-content: center; }

/* -------- RESPONSIVE -------- */
@media (max-width: 768px) {
    .tl-hero-inner { flex-wrap: wrap; }
    .tl-hero-stats { margin-right: 0; }
    .tl-grid-featured, .tl-grid-all { grid-template-columns: 1fr; }
    .tl-media-box { grid-template-columns: 1fr; }
    .tl-media-or { display: none; }
}
</style>
@endpush

@section('content')
<div class="tl-page">

{{-- ====================== HERO ====================== --}}
<div class="tl-hero">
    <div class="tl-hero-pat"></div>
    <div class="tl-hero-glow"></div>
    <div class="tl-hero-inner">
        <div class="tl-hero-icon">📖</div>
        <div>
            <h1 class="tl-hero">مكتبة التلاوات الخاشعة</h1>
            <p>منصة صوتية ومرئية متكاملة تضم روائع القراءات العذبة والتلاوات المؤثرة لقراء العائلة وقراء العالم الإسلامي.</p>
        </div>
        <div class="tl-hero-stats">
            <div class="tl-hs">
                <div class="tl-hs-n font-amiri">{{ isset($featured) ? $featured->count() : 0 }}</div>
                <div class="tl-hs-l">تلاوة مختارة</div>
            </div>
            <div class="tl-hs">
                <div class="tl-hs-n font-amiri">{{ isset($tilawats) ? $tilawats->total() : 0 }}</div>
                <div class="tl-hs-l">إجمالي التلاوات</div>
            </div>
        </div>
    </div>
</div>

{{-- ====================== ADMIN BOX ====================== --}}
@if(isset($isAdmin) && $isAdmin)
<div class="tl-admin-box">
    <div class="tl-admin-head">
        <span style="font-size:18px">⚙️</span>
        <h2>رفع تلاوة جديدة</h2>
        <span class="tl-admin-badge">لوحة الإدارة</span>
    </div>

    @if(session('success'))
    <div class="tl-alert-success">
        <span>✅</span> {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('tilawats.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="tl-form-grid">
            <div class="tl-field">
                <label>عنوان التلاوة *</label>
                <input type="text" name="title" required placeholder="مثال: سورة البقرة — رواية حفص">
            </div>
            <div class="tl-field">
                <label>اسم الشيخ القارئ *</label>
                <input type="text" name="reciter_name" required placeholder="مثال: الشيخ محمود خليل الحصري">
            </div>
            <div class="tl-field">
                <label>اسم السورة (اختياري)</label>
                <input type="text" name="surah_name" placeholder="مثال: سورة الفاتحة">
            </div>
        </div>

        <div class="tl-media-box">
            <div class="tl-field">
                <label>📁 رفع ملف (MP3 أو MP4)</label>
                <input type="file" name="media_file" accept=".mp3,.mp4">
            </div>
            <div class="tl-media-or">أو</div>
            <div class="tl-field">
                <label>🎬 رابط يوتيوب</label>
                <input type="url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=...">
            </div>
        </div>

        <div class="tl-form-footer">
            <label class="tl-check-label">
                <input type="checkbox" name="is_featured" value="1">
                تمييز كـ تلاوة مختارة ⭐
            </label>
            <button type="submit" class="tl-submit-btn">
                <span>⬆️</span> رفع ونشر التلاوة
            </button>
        </div>
    </form>
</div>
@endif

{{-- ====================== FEATURED ====================== --}}
@if(isset($featured) && $featured->count() > 0)
<div class="tl-section">
    <div class="tl-sec-head">
        <span class="tl-sec-icon" style="color:var(--tw-gold)">⭐</span>
        <h2>التلاوات المختارة</h2>
        <div class="tl-sec-line gold"></div>
        <span class="tl-sec-count">{{ $featured->count() }} تلاوة</span>
    </div>

    <div class="tl-grid-featured">
    @foreach($featured as $item)
    @php
        $isYt  = in_array($item->media_type, ['youtube','Youtube']) || str_contains($item->media_url,'youtu');
        $isMp3 = $item->media_type === 'mp3' || str_ends_with($item->media_url,'.mp3');
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',$item->media_url,$m);
        $ytId  = $m[1] ?? null;
    @endphp
    <div class="tl-card featured">

        {{-- admin delete --}}
        @if(isset($isAdmin) && $isAdmin)
        <form action="{{ route('tilawats.destroy',$item->id) }}" method="POST"
              onsubmit="return confirm('حذف هذه التلاوة نهائياً؟')">
            @csrf @method('DELETE')
            <button type="submit" class="tl-del-btn" title="حذف">🗑️</button>
        </form>
        @else
        <div class="tl-featured-star">⭐</div>
        @endif

        {{-- media --}}
        @if($isMp3)
        <div class="tl-audio">
            <div class="tl-audio-disc">🔊</div>
            <audio controls preload="metadata" controlsList="nodownload">
                <source src="{{ $item->media_url }}" type="audio/mpeg">
            </audio>
            <span class="tl-type-badge tl-type-mp3">MP3 صوتي</span>
        </div>
        @else
        <div class="tl-media">
            @if($isYt && $ytId)
                <iframe src="https://www.youtube.com/embed/{{ $ytId }}" allowfullscreen></iframe>
                <span class="tl-type-badge tl-type-yt">YouTube</span>
            @elseif(!$isYt)
                <video controls preload="metadata" controlsList="nodownload">
                    <source src="{{ $item->media_url }}" type="video/mp4">
                </video>
                <span class="tl-type-badge tl-type-mp4">MP4 مرئي</span>
            @else
                <div class="tl-media-err">⚠️ رابط يوتيوب غير صالح</div>
            @endif
        </div>
        @endif

        {{-- body --}}
        <div class="tl-card-body">
            <span class="tl-surah-badge gold">{{ $item->surah_name ?? 'سورة مباركة' }}</span>
            <div class="tl-card-title" title="{{ $item->title }}">{{ $item->title }}</div>
            <div class="tl-card-footer">
                <span class="tl-reciter">👤 {{ $item->reciter_name }}</span>
                <span class="tl-views">👁 {{ number_format($item->views_count ?? 0) }}</span>
            </div>
        </div>
    </div>
    @endforeach
    </div>
</div>
@endif

{{-- ====================== ALL ====================== --}}
<div class="tl-section">
    <div class="tl-sec-head">
        <span class="tl-sec-icon" style="color:var(--tw-green-main)">📚</span>
        <h2>كل التلاوات المتاحة</h2>
        <div class="tl-sec-line"></div>
        <span class="tl-sec-count green">{{ $tilawats->total() }} تلاوات</span>
    </div>

    @if($tilawats->count() > 0)
    <div class="tl-grid-all">
    @foreach($tilawats as $item)
    @php
        $isYt  = in_array($item->media_type, ['youtube','Youtube']) || str_contains($item->media_url,'youtu');
        $isMp3 = $item->media_type === 'mp3' || str_ends_with($item->media_url,'.mp3');
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',$item->media_url,$m);
        $ytId  = $m[1] ?? null;
    @endphp
    <div class="tl-card">

        @if(isset($isAdmin) && $isAdmin)
        <form action="{{ route('tilawats.destroy',$item->id) }}" method="POST"
              onsubmit="return confirm('حذف هذه التلاوة نهائياً؟')">
            @csrf @method('DELETE')
            <button type="submit" class="tl-del-btn" title="حذف">🗑️</button>
        </form>
        @endif

        @if($isMp3)
        <div class="tl-audio">
            <div class="tl-audio-disc">🔊</div>
            <audio controls preload="metadata" controlsList="nodownload">
                <source src="{{ $item->media_url }}" type="audio/mpeg">
            </audio>
            <span class="tl-type-badge tl-type-mp3">MP3</span>
        </div>
        @else
        <div class="tl-media">
            @if($isYt && $ytId)
                <iframe src="https://www.youtube.com/embed/{{ $ytId }}" allowfullscreen></iframe>
                <span class="tl-type-badge tl-type-yt">YouTube</span>
            @elseif(!$isYt)
                <video controls preload="metadata" controlsList="nodownload">
                    <source src="{{ $item->media_url }}" type="video/mp4">
                </video>
                <span class="tl-type-badge tl-type-mp4">MP4</span>
            @else
                <div class="tl-media-err">⚠️ رابط غير صالح</div>
            @endif
        </div>
        @endif

        <div class="tl-card-body">
            <span class="tl-surah-badge green">{{ $item->surah_name ?? 'سورة مباركة' }}</span>
            <div class="tl-card-title" title="{{ $item->title }}">{{ $item->title }}</div>
            <div class="tl-card-footer">
                <span class="tl-reciter">👤 {{ $item->reciter_name }}</span>
                <span class="tl-views">👁 {{ number_format($item->views_count ?? 0) }}</span>
            </div>
        </div>
    </div>
    @endforeach
    </div>
    <div class="tl-pagination">{{ $tilawats->links() }}</div>

    @else
    <div class="tl-empty">
        <div class="tl-empty-icon">📭</div>
        <p>لا توجد تلاوات مرفوعة حالياً — كن أول من يُضيف تلاوة.</p>
    </div>
    @endif
</div>

</div>
@endsection