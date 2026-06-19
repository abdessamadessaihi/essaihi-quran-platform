<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;

class MushafController extends Controller
{
    private static function getSources()
    {
        return [
            [
                'id'          => 'online',
                'title'       => 'المصحف المحمدي',
                'description' => 'المصحف المحمدي الشريف برواية ورش عن نافع',
                'url'         => 'https://almoshaf-almohammadi.ma/',
                'type'        => 'website',
                'icon'        => '🌐',
                'color'       => ['bg'=>'#ecfdf5','border'=>'#a7f3d0','text'=>'#065f46'],
            ],
           
            [
                'id'          => 'pdf_hafs',
                'title'       => 'مصحف المدينة — PDF',
                'description' => 'مصحف التجويد الملون برواية ورش عن نافع',
                'url'         => route('mushaf.stream'), 
                'type'        => 'pdf',
                'icon'        => '📄',
                'color'       => ['bg'=>'#fffbeb','border'=>'#fde68a','text'=>'#92400e'],
            ],
        ];
    }

    public function index()
    {
        $sources  = self::getSources();
        
        // ✨ إعادة تمرير المتغير الذي يتوقعه الـ Blade لمنع الـ Error
        $bookmark = Auth::check() ? Auth::user()->lastBookmark : null;
        
        // جلب آخر صفحة قرأها المستخدم من قاعدة البيانات
        $userPage = Auth::check() ? Auth::user()->mushaf_page : 1;

        // شحن الثلاثة متغيرات معاً ليعمل الـ Index والـ Reader بسلام كامل
        return view('mushaf.index', compact('sources', 'bookmark', 'userPage'));
    }

    public function reader(Request $request)
    {
        $url   = $request->get('url');
        $title = $request->get('title', 'المصحف الشريف');
        
        // جلب رقم الصفحة للمستخدم الحالي
        $currentPage = Auth::check() ? Auth::user()->mushaf_page : 1;
        
        return view('mushaf.reader', compact('url', 'title', 'currentPage'));
    }

    public function streamMoshaf()
    {
        $path = public_path('moshaf/moshaf_warsh.pdf');

        if (!file_exists($path)) {
            abort(404, 'الملف غير موجود في المسار العام.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Access-Control-Allow-Origin' => '*',
            'Content-Disposition' => 'inline; filename="moshaf_warsh.pdf"'
        ]);
    }

    // حفظ الصفحة الحالية للمستخدم
    public function savePage(Request $request)
    {
        $request->validate([
            'page' => 'required|integer|min:1'
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            $user->mushaf_page = $request->page;
            $user->save();
            return response()->json(['success' => true, 'message' => 'تم حفظ رقم الصفحة بنجاح']);
        }

        return response()->json(['success' => false], 401);
    }
}