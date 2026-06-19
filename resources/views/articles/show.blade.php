@extends('layouts.app')
@section('title', $article->title)

@section('content')

<div style="max-width:860px;margin:0 auto">

  {{-- رابط الرجوع --}}
  <a href="{{ route('articles.index') }}"
     style="display:inline-flex;align-items:center;gap:6px;
            color:var(--text-m);text-decoration:none;font-size:13px;
            margin-bottom:20px">
    <svg width="14" height="14" fill="none" stroke="currentColor"
         stroke-width="2" viewBox="0 0 24 24">
      <path d="M19 12H5M12 19l-7-7 7-7"/>
    </svg>
    المقالات التدبرية
  </a>

  {{-- رأس المقال --}}
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:20px;overflow:hidden;margin-bottom:22px">

    {{-- الصورة --}}
    @if($article->cover_url)
    <div style="height:280px;overflow:hidden">
      <img src="{{ asset($article->cover_url) }}" alt="{{ $article->title }}"
           style="width:100%;height:100%;object-fit:cover"/>
    </div>
    @else
    <div style="height:140px;
                background:linear-gradient(135deg,
                  {{ \App\Models\Article::CATEGORY_COLORS[$article->category]['bg'] }},
                  transparent);
                display:flex;align-items:center;justify-content:center;font-size:48px">
      {{ match($article->category){'tafsir'=>'📖','tadabbur'=>'🌙','fiqh'=>'⚖️','seerah'=>'🕌',default=>'✍️'} }}
    </div>
    @endif

    <div style="padding:28px 32px">
      @php $c = $article->category_color; @endphp
      <span style="font-size:11.5px;font-weight:700;padding:4px 12px;
                   border-radius:100px;display:inline-block;margin-bottom:14px;
                   background:{{ $c['bg'] }};color:{{ $c['text'] }};
                   border:1px solid {{ $c['border'] }}">
        {{ $article->category_label }}
      </span>

      <h1 style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
                 color:var(--text);line-height:1.5;margin-bottom:16px">
        {{ $article->title }}
      </h1>

      <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;
                  padding-bottom:18px;border-bottom:1px solid var(--border)">
        <div style="display:flex;align-items:center;gap:9px">
          <div style="width:38px;height:38px;border-radius:10px;
                      background:linear-gradient(135deg,#064e3b,#0d6b52);
                      display:flex;align-items:center;justify-content:center;
                      color:#fff;font-size:15px;font-weight:700">
            {{ mb_substr($article->author->name,0,1) }}
          </div>
          <div>
            <p style="font-size:13.5px;font-weight:700;color:var(--text)">
              {{ $article->author->name }}
            </p>
            <p style="font-size:11.5px;color:var(--text-m)">
              {{ $article->created_at->locale('ar')->isoFormat('D MMMM YYYY') }}
            </p>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:14px;margin-right:auto">
          <span style="font-size:12px;color:var(--text-m);
                       display:flex;align-items:center;gap:4px">
            👁️ {{ $article->views }} مشاهدة
          </span>
          @if($article->user_id === auth()->id())
          <a href="{{ route('articles.edit',$article) }}"
             style="font-size:12px;font-weight:600;color:#059669;
                    text-decoration:none;padding:5px 12px;border-radius:8px;
                    background:#ecfdf5;border:1px solid #a7f3d0">
            ✏️ تعديل
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- محتوى المقال --}}
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:20px;padding:32px;margin-bottom:22px">
    <div class="article-content"
         style="font-size:15px;line-height:2.1;color:var(--text)">
      {!! nl2br(e($article->content)) !!}
    </div>
  </div>
  {{-- ══ قسم التعليقات المطور ══ --}}
  <div style="background:var(--card);border:1px solid var(--border);border-radius:20px;padding:32px;margin-bottom:22px">
    <h3 style="font-family:'Amiri',serif;font-size:1.3rem;font-weight:700;color:var(--text);margin-bottom:20px;display:flex;align-items:center;gap:8px">
      💬 التعليقات ({{ $article->comments->count() }})
    </h3>

    {{-- فورم إضافة تعليق جديد --}}
    @auth
    <form action="{{ route('comments.store', $article) }}" method="POST" style="margin-bottom:28px">
      @csrf
      <div style="display:flex;gap:12px;align-items:flex-start">
        <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#064e3b,#0d6b52);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:700;flex-shrink:0">
          {{ mb_substr(auth()->user()->name, 0, 1) }}
        </div>
        <div style="flex:1">
          <textarea name="content" rows="3" required placeholder="اكتب تأملك أو تعليقك حول هذا المقال..." 
                    style="width:100%;padding:12px;border-radius:12px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:14px;resize:none;line-height:1.6"
                    onfocus="this.style.borderColor='#0d6b52'"></textarea>
          <div style="display:flex;justify-content:flex-end;margin-top:8px">
            <button type="submit" style="background:#064e3b;color:white;border:none;padding:8px 20px;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;transition:background 0.2s">
              نشر التعليق
            </button>
          </div>
        </div>
      </div>
    </form>
    @else
    <p style="text-align:center;font-size:13px;color:var(--text-m);background:var(--bg);padding:12px;border-radius:10px;margin-bottom:24px">
      يرجى <a href="{{ route('login') }}" style="color:#059669;font-weight:600;text-decoration:none">تسجيل الدخول</a> للمشاركة والتعليق.
    </p>
    @endauth

    {{-- عرض التعليقات الموجودة --}}
    <div style="display:flex;flex-direction:column;gap:16px">
      @forelse($article->comments as $comment)
      <div style="display:flex;gap:12px;align-items:flex-start;padding-bottom:16px;border-bottom:1px solid var(--border)">
        <div style="width:34px;height:34px;border-radius:8px;background:#f1f5f9;color:#475569;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;border:1px solid var(--border)">
          {{ mb_substr($comment->user->name, 0, 1) }}
        </div>
        <div style="flex:1">
          <div style="display:flex;justify-content:between;align-items:center;margin-bottom:4px;flex-wrap:wrap;gap:8px">
            <span style="font-size:13px;font-weight:700;color:var(--text)">{{ $comment->user->name }}</span>
            <span style="font-size:11px;color:var(--text-m);margin-right:auto">{{ $comment->created_at->locale('ar')->diffForHumans() }}</span>
          </div>
          <p style="font-size:13.5px;color:var(--text);line-height:1.6;white-space:pre-line">
            {{ $comment->content }}
          </p>
        </div>
      </div>
      @empty
      <p style="text-align:center;font-size:13px;color:var(--text-m);padding:20px 0">لا توجد تعليقات بعد، كن أول من يعلق! ✍️</p>
      @endforelse
    </div>
  </div>

  {{-- المقالات ذات الصلة --}}
  @if($related->isNotEmpty())
  <div class="card">
    <div class="card-header">
      <div class="card-header-title">
        <div class="card-icon green">📚</div>
        مقالات ذات صلة
      </div>
    </div>
    <div style="padding:16px;display:grid;
                grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px">
      @foreach($related as $r)
      <a href="{{ route('articles.show',$r) }}"
         style="padding:14px;border-radius:14px;
                background:var(--bg);border:1px solid var(--border);
                text-decoration:none;transition:border-color .18s"
         onmouseover="this.style.borderColor='#a7f3d0'"
         onmouseout="this.style.borderColor='var(--border)'">
        <p style="font-size:13.5px;font-weight:700;color:var(--text);
                  margin-bottom:5px;line-height:1.4">{{ $r->title }}</p>
        <p style="font-size:11px;color:var(--text-m)">
          {{ $r->created_at->locale('ar')->isoFormat('D MMM YYYY') }}
        </p>
      </a>
      @endforeach
    </div>
  </div>
  @endif

</div>

@endsection

@push('styles')
<style>
.article-content p { margin-bottom: 1.2em; }
.article-content br { line-height: 2; }
@media(max-width:640px){
  div[style*="padding:28px 32px"]{ padding:20px !important; }
  div[style*="padding:32px"]{ padding:20px !important; }
}
</style>
@endpush