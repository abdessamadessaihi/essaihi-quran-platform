<?php

namespace App\Http\Controllers;

use App\Models\QuranClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class QuranClassController extends Controller
{
   public function index()
{
    $user = auth()->user();

    // جلب جميع الحلقات القرآنية المتاحة في المنصة ليراها الجميع
    $classes = QuranClass::latest()->get();

    // جلب قائمة المحفظين فقط لنموذج الإنشاء الخاص بالآدمن
    $mohafids = [];
    if ($user->isAdmin() || $user->isSuperAdmin()) {
        $mohafids = \App\Models\User::where('role', 'mohafid')->get();
    }

    return view('quran_classes.index', compact('classes', 'mohafids'));
}
public function store(Request $request)
{
    abort_unless(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin(), 403);

    $validated = $request->validate([
        'title'       => 'required|string|max:150', // 🌟 تعديل: تغيير 'name' إلى 'title' ليطابق قاعدة البيانات
        'mohafid_id'  => 'required|exists:users,id',
        'description' => 'nullable|string',
    ]);

    QuranClass::create($validated);

    return back()->with('success', 'تم إنشاء الحلقة القرآنية بنجاح وتعيين المحفظ المختار ✅');
}
public function removeFile(QuranClass $quranClass, Request $request)
{
    // 💡 تم التعديل هنا لتبسيط الفحص واستخدام صلاحية التحديث المعتمدة والمستقرة
    if (!$request->user()->can('update', $quranClass)) {
        abort(403, 'عذراً، لا تملك الصلاحية لإدارة ملفات هذه الحلقة.');
    }

    $fileCode = $request->input('file_path');
    $currentFiles = $quranClass->resource_file ?? [];

    // البحث عن الملف داخل مصفوفة الملفات وحذفه
    foreach ($currentFiles as $key => $file) {
        $filePath = is_array($file) ? $file['path'] : $file;
        
        if ($filePath === $fileCode) {
            // 1. حذف الملف الفيزيائي من السيرفر (Storage)
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            // 2. إزالته من المصفوفة
            unset($currentFiles[$key]);
            break;
        }
    }

    // إعادة ترتيب مصفوفة الـ Indexes بعد الحذف وحفظها
    $quranClass->update([
        'resource_file' => array_values($currentFiles)
    ]);

    return back()->with('success', 'تم حذف الملف بنجاح 🗑️');
}

public function show(QuranClass $quranClass)
{
    $user = auth()->user();

    // الآدمن والمحفظ المسؤول يدخلون مباشرة، غيرهم يتم التثبت من جدول الـ class_student
    if (! $user->isAdmin() && ! $user->isSuperAdmin() && $quranClass->mohafid_id !== $user->id) {
        
        $isEnrolled = $quranClass->students()->where('student_id', $user->id)->exists();
        
        if (! $isEnrolled) {
            return redirect()->route('quran-classes.index')->with('error', 'عذراً، يجب تقديم طلب انضمام وقبولك في الحلقة أولاً قبل الدخول.');
        }
    }

    return view('quran_classes.show', compact('quranClass'));
}

// 1. دالة إزالة (طرد) الطالب بواسطة المحفظ
public function removeStudent(QuranClass $quranClass, User $student, Request $request)
{
    // التأكد من أن المستخدم الحالي هو المحفظ صاحب الحلقة أو آدمن
    $request->user()->can('update', $quranClass) || abort(403);

    // فك ارتباط الطالب بالحلقة من الجدول الوسيط
    $quranClass->students()->detach($student->id);

    return back()->with('success', "تم إزالة الطالب {$student->name} من الحلقة بنجاح.");
}

// 2. دالة مغادرة الطالب للحلقة بنفسه
public function leaveClass(QuranClass $quranClass)
{
    $student = auth()->user();

    // فك ارتباط الطالب الحالي بالحلقة
    $quranClass->students()->detach($student->id);

    return redirect()->route('quran-classes.index')->with('success', 'لقد قمت بمغادرة الحلقة بنجاح.');
}
public function update(Request $request, QuranClass $quranClass)
{
    $request->user()->can('update', $quranClass) || abort(403);
    $request->validate([
        'meet_url'          => 'nullable|url',
        'schedule'          => 'nullable|string|max:255',
        'courses_materials' => 'nullable|string',
        'resource_files.*'  => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:10240', // يدعم عدة ملفات
    ]);
// جلب الملفات القديمة إن وجدت، أو إنشاء مصفوفة فارغة
$currentFiles = $quranClass->resource_file ?? [];

// إذا قام المحفظ برفع ملفات جديدة
if ($request->hasFile('resource_files')) {
    foreach ($request->file('resource_files') as $file) {
        // 💡 جلب الاسم الأصلي للملف الذي رفعه المحفظ
        $originalName = $file->getClientOriginalName();
        
        // تخزين الملف باسمه العشوائي الآمن على السيرفر
        $path = $file->store('quran_resources', 'public');
        
        // 💡 حفظ المسار والاسم الأصلي معاً داخل المصفوفة
        $currentFiles[] = [
            'name' => $originalName,
            'path' => $path
        ];
    }
}

    $quranClass->update([
        'meet_url'          => $request->meet_url,
        'schedule'          => $request->schedule,
        'courses_materials' => $request->courses_materials,
        'resource_file'     => $currentFiles, // حفظ المصفوفة كاملة
    ]);
        return redirect()->back()->with('success', 'تم تحديث بيانات الحصة بنجاح.');
    }

   
// 1. أضف هذه الدالة داخل الكنترولر لإرسال الطلب عبر نظام الإشعارات بدلاً من الواتساب
public function sendRequest(QuranClass $quranClass)
{
    $student = auth()->user();

    // منع الطالب من إرسال طلب تكراري إذا كان مسجلاً بالفعل
    if ($quranClass->students()->where('student_id', $student->id)->exists()) {
        return back()->with('error', 'أنت مسجل بالفعل في هذه الحلقة.');
    }

    // استدعاء الخدمة لإرسال الإشعار للمحفظ
    NotificationService::onQuranClassRequest($quranClass->toArray(), $student);

    return back()->with('success', 'تم إرسال طلب الانضمام إلى المحفّظ بنجاح، يرجى انتظار القبول واشعارات النظام ⏳');
}

// 2. دالة قبول الطالب وإضافته للجدول الوسيط class_student
public function acceptRequest(Notification $notification)
{
    $data = $notification->data;
    $quranClass = QuranClass::findOrFail($data['quran_class_id']);
    
    // التأكد أن المحفّظ الحالي هو صاحب الصلاحية للقبول
    abort_unless(auth()->id() === $quranClass->mohafid_id || auth()->user()->isAdmin(), 403);

    // ربط الطالب بالحلقة القرآنية (تجنب التكرار بـ syncWithoutDetaching)
    $quranClass->students()->syncWithoutDetaching([$data['student_id']]);

    // إرسال إشعار بنجاح القبول للطالب
    NotificationService::onClassRequestAccepted($quranClass->title, $data['student_id']);

    // حذف إشعار الطلب أو تحويله كمقروء لتنظيف اللوحة
    $notification->delete();

    return back()->with('success', "تم قبول الطالب {$data['student_name']} بنجاح في حلقتك القرآنية.");
}

// 3. دالة الرفض
public function rejectRequest(Notification $notification)
{
    $data = $notification->data;
    abort_unless(auth()->id() === intval($notification->user_id) || auth()->user()->isAdmin(), 403);

    NotificationService::onClassRequestRejected($data['class_title'], $data['student_id']);
    
    $notification->delete();

    return back()->with('success', 'تم رفض طلب الانضمام وإشعار الطالب.');
}
}