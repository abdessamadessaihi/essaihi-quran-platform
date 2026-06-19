@extends('layouts.app')
@section('title','المقالات التدبرية')

@section('content')

{{-- ══ رأس الصفحة ══ --}}
<div style="margin-bottom:28px">
  <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
    <div style="flex:1;height:1px;background:linear-gradient(to left,transparent,#a7f3d0)"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة"
         style="width:56px;height:56px;object-fit:contain;opacity:.85"/>
    <div style="flex:1;height:1px;background:linear-gradient(to right,transparent,#a7f3d0)"></div>
  </div>
  <div style="display:flex;align-items:flex-start;justify-content:space-between;
              gap:16px;flex-wrap:wrap">
    <div>
      <h1 style="font-family:'Amiri',serif;font-size:1.9rem;font-weight:700;
                 color:var(--text);margin-bottom:5px">المقالات التدبرية</h1>
      <p style="font-size:13.5px;color:var(--text-m)">
        إسهامات فكرية وتأملات قرآنية من أفراد العائلة
      </p>
    </div>
    <a href="{{ route('articles.create') }}"
       style="display:inline-flex;align-items:center;gap:8px;
              padding:11px 22px;border-radius:12px;
              background:linear-gradient(135deg,#0d6b52,#064e3b);
              color:#fff;font-size:13.5px;font-weight:700;
              text-decoration:none;white-space:nowrap;
              box-shadow:0 4px 16px rgba(13,107,82,.30)">
      <svg width="15" height="15" fill="none" stroke="currentColor"
           stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      كتابة مقال
    </a>
  </div>
</div>

{{-- ══ فلاتر الفئات ══ --}}
<div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;
            margin-bottom:24px;scrollbar-width:none">
  <a href="{{ route('articles.index') }}"
     style="display:inline-flex;align-items:center;gap:6px;
            padding:7px 16px;border-radius:100px;white-space:nowrap;
            text-decoration:none;font-size:13px;font-weight:600;
            transition:all .18s;flex-shrink:0;
            {{ !$category
               ? 'background:linear-gradient(135deg,#064e3b,#0d6b52);color:#fff;border:none'
               : 'background:var(--card);color:var(--text-m);border:1px solid var(--border)' }}">
    📚 الكل
  </a>
  @foreach($categories as $key => $label)
  @php $c = \App\Models\Article::CATEGORY_COLORS[$key]; @endphp
  <a href="{{ route('articles.index', ['category'=>$key]) }}"
     style="display:inline-flex;align-items:center;gap:6px;
            padding:7px 16px;border-radius:100px;white-space:nowrap;
            text-decoration:none;font-size:13px;font-weight:600;
            flex-shrink:0;transition:all .18s;
            {{ $category === $key
               ? "background:{$c['bg']};color:{$c['text']};border:1.5px solid {$c['border']}"
               : 'background:var(--card);color:var(--text-m);border:1px solid var(--border)' }}">
    {{ $label }}
  </a>
  @endforeach
</div>

<div style="display:grid;grid-template-columns:minmax(0,1fr) 280px;gap:22px">

  {{-- ── المقالات ── --}}
  <div>
    @if($articles->isEmpty())
    <div style="text-align:center;padding:64px 20px;
                background:var(--card);border:1px solid var(--border);
                border-radius:20px">
      <span style="font-size:48px;display:block;margin-bottom:16px">✍️</span>
      <p style="font-family:'Amiri',serif;font-size:1.4rem;font-weight:700;
                color:var(--text);margin-bottom:8px">
        لا توجد مقالات بعد
      </p>
      <p style="font-size:13.5px;color:var(--text-m);margin-bottom:22px">
        كن أول من يشارك تأملاته القرآنية مع العائلة
      </p>
      <a href="{{ route('articles.create') }}"
         style="display:inline-flex;align-items:center;gap:8px;
                padding:11px 24px;border-radius:12px;
                background:linear-gradient(135deg,#0d6b52,#064e3b);
                color:#fff;font-size:13.5px;font-weight:700;text-decoration:none">
        ✍️ كتابة أول مقال
      </a>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
                gap:18px;margin-bottom:24px">
      @foreach($articles as $article)
      @php $c = $article->category_color; @endphp
      <div style="background:var(--card);border:1px solid var(--border);
                  border-radius:20px;overflow:hidden;
                  transition:box-shadow .25s,transform .25s;position:relative"
           onmouseover="this.style.transform='translateY(-3px)';
                        this.style.boxShadow='0 10px 36px rgba(6,78,59,.10)'"
           onmouseout="this.style.transform='translateY(0)';
                       this.style.boxShadow='none'">

        {{-- الصورة --}}
        @if($article->cover_url)
        <div style="height:160px;overflow:hidden">
          <img src="{{ asset($article->cover_url) }}" alt="{{ $article->title }}"
               style="width:100%;height:100%;object-fit:cover;transition:transform .3s"
               onmouseover="this.style.transform='scale(1.05)'"
               onmouseout="this.style.transform='scale(1)'"/>
        </div>
        @else
        <div style="height:100px;
                    background:linear-gradient(135deg,{{ $c['bg'] }},transparent);
                    display:flex;align-items:center;justify-content:center;
                    font-size:32px">
          {{ match($article->category){
            'tafsir'=>'📖','tadabbur'=>'🌙','fiqh'=>'⚖️',
            'seerah'=>'🕌',default=>'✍️'} }}
        </div>
        @endif

        <div style="padding:18px">
          {{-- الفئة --}}
          <span style="font-size:11px;font-weight:700;padding:3px 10px;
                       border-radius:100px;display:inline-block;margin-bottom:10px;
                       background:{{ $c['bg'] }};color:{{ $c['text'] }};
                       border:1px solid {{ $c['border'] }}">
            {{ $article->category_label }}
          </span>

          {{-- العنوان --}}
          <h3 style="font-family:'Amiri',serif;font-size:1.1rem;font-weight:700;
                     color:var(--text);margin-bottom:8px;line-height:1.4">
            <a href="{{ route('articles.show', $article) }}"
               style="color:inherit;text-decoration:none">
              {{ $article->title }}
            </a>
          </h3>

          {{-- المقتطف --}}
          @if($article->excerpt)
          <p style="font-size:13px;color:var(--text-m);line-height:1.7;
                    margin-bottom:14px;display:-webkit-box;
                    -webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
            {{ $article->excerpt }}
          </p>
          @endif

          {{-- التذييل --}}
          <div style="display:flex;align-items:center;justify-content:space-between;
                      padding-top:12px;border-top:1px solid var(--border)">
            <div style="display:flex;align-items:center;gap:7px">
              <div style="width:28px;height:28px;border-radius:8px;flex-shrink:0;
                          background:linear-gradient(135deg,#064e3b,#0d6b52);
                          display:flex;align-items:center;justify-content:center;
                          color:#fff;font-size:12px;font-weight:700">
                {{ mb_substr($article->author->name,0,1) }}
              </div>
              <div>
                <p style="font-size:11.5px;font-weight:600;color:var(--text);
                           line-height:1">{{ $article->author->name }}</p>
                <p style="font-size:10.5px;color:var(--text-m)">
                  {{ $article->created_at->locale('ar')->diffForHumans() }}
                </p>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
              <span style="font-size:11px;color:var(--text-m);
                           display:flex;align-items:center;gap:3px">
                👁️ {{ $article->views }}
              </span>
              <a href="{{ route('articles.show', $article) }}"
                 style="font-size:12px;font-weight:700;color:#059669;
                        text-decoration:none;padding:5px 12px;border-radius:8px;
                        background:#ecfdf5;border:1px solid #a7f3d0">
                اقرأ ←
              </a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    {{ $articles->links() }}
    @endif
  </div>

  {{-- ── الشريط الجانبي ── --}}
  <div style="display:flex;flex-direction:column;gap:16px">

    {{-- مقالاتي --}}
    @if($myArticles->isNotEmpty())
    <div class="card">
      <div class="card-header">
        <div class="card-header-title">
          <div class="card-icon gold">✍️</div>
          مقالاتي
        </div>
        <a href="{{ route('articles.create') }}"
           style="font-size:12px;color:#059669;font-weight:600;text-decoration:none">
          + جديد
        </a>
      </div>
      <div style="padding:12px">
        @foreach($myArticles as $a)
        <a href="{{ route('articles.show',$a) }}"
           style="display:flex;align-items:flex-start;gap:10px;
                  padding:10px;border-radius:10px;text-decoration:none;
                  transition:background .15s"
           onmouseover="this.style.background='var(--bg)'"
           onmouseout="this.style.background='transparent'">
          <div style="width:32px;height:32px;border-radius:8px;flex-shrink:0;
                      display:flex;align-items:center;justify-content:center;
                      font-size:14px;
                      background:{{ \App\Models\Article::CATEGORY_COLORS[$a->category]['bg'] }}">
            {{ match($a->category){'tafsir'=>'📖','tadabbur'=>'🌙','fiqh'=>'⚖️','seerah'=>'🕌',default=>'✍️'} }}
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-size:12.5px;font-weight:600;color:var(--text);
                      white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
              {{ $a->title }}
            </p>
            <div style="display:flex;align-items:center;gap:8px;margin-top:3px">
              <span style="font-size:10px;padding:1px 7px;border-radius:100px;
                           background:{{ $a->status==='published' ? '#ecfdf5' : '#f1f5f9' }};
                           color:{{ $a->status==='published' ? '#059669' : '#64748b' }};
                           font-weight:600">
                {{ $a->status==='published' ? 'منشور' : 'مسودة' }}
              </span>
              <span style="font-size:10.5px;color:var(--text-m)">
                👁️ {{ $a->views }}
              </span>
            </div>
          </div>
        </a>
        @endforeach
      </div>
    </div>
    @endif

    {{-- نصيحة --}}
    <div style="background:linear-gradient(135deg,#031810,#042a1e);
                border-radius:16px;padding:22px;text-align:center">
      <p style="font-family:'Amiri',serif;font-size:1.2rem;
                color:rgba(255,255,255,.9);line-height:2;margin-bottom:8px">
        ﴿ كِتَابٌ أَنزَلْنَاهُ إِلَيْكَ مُبَارَكٌ لِّيَدَّبَّرُوا آيَاتِهِ ﴾
      </p>
      <p style="font-size:11px;color:#f59e0b;opacity:.8">
        سورة ص — الآية ٢٩
      </p>
    </div>

  </div>
</div>

@endsection

@push('styles')
<style>
@media(max-width:1024px){
  div[style*="grid-template-columns:minmax(0,1fr) 280px"]{
    grid-template-columns:1fr !important;
  }
}
</style>
@endpush