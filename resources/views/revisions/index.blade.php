@extends('layouts.app')
@section('title', 'المراجعة')

@section('content')

{{-- ═══ رأس الصفحة ═══ --}}
<div class="page-header">
  <div class="header-ornament-row">
    <div class="ornament-line"></div>
    <img src="{{ asset('images/zakhrafa.png') }}" alt="زخرفة" class="header-zakhrafa"/>
    <div class="ornament-line"></div>
  </div>
  <h1 class="header-title">جدول المراجعة</h1>
  <p class="header-subtitle">متابعة المحفوظات من خلال نظام التكرار المتباعد</p>
</div>

{{-- ═══ بطاقات الإحصاء ═══ --}}
<div class="stats-grid">
  <div class="stat-card card-green"
       onmouseover="this.style.transform='translateY(-3px)'"
       onmouseout="this.style.transform='translateY(0)'">
    <div class="stat-card-header">
      <span class="stat-emoji">📖</span>
      <span class="stat-badge badge-green">اليوم</span>
    </div>
    <p class="stat-number num-green">{{ $todayRevisions->count() }}</p>
    <p class="stat-label">مراجعات اليوم</p>
    <p class="stat-sub">
      @if($overdueRevisions > 0)
        <span style="color:#ef4444">{{ $overdueRevisions }} متأخرة</span>
      @else
        بانتظارك
      @endif
    </p>
  </div>

  <div class="stat-card card-blue"
       onmouseover="this.style.transform='translateY(-3px)'"
       onmouseout="this.style.transform='translateY(0)'">
    <div class="stat-card-header">
      <span class="stat-emoji">📅</span>
      <span class="stat-badge badge-blue">الأسبوع</span>
    </div>
    <p class="stat-number num-blue">{{ $weekRevisions }}</p>
    <p class="stat-label">مراجعات الأسبوع</p>
    <p class="stat-sub">{{ $completedThisWeek }} مكتملة</p>
  </div>

  <div class="stat-card card-purple"
       onmouseover="this.style.transform='translateY(-3px)'"
       onmouseout="this.style.transform='translateY(0)'">
    <div class="stat-card-header">
      <span class="stat-emoji">🎯</span>
      <span class="stat-badge badge-purple">معدّل</span>
    </div>
    <p class="stat-number num-purple">{{ $achievementRate }}%</p>
    <p class="stat-label">معدّل الإنجاز</p>
    <p class="stat-sub">{{ $totalMemorizations }} محفوظ مسجّل</p>
  </div>
</div>

