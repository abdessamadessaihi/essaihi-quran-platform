@extends('layouts.app')
@section('title', 'حلقات التحفيظ والتجويد')
@section('content')

<div style="max-width:1100px; margin:0 auto;">

    {{-- الرأس --}}
    <div style="display:flex; align-items:center; justify-content:between; margin-bottom:28px; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 style="font-size:22px; font-weight:700; color:var(--text)">📚 حلقات التحفيظ وحصص التجويد</h1>
            <p style="font-size:13px; color:var(--text-m); margin-top:4px">إدارة ومتابعة الحلقات القرآنية المباشرة والغرف التعليمية</p>
        </div>

        {{-- لا يظهر زر الإنشاء إلا للآدمن الأعلى --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <button onclick="document.getElementById('createClassModal').style.display='block'"
                    style="background:#059669; color:#fff; border:none; padding:10px 20px; border-radius:10px; font-weight:600; cursor:pointer; font-family:'Tajawal'">
                ➕ إنشاء حلقة جديدة
            </button>
        @endif
    </div>

    {{-- 🌟 لوحة الآدمن: نافذة منبثقة (Modal) لإنشاء الحلقة 🌟 --}}
    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
    <div id="createClassModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; backdrop-filter:blur(4px)">
        <div style="background:var(--card); max-width:500px; margin:100px auto; padding:24px; border-radius:16px; border:1px solid var(--border); position:relative">
            <h3 style="font-weight:700; margin-bottom:16px; color:var(--text)">إنشاء حلقة قرآنية جديدة</h3>
            
            <form action="{{ route('quran-classes.store') }}" method="POST">
                @csrf
                <div style="margin-bottom:14px">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px">اسم الحلقة</label>
                    <input type="text" name="title" required placeholder="مثال: حلقة الإمام نافع" 
                           style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; background:var(--bg); color:var(--text)">
                </div>

                <div style="margin-bottom:14px">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px">تعيين المحفّظ المشرف</label>
                    <select name="mohafid_id" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; background:var(--bg); color:var(--text)">
                        <option value="">-- اختر محفظاً للحلقة --</option>
                        @foreach($mohafids ?? [] as $mohafid)
                            <option value="{{ $mohafid->id }}">{{ $mohafid->name }} ({{ $mohafid->email }})</option>
                        @endforeach
                    </select>
                    <p style="font-size:11px; color:var(--text-m); margin-top:4px">ملاحظة: يجب أن يكون دور المستخدم المختار "mohafid" ليظهر هنا.</p>
                </div>

                <div style="margin-bottom:20px">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px">وصف مختصر</label>
                    <textarea name="description" rows="3" placeholder="أيام الحلقة، الشريحة المستهدفة..." 
                              style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px; background:var(--bg); color:var(--text); font-family:'Tajawal'"></textarea>
                </div>

                <div style="display:flex; justify-content:end; gap:10px">
                    <button type="button" onclick="document.getElementById('createClassModal').style.display='none'"
                            style="background:none; border:1px solid var(--border); padding:8px 16px; border-radius:8px; color:var(--text-m); cursor:pointer">إلغاء</button>
                    <button type="submit" style="background:#059669; color:#fff; border:none; padding:8px 20px; border-radius:8px; cursor:pointer">حفظ الحلقة</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- عرض الحلقات المتاحة --}}
    @if($classes->isEmpty())
        <div style="background:var(--card); border:1px solid var(--border); border-radius:16px; padding:40px; text-align:center">
            <span style="font-size:48px; display:block; margin-bottom:16px">🏫</span>
            <p style="font-size:15px; font-weight:700; color:var(--text)">لا توجد حلقات قرآنية مضافة حالياً</p>
            @if(auth()->user()->isSuperAdmin())
                <p style="font-size:13px; color:var(--text-m); margin-top:4px">يمكنك البدء بإنشاء أول حلقة وتعيين محفظ لها بالضغط على زر الإنشاء بالأعلى.</p>
            @else
                <p style="font-size:13px; color:var(--text-m); margin-top:4px">يرجى التواصل مع إدارة المنصة لتسجيلك في الحلقات القرآنية.</p>
            @endif
        </div>
    @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px">
<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:20px">
    @foreach($classes as $class)
        <div style="background:var(--card); border:1px solid var(--border); border-radius:16px; padding:20px; display:flex; flex-direction:column; justify-content:space-between">
            <div>
                <div style="display:flex; align-items:center; justify-content:between; margin-bottom:12px">
                    <h3 style="font-size:16px; font-weight:700; color:var(--text)">{{ $class->title }}</h3>
                    
                    {{-- فحص حالة اشتراك المستخدم الحالي --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() || $class->mohafid_id === auth()->id())
                        <span style="font-size:11px; padding:3px 8px; background:rgba(5,150,105,0.1); color:#059669; border-radius:100px; font-weight:600">إدارة / إشراف</span>
                    @elseif($class->students->contains(auth()->id()))
                        <span style="font-size:11px; padding:3px 8px; background:rgba(37,99,235,0.1); color:#2563eb; border-radius:100px; font-weight:600">✅ مشترك فيها</span>
                    @else
                        <span style="font-size:11px; padding:3px 8px; background:rgba(217,119,6,0.1); color:#d97706; border-radius:100px; font-weight:600">متاحة للانضمام</span>
                    @endif
                </div>
                
                <p style="font-size:13px; color:var(--text-m); margin-bottom:14px">{{ $class->description ?? 'لا يوجد وصف مضاف.' }}</p>
                
                <div style="border-top:1px dashed var(--border); padding-top:12px; margin-bottom:16px; font-size:12.5px">
                    <p style="color:var(--text)"><strong>👤 المحفظ:</strong> {{ $class->mohafid->name ?? 'غير معين' }}</p>
                    <p style="color:var(--text-m); margin-top:4px;"><strong>📅 المواعيد:</strong> {{ $class->schedule ?? 'لم تحدد بعد' }}</p>
                </div>
            </div>

            <div>
                {{-- أزرار التحكم الصلاحية والدخول --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() || $class->mohafid_id === auth()->id() || $class->students->contains(auth()->id()))
                    {{-- يملك صلاحية الدخول --}}
                    <a href="{{ route('quran-classes.show', $class->id) }}" 
                       style="display:block; text-align:center; background:var(--primary); color:#fff; padding:10px; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none">
                        🎯 دخول غرفة الحلقة
                    </a>
              @else
    {{-- طالب غير مسجل -> يرسل طلباً برمجياً للمحفّظ --}}
    <form action="{{ route('quran-classes.request', $class->id) }}" method="POST">
        @csrf
        <button type="submit" style="display:block; width:100%; text-align:center; background:#059669; color:#fff; border:none; padding:10px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Tajawal'">
            📥 طلب انضمام للمجموعة التعليمية
        </button>
    </form>
@endif
            </div>
        </div>
    @endforeach
</div>
    @endif

</div>

@endsection