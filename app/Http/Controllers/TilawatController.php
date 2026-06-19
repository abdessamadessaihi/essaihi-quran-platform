<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tilawat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class TilawatController extends Controller
{
    // عرض الصفحة الرئيسية للتلاوات
    public function index()
    {
        $featured = Tilawat::where('is_featured', 1)->get();
        $tilawats = Tilawat::orderBy('created_at', 'desc')->paginate(9);

        // التحقق مما إذا كان المستخدم الحالي Super Admin
        $isAdmin = Auth::check() && Auth::user()->isSuperAdmin(); 

        return view('tilawats.index', compact('featured', 'tilawats', 'isAdmin'));
    }

 public function store(Request $request)
{
    // حماية برمجية صارمة في الخلفية
    if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
        abort(403, 'Action non autorisée.');
    }

    // تم إزالة required من media_file وجعله اختيارياً بالتبادل مع رابط اليوتيوب
    $request->validate([
        'title'        => 'required|string|max:255',
        'reciter_name' => 'required|string|max:255',
        'surah_name'   => 'nullable|string|max:255',
        'media_file'   => 'nullable|file|mimes:mp3,mp4,mpeg|max:153600', 
        'youtube_url'  => 'nullable|url|max:255',
    ]);

    // السيناريو الأول: إذا أدخل الأدمن رابط يوتيوب
    if ($request->filled('youtube_url')) {
        Tilawat::create([
            'title'        => $request->title,
            'reciter_name' => $request->reciter_name,
            'surah_name'   => $request->surah_name,
            'media_type'   => 'youtube', // تحديد النوع يوتيوب
            'media_url'    => $request->youtube_url, // حفظ الرابط مباشرة
            'is_featured'  => $request->has('is_featured') ? true : false
        ]);

        return redirect()->back()->with('success', 'تم نشر تلاوة اليوتيوب بنجاح! 🎉');
    }

    // السيناريو الثاني: إذا اختار الأدمن رفع ملف محلي (MP3/MP4)
    if ($request->hasFile('media_file') && $request->file('media_file')->isValid()) {
        $file = $request->file('media_file');
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension === 'mpeg') {
            $extension = 'mp3';
        }

        $path = $file->store('tilawats', 'public');
        $url = Storage::url($path);

        Tilawat::create([
            'title'        => $request->title,
            'reciter_name' => $request->reciter_name,
            'surah_name'   => $request->surah_name,
            'media_type'   => $extension, 
            'media_url'    => $url,
            'is_featured'  => $request->has('is_featured') ? true : false
        ]);

        return redirect()->back()->with('success', 'تم رفع وحفظ ملف التلاوة بنجاح! 💾');
    }

    // إذا لم يقم بإدخال أي ميديا
    return redirect()->back()->withErrors(['media_file' => 'يرجى تزويد المنصة برابط يوتيوب أو اختيار ملف صوتي/مرئي للمتابعة.']);
}
    // حذف التلاوة
    public function destroy(Tilawat $tilawat)
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        if (!str_contains($tilawat->media_url, 'youtube') && !str_contains($tilawat->media_url, 'youtu.be')) {
            $relativeStoragePath = str_replace('/storage/', '', $tilawat->media_url);
            if (Storage::disk('public')->exists($relativeStoragePath)) {
                Storage::disk('public')->delete($relativeStoragePath);
            }
        }

        $tilawat->delete();

        return redirect()->back()->with('success', 'تم حذف التلاوة وملفها المرتبط بنجاح 🗑️');
    }
}