http://127.0.0.1:8001/admin/families go to this page
@extends('layouts.app')
@section('title','المراجعة')
@section('content')

{{-- ═══ رأس الصفحة ═══ --}}
<div style="margin-bottom:28px">
  <div style="display:flex;align-items:center;justify-content:center;
              gap:10px;margin-bottom:16px">
    <div style="flex:1;height:1px;background:var(--border)"></div>
    <span style="font-size:14px;color:#059669;font-weight:700">🔄</span>
    <div style="flex:1;height:1px;background:var(--border)"></div>
  </div>
  <h1 style="font-size:2rem;font-weight:700;color:var(--text);
             text-align:center;margin-bottom:8px">جدول المراجعة</h1>
  <p style="font-size:13.5px;color:var(--text-m);text-align:center">
    متابعة المحفوظات من خلال نظام التكرار المتباعد
  </p>
</div>

{{-- ═══ بطاقة الإحصاء ═══ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-7">
  <div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);
              border:1px solid #a7f3d0;border-radius:16px;padding:22px 20px;
              transition:box-shadow .2s,transform .2s;cursor:default"
       onmouseover="this.style.transform='translateY(-3px)';
                    this.style.boxShadow='0 8px 28px rgba(0,0,0,.08)'"
       onmouseout="this.style.transform='translateY(0)';
                   this.style.boxShadow='none'">
    <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:12px">
      <span style="font-size:24px">📖</span>
      <span style="font-size:11px;padding:3px 9px;border-radius:100px;
                   background:#a7f3d0;color:#065f46;font-weight:600">اليوم</span>
    </div>
    <p style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
              color:#059669;line-height:1;margin-bottom:4px">0</p>
    <p style="font-size:13px;font-weight:700;color:#1a2e25;margin-bottom:1px">
      مراجعات اليوم
    </p>
    <p style="font-size:11px;color:#6b7280">بانتظارك</p>
  </div>

  <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);
              border:1px solid #bfdbfe;border-radius:16px;padding:22px 20px;
              transition:box-shadow .2s,transform .2s;cursor:default"
       onmouseover="this.style.transform='translateY(-3px)';
                    this.style.boxShadow='0 8px 28px rgba(0,0,0,.08)'"
       onmouseout="this.style.transform='translateY(0)';
                   this.style.boxShadow='none'">
    <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:12px">
      <span style="font-size:24px">📅</span>
      <span style="font-size:11px;padding:3px 9px;border-radius:100px;
                   background:#bfdbfe;color:#1d4ed8;font-weight:600">هذا الأسبوع</span>
    </div>
    <p style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
              color:#2563eb;line-height:1;margin-bottom:4px">0</p>
    <p style="font-size:13px;font-weight:700;color:#1a2e25;margin-bottom:1px">
      إجمالي المراجعات
    </p>
    <p style="font-size:11px;color:#6b7280">الأسبوع الحالي</p>
  </div>

  <div style="background:linear-gradient(135deg,#fdf4ff,#fae8ff);
              border:1px solid #e9d5ff;border-radius:16px;padding:22px 20px;
              transition:box-shadow .2s,transform .2s;cursor:default"
       onmouseover="this.style.transform='translateY(-3px)';
                    this.style.boxShadow='0 8px 28px rgba(0,0,0,.08)'"
       onmouseout="this.style.transform='translateY(0)';
                   this.style.boxShadow='none'">
    <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:12px">
      <span style="font-size:24px">🎯</span>
      <span style="font-size:11px;padding:3px 9px;border-radius:100px;
                   background:#e9d5ff;color:#7e22ce;font-weight:600">معدّل</span>
    </div>
    <p style="font-family:'Amiri',serif;font-size:2rem;font-weight:700;
              color:#9333ea;line-height:1;margin-bottom:4px">0</p>
    <p style="font-size:13px;font-weight:700;color:#1a2e25;margin-bottom:1px">
      معدّل الإنجاز
    </p>
    <p style="font-size:11px;color:#6b7280">من المخطط</p>
  </div>
</div>