{{-- ═══ مراجعات اليوم ═══ --}}
<div class="card" style="margin-bottom:24px">
  <div class="card-header"
       style="display:flex;align-items:center;
              justify-content:space-between;flex-wrap:wrap;gap:10px;padding:16px">
    <div class="card-header-title" style="display:flex;align-items:center;gap:10px">
      <div class="card-icon green" style="font-size:24px">🧠</div>
      <div>
        <span style="font-weight:700;color:var(--text)">مراجعات اليوم</span>
        <p style="font-size:11px;color:var(--text-m);font-weight:400;
                  margin-top:1px;margin-bottom:0">
          {{ today()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
        </p>
      </div>
    </div>
    <a href="{{ route('memorizations.index') }}"
       style="font-size:12.5px;color:#059669;text-decoration:none;
              font-weight:600;display:flex;align-items:center;gap:4px">
      المحفوظات
      <svg width="14" height="14" fill="none" stroke="currentColor"
           stroke-width="2.2" viewBox="0 0 24 24">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>
  </div>

  <div class="card-body">
    @if($todayRevisions->isEmpty())
    {{-- حالة فارغة --}}
    <div class="empty-state-container">
      <div class="empty-image-wrapper">
        <img src="{{ asset('images/check.png') }}" alt="تم" class="empty-check-img"/>
      </div>
      <p class="empty-title">لا توجد مراجعات مجدولة اليوم</p>
      <p class="empty-desc">
        بارك الله فيك! انتهيت من جميع المراجعات المخطط لها.<br/>
        <span style="font-size:12px;opacity:.7">عد غداً لمتابعة حفظك</span>
      </p>
      <a href="{{ route('memorizations.create') }}" class="btn-primary-gradient">
        📖 إضافة محفوظ جديد
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="2.2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    </div>

    @else
    {{-- قائمة المراجعات المتبقية لليوم --}}
    <div style="display:flex;flex-direction:column;gap:12px">
      @foreach($todayRevisions as $rev)
      @php
        $mem  = $rev->memorization;
        $surahs = \App\Models\DailyWard::SURAHS;
        $surahName = $surahs[$mem->surah_number] ?? 'سورة '.$mem->surah_number;
        $isOverdue = $rev->scheduled_date->lt(today());

        $masteryColors = [
          'weak'      => ['#fef2f2','#ef4444','#991b1b'],
          'fair'      => ['#fffbeb','#f59e0b','#92400e'],
          'good'      => ['#eff6ff','#3b82f6','#1e40af'],
          'excellent' => ['#ecfdf5','#10b981','#065f46'],
        ];
        $mc = $masteryColors[$mem->mastery_level] ?? $masteryColors['fair'];
      @endphp

      <div style="background:var(--card);border:1px solid var(--border);
                  border-radius:16px;overflow:hidden;
                  {{ $isOverdue ? 'border-color:#fecaca' : '' }}">

        {{-- رأس بطاقة المراجعة --}}
        <div style="padding:16px 20px;display:flex;align-items:center;
                    justify-content:space-between;gap:12px;flex-wrap:wrap;
                    background:{{ $isOverdue ? '#fef2f2' : 'var(--bg)' }}">
          <div style="display:flex;align-items:center;gap:12px">
            <div style="width:44px;height:44px;border-radius:12px;
                        background:linear-gradient(135deg,#064e3b,#0d6b52);
                        display:flex;align-items:center;justify-content:center;
                        font-family:'Amiri',serif;font-size:1.2rem;
                        font-weight:700;color:#fff;flex-shrink:0">
              {{ $mem->surah_number }}
            </div>
            <div>
              <p style="font-family:'Amiri',serif;font-size:1.1rem;
                         font-weight:700;color:var(--text);margin-bottom:3px">
                {{ $surahName }}
              </p>
              <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                <span style="font-size:11.5px;padding:2px 9px;border-radius:100px;
                             background:{{ $mc[0] }};color:{{ $mc[2] }};
                             font-weight:600">
                  {{ ['weak'=>'ضعيف','fair'=>'متوسط','good'=>'جيد','excellent'=>'ممتاز'][$mem->mastery_level] ?? '' }}
                </span>
                <span style="font-size:11.5px;color:var(--text-m)">
                  آية {{ $mem->ayah_from }} — {{ $mem->ayah_to }}
                  ({{ ($mem->ayah_to - $mem->ayah_from + 1) }} آية)
                </span>
                @if($isOverdue)
                <span style="font-size:11px;color:#ef4444;font-weight:700">
                  ⚠️ متأخرة — {{ $rev->scheduled_date->locale('ar')->diffForHumans() }}
                </span>
                @else
                <span style="font-size:11px;color:var(--text-m)">
                  {{ ['daily'=>'يومية','weekly'=>'أسبوعية','monthly'=>'شهرية'][$rev->revision_type] ?? '' }}
                </span>
                @endif
              </div>
            </div>
          </div>

          <form method="POST" action="{{ route('revisions.skip', $rev) }}">
            @csrf
            <button type="submit"
                    style="padding:7px 14px;border-radius:9px;
                           background:var(--bg);border:1px solid var(--border);
                           font-family:'Tajawal',sans-serif;font-size:12px;
                           color:var(--text-m);cursor:pointer">
              تأجيل
            </button>
          </form>
        </div>

        {{-- نموذج إتمام المراجعة --}}
        <div style="padding:16px 20px">
          <form method="POST" action="{{ route('revisions.complete', $rev) }}"
                x-data="{ score: 80 }">
            @csrf

            <div style="margin-bottom:14px">
              <div style="display:flex;justify-content:space-between;
                          margin-bottom:8px">
                <label style="font-size:12.5px;font-weight:600;color:var(--text)">
                  درجة الإتقان بعد المراجعة
                </label>
                <span x-text="score + '٪'"
                      style="font-family:'Amiri',serif;font-size:1.1rem;
                             font-weight:700;color:#059669"></span>
              </div>

              <input type="range" name="score"
                     x-model="score"
                     min="0" max="100" step="5"
                     style="width:100%;accent-color:#059669;cursor:pointer"/>

              <div style="display:flex;justify-content:space-between;
                          margin-top:6px">
                @foreach([
                  [0,'ضعيف','#ef4444'],
                  [50,'متوسط','#f59e0b'],
                  [70,'جيد','#3b82f6'],
                  [90,'ممتاز','#10b981'],
                ] as [$v,$l,$c])
                <span style="font-size:10px;color:{{ $c }};font-weight:600">
                  {{ $l }}
                </span>
                @endforeach
              </div>
            </div>

            <div style="margin-bottom:14px">
              <input type="text" name="notes"
                     placeholder="ملاحظة اختيارية..."
                     style="width:100%;padding:9px 13px;
                            border:1.5px solid var(--border);border-radius:10px;
                            font-family:'Tajawal',sans-serif;font-size:13px;
                            color:var(--text);background:var(--bg);outline:none"/>
            </div>

            <button type="submit"
                    style="width:100%;padding:12px;border-radius:12px;
                           background:linear-gradient(135deg,#0d6b52,#064e3b);
                           border:none;color:#fff;font-family:'Tajawal',sans-serif;
                           font-size:14px;font-weight:700;cursor:pointer;
                           box-shadow:0 4px 16px rgba(13,107,82,.3)">
              ✓ تأكيد إتمام المراجعة
            </button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</div>

{{-- ═══ الجدول: مسار مراجعات المحفوظات الكامل ═══ --}}
<div class="card" style="margin-bottom:24px">
  <div class="card-header" style="padding:16px; border-bottom:1px solid var(--border)">
    <div style="display:flex;align-items:center;gap:10px">
      <div style="font-size:24px">📊</div>
      <div>
        <span style="font-weight:700;color:var(--text)">سجل ومسار المراجعات الإجمالي</span>
        <p style="font-size:11px;color:var(--text-m);margin:2px 0 0 0">كافة المراجعات السابقة (المنجزة)، الفائتة، والمستقبلية القادمة</p>
      </div>
    </div>
  </div>

  <div class="card-body" style="padding:0">
    @if($allRevisionsLog->isEmpty())
      <div style="text-align:center; padding:30px; color:var(--text-m); font-size:13px;">
        لا يوجد سجل مراجعات متوفر حالياً.
      </div>
    @else
      <div class="table-responsive">
        <table class="rev-track-table">
          <thead>
            <tr>
              <th>المحفوظ (السورة)</th>
              <th>الآيات</th>
              <th>نوع المراجعة</th>
              <th>تاريخ الجدولة</th>
              <th>الحالة</th>
              <th>الدرجة / الملاحظة</th>
            </tr>
          </thead>
          <tbody>
            @foreach($allRevisionsLog as $log)
              @php
                $surahs = \App\Models\DailyWard::SURAHS;
                $sName = $surahs[$log->memorization->surah_number] ?? 'سورة '.$log->memorization->surah_number;
                
                $statusConfig = [
                  'completed' => ['background' => '#ecfdf5', 'color' => '#065f46', 'label' => '✓ تمت المراجعة'],
                  'pending'   => $log->scheduled_date->lt(today()) 
                                 ? ['background' => '#fef2f2', 'color' => '#b91c1c', 'label' => '⚠️ متأخرة']
                                 : ['background' => '#fffbeb', 'color' => '#b45309', 'label' => '⏳ مجدولة'],
                  'skipped'   => ['background' => '#f3f4f6', 'color' => '#4b5563', 'label' => '↩ مؤجلة'],
                  'overdue'   => ['background' => '#fef2f2', 'color' => '#b91c1c', 'label' => '⚠️ متأخرة'],
                ];

                $currentStatus = $statusConfig[$log->status] ?? ['background' => 'var(--bg)', 'color' => 'var(--text)', 'label' => $log->status];
              @endphp
              <tr>
                <td class="font-amiri" style="font-weight:700; font-size:1.05rem;">
                  {{ $sName }}
                </td>
                <td style="font-size:12px; color:var(--text-m)">
                  {{ $log->memorization->ayah_from }} - {{ $log->memorization->ayah_to }}
                </td>
                <td>
                  <span class="type-badge">
                    {{ ['daily'=>'يومية','weekly'=>'أسبوعية','monthly'=>'شهرية'][$log->revision_type] ?? $log->revision_type }}
                  </span>
                </td>
                <td style="font-size:12px;">
                  {{ $log->scheduled_date->format('Y/m/d') }}
                  <span style="display:block; font-size:10px; color:var(--text-m); margin-top:2px;">
                    {{ $log->scheduled_date->locale('ar')->diffForHumans() }}
                  </span>
                </td>
                <td>
                  <span class="status-pill" style="background: {{ $currentStatus['background'] }}; color: {{ $currentStatus['color'] }}">
                    {{ $currentStatus['label'] }}
                  </span>
                </td>
                <td>
                  @if($log->status === 'completed')
                    <span style="font-weight:700; color:#059669;">{{ $log->score }}٪</span>
                    @if($log->notes)
                      <span class="table-note-tooltip" title="{{ $log->notes }}">📝</span>
                    @endif
                  @else
                    <span style="color:var(--text-m); font-size:12px;">-</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
      {{-- 🌟 تم تحديث شريط التنقيل المخصص لحل مشكلة النصوص الإنجليزية بالكامل وتناسق الشكل 🌟 --}}
      <div class="custom-pagination-container">
        <div class="pagination-info">
          عرض السجلات من <b>{{ $allRevisionsLog->firstItem() ?? 0 }}</b> إلى <b>{{ $allRevisionsLog->lastItem() ?? 0 }}</b> (الإجمالي: <b>{{ $allRevisionsLog->total() }}</b> مراجعة)
        </div>
        
        @if($allRevisionsLog->hasPages())
          <div class="pagination-buttons">
            {{-- زر الصفحة السابقة --}}
            @if($allRevisionsLog->onFirstPage())
              <span class="pag-btn disabled">السابق</span>
            @else
              <a href="{{ $allRevisionsLog->appends(['revisions_page' => $allRevisionsLog->currentPage()])->previousPageUrl() }}" class="pag-btn">السابق</a>
            @endif

            {{-- أرقام الصفحات --}}
            @foreach ($allRevisionsLog->getUrlRange(1, $allRevisionsLog->lastPage()) as $page => $url)
              @if ($page == $allRevisionsLog->currentPage())
                <span class="pag-link active">{{ $page }}</span>
              @else
                <a href="{{ $allRevisionsLog->appends(['revisions_page' => $page])->url($page) }}" class="pag-link">{{ $page }}</a>
              @endif
            @endforeach

            {{-- زر الصفحة التالية --}}
            @if($allRevisionsLog->hasMorePages())
              <a href="{{ $allRevisionsLog->appends(['revisions_page' => $allRevisionsLog->currentPage()])->nextPageUrl() }}" class="pag-btn">التالي</a>
            @else
              <span class="pag-btn disabled">التالي</span>
            @endif
          </div>
        @endif
      </div>
    @endif
  </div>
</div>

{{-- ═══ معلومات نظام التكرار المتباعد ═══ --}}
<div class="info-grid">
  <div class="info-box">
    <div class="info-box-header header-amber">
      <img src="{{ asset('images/idea.png') }}" class="info-icon-img"/>
      <p class="info-box-title text-amber">نصيحة اليوم</p>
    </div>
    <div class="info-box-body">
      <p style="margin-bottom:8px">
        <span style="color:#d97706;font-weight:600">التكرار المتباعد</span>
        يساعدك على تثبيت الحفظ في الذاكرة الطويلة المدى.
      </p>
      <p style="color:var(--text-m);font-size:12px;margin:0">
        جدول المراجعات: اليوم ← غداً ← 3 أيام ← أسبوع ← شهر
      </p>
    </div>
  </div>

  <div class="info-box">
    <div class="info-box-header header-emerald">
      <span style="font-size:24px">🎯</span>
      <p class="info-box-title text-emerald">أهدافك</p>
    </div>
    <div class="info-box-body" style="display:flex;flex-direction:column;gap:14px">
      <div style="display:flex;justify-content:space-between;
                  padding-bottom:12px;border-bottom:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text);margin:0">
          <span style="font-weight:700">{{ $totalMemorizations }}</span> محفوظ مسجّل
        </p>
        <p style="font-size:12px;color:var(--text-m);margin:0">من أصل 30 جزء</p>
      </div>
      <div style="display:flex;justify-content:space-between;margin:0">
        <p style="font-size:13px;color:var(--text);margin:0">
          <span style="font-weight:700">{{ $completedThisWeek }}</span> راجعت هذا الأسبوع
        </p>
        <p style="font-size:12px;color:var(--text-m);margin:0">
          {{ $achievementRate }}٪ معدّل إنجاز
        </p>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
.page-header { margin-bottom:28px;text-align:center; }
.header-ornament-row {
  display:flex;align-items:center;justify-content:center;
  gap:10px;margin-bottom:16px;
}
.ornament-line { flex:1;height:1px;background:var(--border); }
.header-zakhrafa { width:56px;height:56px;object-fit:contain;opacity:.85; }
.header-title { font-size:2rem;font-weight:700;color:var(--text);margin-bottom:8px; }
.header-subtitle { font-size:13.5px;color:var(--text-m); }

.stats-grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
  gap:16px;margin-bottom:24px;
}
.stat-card {
  border-radius:16px;padding:22px 20px;
  transition:transform .2s,box-shadow .2s;cursor:default;
}
.stat-card-header {
  display:flex;align-items:center;
  justify-content:space-between;margin-bottom:12px;
}
.stat-emoji { font-size:24px; }
.stat-badge { font-size:11px;padding:3px 9px;border-radius:100px;font-weight:600; }
.stat-number { font-family:'Amiri',serif;font-size:2rem;font-weight:700;line-height:1;margin-bottom:4px; }
.stat-label { font-size:13px;font-weight:700;color:#1a2e25;margin-bottom:1px; }
.stat-sub { font-size:11px;color:#6b7280;margin:0; }

.card-green { background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0; }
.badge-green { background:#a7f3d0;color:#065f46; }
.num-green { color:#059669; }
.card-blue { background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe; }
.badge-blue { background:#bfdbfe;color:#1d4ed8; }
.num-blue { color:#2563eb; }
.card-purple { background:linear-gradient(135deg,#fdf4ff,#fae8ff);border:1px solid #e9d5ff; }
.badge-purple { background:#e9d5ff;color:#7e22ce; }
.num-purple { color:#9333ea; }

.empty-state-container { text-align:center;padding:60px 20px; }
.empty-image-wrapper { margin-bottom:20px;display:flex;align-items:center;justify-content:center; }
.empty-check-img { width:56px;height:56px;object-fit:contain;opacity:.85; }
.empty-title { font-family:'Amiri',serif;font-size:1.4rem;font-weight:700;color:var(--text);margin-bottom:8px; }
.empty-desc { font-size:13.5px;color:var(--text-m);margin-bottom:24px;line-height:1.5; }
.btn-primary-gradient {
  display:inline-flex;align-items:center;gap:8px;
  padding:11px 24px;border-radius:12px;
  background:linear-gradient(135deg,#0d6b52,#065f46);
  color:#fff;font-size:13.5px;font-weight:700;text-decoration:none;
  box-shadow:0 4px 16px rgba(13,107,82,.35);
  transition:transform .2s,box-shadow .2s;
}

.info-grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
  gap:24px;margin-top:24px;
}
.info-box { background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden; }
.info-box-header { padding:18px 22px;display:flex;align-items:center;gap:12px; }
.header-amber { background:linear-gradient(135deg,#fffbeb,#fef3c7);border-bottom:1px solid var(--border); }
.header-emerald { background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-bottom:1px solid var(--border); }
.info-icon-img { width:30px;height:30px;object-fit:contain; }
.info-box-title { font-size:13.5px;font-weight:700;margin:0; }
.text-amber { color:#78350f; }
.text-emerald { color:#065f46; }
.info-box-body { padding:20px;font-size:13px;color:var(--text-m);line-height:1.6; }

/* ═══ 📱 ستيلات الجدول والتنقيل المخصص المتجاوب ═══ */
.table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.rev-track-table {
  width: 100%;
  border-collapse: collapse;
  text-align: right;
}
.rev-track-table th {
  background: var(--bg);
  padding: 12px 16px;
  font-size: 12px;
  font-weight: 700;
  color: var(--text-m);
  border-bottom: 1px solid var(--border);
}
.rev-track-table td {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border);
  font-size: 13px;
  color: var(--text);
  vertical-align: middle;
}
.rev-track-table tr:last-child td {
  border-bottom: none;
}
.font-amiri {
  font-family: 'Amiri', serif;
}
.status-pill {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 100px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}
.type-badge {
  background: var(--bg);
  border: 1px solid var(--border);
  padding: 2px 8px;
  border-radius: 6px;
  font-size: 11.5px;
  color: var(--text);
}
.table-note-tooltip {
  cursor: help;
  margin-right: 4px;
  display: inline-block;
}

/* ستيلات التحكم في شريط التنقيل الجديد */
.custom-pagination-container {
  padding: 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
  border-top: 1px solid var(--border);
  background: var(--card);
}
.pagination-info {
  font-size: 12.5px;
  color: var(--text-m);
}
.pagination-buttons {
  display: flex;
  align-items: center;
  gap: 6px;
}
.pag-btn {
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: var(--bg);
  color: var(--text);
  font-size: 12.5px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.2s;
}
.pag-btn:hover:not(.disabled) {
  background: var(--border);
}
.pag-btn.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  color: var(--text-m);
}
.pag-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none;
  color: var(--text);
  border: 1px solid var(--border);
  background: var(--bg);
  transition: all 0.2s;
}
.pag-link:hover:not(.active) {
  background: var(--border);
}
.pag-link.active {
  background: linear-gradient(135deg, #0d6b52, #064e3b);
  color: #fff;
  border-color: #064e3b;
}

@media(max-width:768px) {
  .rev-track-table th, .rev-track-table td {
    padding: 10px 12px !important;
  }
  .custom-pagination-container {
    flex-direction: column;
    text-align: center;
  }
}

@media(max-width:480px) {
  .header-title { font-size:1.6rem; }
  .stats-grid { grid-template-columns:1fr; }
  .info-grid { grid-template-columns:1fr; }
  .empty-state-container { padding:40px 16px; }
}
</style>
@endpush