@extends('layouts.app')
@section('title', $quranClass->title)

@section('content')
<div class="quran-class-wrapper">
    
    {{-- رأس الصفحة --}}
    <div class="class-header-row">
        <h2 class="class-main-title">{{ $quranClass->title }}</h2>
        <a href="{{ route('quran-classes.index') }}" class="back-list-btn">← العودة للقائمة</a>
    </div>

    {{-- رسائل النجاح --}}
    @if(session('success'))
        <div class="alert-success-box">{{ session('success') }}</div>
    @endif

    {{-- الشبكة البرمجية الرئيسية المتوازنة --}}
    <div class="class-main-grid">
        
        {{-- الجانب الأول: البث والمواد العلمية --}}
        <div class="class-side-column">
            
            {{-- بطاقة البث المباشر --}}
            <div class="info-card text-center">
                <h4 class="card-box-title">🎥 البث المباشر للحصة</h4>
                @if($quranClass->meet_url)
                    <a href="{{ $quranClass->meet_url }}" target="_blank" class="meet-join-btn">
                        🔴 انضم الآن عبر Google Meet
                    </a>
                @else
                    <p class="empty-field-text">المحفظ لم يضع رابط اللقاء المباشر بعد.</p>
                @endif
            </div>

            {{-- بطاقة المادة العلمية والملفات المرفقة للطلاب --}}
            <div class="info-card">
                <h4 class="card-box-title">📖 المادة العلمية والدروس</h4>
                
                @if($quranClass->courses_materials)
                    <div class="materials-content-view">
                        {{ $quranClass->courses_materials }}
                    </div>
                @elseif(!$quranClass->resource_file || count($quranClass->resource_file) == 0)
                    <div class="materials-content-view">
                        لا يوجد دروس أو مراجع مضافة حالياً.
                    </div>
                @endif

                {{-- استعراض وتنزيل الملفات المرفقة للطلاب والجميع --}}
                @if($quranClass->resource_file && count($quranClass->resource_file) > 0)
                    <div style="margin-top:15px; display:flex; flex-direction:column; gap:10px;">
                        <span style="font-size:12.5px; color:var(--text); font-weight:600">📁 المستندات والملفات المرفقة بالحلقة:</span>
                        @foreach($quranClass->resource_file as $file)
                            @php
                                $fileName = is_array($file) ? $file['name'] : 'ملف مرفق';
                                $filePath = is_array($file) ? $file['path'] : $file;
                            @endphp
                            <div style="padding:10px 12px; background:rgba(13,107,82,0.05); border:1px dashed #0d6b52; border-radius:10px; display:flex; align-items:center; justify-content:space-between; gap:10px;">
                                <span style="font-size:12.5px; color:var(--text-m); word-break:break-all;">📄 {{ $fileName }}</span>
                                <a href="{{ asset('storage/' . $filePath) }}" target="_blank" download style="background:#0d6b52; color:#fff; text-decoration:none; padding:4px 10px; border-radius:6px; font-size:11px; font-weight:700; flex-shrink: 0;">
                                    📥 تحميل
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- الجانب الثاني: لوحة تحكم المحفّظ أو واجهة الطالب --}}
        <div class="info-card main-panel-card">
            @can('update', $quranClass)
                <h3 class="panel-section-title">🛠️ لوحة تحكم المحفظ (تحديث الحلقة)</h3>
                
                {{-- قسم إدارة وحذف الملفات الحالية للمحفظ --}}
                @if($quranClass->resource_file && count($quranClass->resource_file) > 0)
                    <div class="form-group-item" style="margin-bottom: 20px; background: rgba(0,0,0,0.01); padding: 12px; border-radius: 10px; border: 1px solid var(--border);">
                        <label class="form-input-label" style="color: #dc2626;">🗑️ إدارة وحذف المرفقات الحالية:</label>
                        <div style="display:flex; flex-direction:column; gap:8px; margin-top:8px;">
                            @foreach($quranClass->resource_file as $file)
                                @php
                                    $fileName = is_array($file) ? $file['name'] : 'ملف مرفق';
                                    $filePath = is_array($file) ? $file['path'] : $file;
                                @endphp
                                <div style="padding:8px 12px; background:var(--bg); border:1px solid var(--border); border-radius:8px; display:flex; align-items:center; justify-content:space-between; gap:10px;">
                                    <span style="font-size:12.5px; color:var(--text); word-break:break-all;">📄 {{ $fileName }}</span>
                                    
                                    <form action="{{ route('quran-classes.remove-file', $quranClass->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الملف نهائياً؟');" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="file_path" value="{{ $filePath }}">
                                        <button type="submit" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:13px; font-weight:600;">❌ حذف</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- نموذج التحديث والرفع الجديد للمحفظ --}}
                <form action="{{ route('quran-classes.update', $quranClass->id) }}" method="POST" enctype="multipart/form-data" class="class-edit-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group-item">
                        <label class="form-input-label">رابط Google Meet / Zoom الجديد:</label>
                        <input type="url" name="meet_url" value="{{ $quranClass->meet_url }}" placeholder="https://meet.google.com/..." class="custom-form-input">
                    </div>

                    <div class="form-group-item">
                        <label class="form-input-label">توقيت ومواعيد الحصص الدورية:</label>
                        <input type="text" name="schedule" value="{{ $quranClass->schedule }}" placeholder="مثال: كل سبت وأربعاء في الساعة 8 مساءً" class="custom-form-input">
                    </div>

                    <div class="form-group-item">
                        <label class="form-input-label">نصوص الدروس والملاحظات:</label>
                        <textarea name="courses_materials" rows="5" placeholder="اكتب المنهج الحالي هنا..." class="custom-form-textarea">{{ $quranClass->courses_materials }}</textarea>
                    </div>

                    <div class="form-group-item">
                        <label class="form-input-label">رفع كتب، مراجع أو وثائق إضافية للحلقة (يمكنك اختيار عدة ملفات معاً):</label>
                        <input type="file" name="resource_files[]" multiple class="custom-form-input" style="padding:8px;">
                        
                        @if($quranClass->resource_file && count($quranClass->resource_file) > 0)
                            <small class="file-exists-warning" style="color:#059669; margin-top:4px; display:block;">
                                💡 توجد حالياً ({{ count($quranClass->resource_file) }}) ملفات مرفوعة، رفع ملفات جديدة سيُضاف إليها.
                            </small>
                        @endif
                    </div>

                    <button type="submit" class="form-submit-btn">💾 حفظ وتحديث الحلقة فوراً</button>
                </form>

                {{-- قائمة الطلاب المقيدين مع ميزة إزالة طالب للمحفظ --}}
                <h4 class="students-list-title">👥 قائمة الطلاب المقيدين في حلقتك ({{ $quranClass->students->count() }} طالب):</h4>
                <ul class="students-html-list" style="list-style: none; padding-right: 0;">
                    @foreach($quranClass->students as $student)
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 6px 10px; background: rgba(0,0,0,0.01); border: 1px solid var(--border); border-radius: 8px; margin-bottom: 6px;">
                            <div>
                                <strong>{{ $student->name }}</strong> 
                                <span class="student-email-span">({{ $student->email }})</span>
                            </div>
                            
                            {{-- نموذج إزالة الطالب --}}
                            <form action="{{ route('quran-classes.remove-student', [$quranClass->id, $student->id]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في إزالة هذا الطالب من حلقتك تماماً؟');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #dc2626; font-size: 11.5px; font-weight: bold; cursor: pointer;">❌ إزالة</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                {{-- واجهة الطالب البسيطة --}}
                <h3 class="student-welcome-title">بيانات الحلقة الإرشادية</h3>
                <p class="student-welcome-desc">مرحباً بك يا <strong>{{ Auth::user()->name }}</strong> في هذه الحلقة المباركة، يرجى الالتزام بالمواعيد وتحميل المرفقات المتاحة من قبل الشيخ للتحضير.</p>
                
                <div class="student-summary-box">
                    <strong>👨‍🏫 محفظ الحلقة الحالي:</strong> {{ $quranClass->mohafid->name }} 
                    <br><br>
                    <strong>📅 مواعيد التسميع واللقاء:</strong> {{ $quranClass->schedule ?? 'لم يحدد الشيخ الموعد بشكل رسمي بعد' }}
                </div>

                {{-- زر مغادرة الانسحاب للطالب --}}
                <form action="{{ route('quran-classes.leave', $quranClass->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في الانسحاب ومغادرة هذه الحلقة القرآنية؟');" style="margin-top: 25px; text-align: left;">
                    @csrf
                    <button type="submit" style="background: none; border: 1px solid #dc2626; color: #dc2626; padding: 8px 16px; border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif;">
                        🏃 مغادرة والانسحاب من الحلقة
                    </button>
                </form>
            @endcan
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.quran-class-wrapper { direction: rtl; padding: 20px; font-family: 'Tajawal', sans-serif; }
.class-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
.class-main-title { font-family: 'Amiri', serif; color: #0d6b52; margin: 0; font-size: 1.75rem; }
.back-list-btn { color: var(--text-m); text-decoration: none; font-size: 13.5px; font-weight: 600; }
.alert-success-box { background: #d1fae5; color: #065f46; padding: 14px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 600; }
.class-main-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 24px; align-items: start; }
.class-side-column { display: flex; flex-direction: column; gap: 20px; }
.info-card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
.main-panel-card { padding: 24px; }
.text-center { text-align: center; }
.card-box-title { margin: 0 0 14px 0; color: var(--text); font-size: 15px; font-weight: 700; }
.meet-join-btn { display: inline-block; background: #dc2626; color: #fff; padding: 12px 24px; border-radius: 30px; text-decoration: none; font-weight: 700; font-size: 13.5px; box-shadow: 0 4px 12px rgba(220,38,38,0.2); }
.empty-field-text { color: var(--text-m); font-size: 13px; margin: 5px 0; }
.materials-content-view { white-space: pre-line; color: var(--text-m); font-size: 13.5px; line-height: 1.7; }
.panel-section-title { color: var(--text); border-bottom: 1px solid var(--border); padding-bottom: 12px; margin-top: 0; font-size: 16px; font-weight: 700; }
.class-edit-form { margin-top: 20px; display: flex; flex-direction: column; gap: 16px; }
.form-group-item { display: flex; flex-direction: column; }
.form-input-label { display: block; margin-bottom: 6px; font-size: 12.5px; color: var(--text); font-weight: 700; }
.custom-form-input, .custom-form-textarea { width: 100%; padding: 11px; border-radius: 10px; border: 1px solid var(--border); background: var(--bg); color: var(--text); font-size: 13px; box-sizing: border-box; }
.custom-form-textarea { font-family: inherit; resize: vertical; }
.file-exists-warning { color: #059669; margin-top: 4px; font-size: 11.5px; }
.form-submit-btn { background: #0d6b52; color: #fff; border: none; padding: 13px; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Tajawal', sans-serif; font-size: 13.5px; box-shadow: 0 4px 12px rgba(13,107,82,0.15); }
.students-list-title { margin: 28px 0 12px 0; color: var(--text); border-top: 1px solid var(--border); padding-top: 18px; font-size: 14px; }
.student-email-span { color: var(--text-m); font-size: 11.5px; }
.student-welcome-title { color: #0d6b52; font-family: 'Amiri', serif; margin-top: 0; font-size: 1.5rem; }
.student-welcome-desc { color: var(--text-m); font-size: 13.5px; line-height: 1.8; }
.student-summary-box { margin-top: 20px; padding: 16px; background: var(--bg); border-radius: 10px; border: 1px solid var(--border); font-size: 13px; color: var(--text); }

@media (max-width: 991px) { .class-main-grid { grid-template-columns: 1fr; gap: 20px; } }
@media (max-width: 576px) { .quran-class-wrapper { padding: 12px; } .class-header-row { flex-direction: column; align-items: flex-start; gap: 6px; } .class-main-title { font-size: 1.5rem; } .main-panel-card { padding: 16px; } .meet-join-btn { width: 100%; box-sizing: border-box; text-align: center; } }
</style>
@endpush