{{-- ═══ جدول المراجعات ═══ --}}
<div class="card">
  <div class="card-header">
    <div class="card-header-title">
      <div class="card-icon green">🧠</div>
      <div>
        جدول المراجعات المجدولة
        <p style="font-size:11px;color:var(--text-m);font-weight:400;margin-top:1px">
          المراجعات المتبقية هذا اليوم
        </p>
      </div>
    </div>
    <a href="{{ route('memorizations.index') }}"
       style="font-size:12.5px;color:#059669;text-decoration:none;
              font-weight:600;display:flex;align-items:center;gap:4px">
      الحفظيات
      <svg width="14" height="14" fill="none" stroke="currentColor"
           stroke-width="2.2" viewBox="0 0 24 24">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>
  </div>
  <div class="card-body">
    <div style="text-align:center;padding:60px 40px">
      <div style="margin-bottom:20px">
        <span style="font-size:64px;display:block">✅</span>
      </div>
      <p style="font-family:'Amiri',serif;font-size:1.4rem;font-weight:700;
                color:var(--text);margin-bottom:8px">
        لا توجد مراجعات مجدولة اليوم
      </p>
      <p style="font-size:13.5px;color:var(--text-m);margin-bottom:24px;
                line-height:1.5">
        بارك الله فيك! انتهيت من جميع المراجعات المخطط لها.<br/>
        <span style="font-size:12px;opacity:0.7">عد غداً لمتابعة حفظك</span>
      </p>
      <a href="{{ route('memorizations.index') }}"
         style="display:inline-flex;align-items:center;gap:8px;
                padding:11px 24px;border-radius:12px;
                background:linear-gradient(135deg,#0d6b52,#065f46);
                color:#fff;font-size:13.5px;font-weight:700;
                text-decoration:none;
                box-shadow:0 4px 16px rgba(13,107,82,.35);
                transition:transform .2s,box-shadow .2s"
         onmouseover="this.style.transform='translateY(-2px)';
                      this.style.boxShadow='0 6px 20px rgba(13,107,82,.45)'"
         onmouseout="this.style.transform='translateY(0)';
                    this.style.boxShadow='0 4px 16px rgba(13,107,82,.35)'">
        📖 إضافة حفظيات جديدة
        <svg width="15" height="15" fill="none" stroke="currentColor"
             stroke-width="2.2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    </div>
  </div>
</div>

{{-- ═══ معلومات نظام التكرار المتباعد ═══ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:16px;overflow:hidden">
    <div style="padding:18px 22px;background:linear-gradient(135deg,#fffbeb,#fef3c7);
                border-bottom:1px solid var(--border);
                display:flex;align-items:center;gap:12px">
      <span style="font-size:24px">💡</span>
      <p style="font-size:13.5px;font-weight:700;color:#78350f">
        نصيحة اليوم
      </p>
    </div>
    <div style="padding:20px;font-size:13px;color:var(--text-m);
                line-height:1.6">
      <p style="margin-bottom:8px">
        <span style="color:#d97706;font-weight:600">التكرار المتباعد</span> يساعدك على
        تثبيت الحفظ في الذاكرة الطويلة المدى. قم بالمراجعة في الأوقات الموصى بها.
      </p>
      <p style="color:var(--text-m);font-size:12px">
        ⏰ المراجعات المقترحة: اليوم، غداً، بعد 3 أيام، أسبوع، شهر
      </p>
    </div>
  </div>

  <div style="background:var(--card);border:1px solid var(--border);
              border-radius:16px;overflow:hidden">
    <div style="padding:18px 22px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);
                border-bottom:1px solid var(--border);
                display:flex;align-items:center;gap:12px">
      <span style="font-size:24px">🎯</span>
      <p style="font-size:13.5px;font-weight:700;color:#065f46">
        أهدافك
      </p>
    </div>
    <div style="padding:20px">
      <div style="display:flex;align-items:center;justify-content:space-between;
                  margin-bottom:16px;padding-bottom:16px;
                  border-bottom:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text)">
          <span style="font-weight:600">0</span> محفوظ
        </p>
        <p style="font-size:12px;color:var(--text-m)">من أصل 30 جزء</p>
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;
                  margin-bottom:0">
        <p style="font-size:13px;color:var(--text)">
          <span style="font-weight:600">0</span> ختمة
        </p>
        <p style="font-size:12px;color:var(--text-m)">أكملت القرآن كاملاً</p>
      </div>
    </div>
  </div>
</div>

@endsection