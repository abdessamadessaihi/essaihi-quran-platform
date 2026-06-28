<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware; // 👈 استدعاء الواجهة الجديدة
use Illuminate\Routing\Controllers\Middleware;    // 👈 استدعاء كلاس الميدل وير

class HomeController extends Controller implements HasMiddleware // 👈 تطبيق الواجهة هنا
{
    /**
     * إعداد الـ Middleware بالطريقة الحديثة المتوافقة مع إصدار لارافيل الحالي.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            // حماية جميع دوال الكنترولر (بما فيها صفحة الـ home) لتفتح للمسجلين فقط
            new Middleware('auth'),
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
// 1. قم بجلب البيانات أو ضع مصفوفة تجريبية مؤقتاً لتتخلص من الخطأ فوراً
    $dashboardStats = [
        'users_count' => \App\Models\User::count(),
        // أضف أي حقول أخرى يطلبها ملف الـ blade في السطر 275
    ];

    // 2. أرسل المتغير باستخدام compact
    return view('home', compact('dashboardStats'));    }
}