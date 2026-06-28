<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ── Controllers ──────────────────────────────────────────
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\KhatmaController;
use App\Http\Controllers\JuzAllocationController;
use App\Http\Controllers\DailyWardController;
use App\Http\Controllers\MemorizationController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFamilyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MushafController;
use App\Http\Controllers\TilawatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\QuranClassController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\GoogleController;

// ════════════════════════════════════════════════════════
// الصفحة الرئيسية العامة والمسارات المفتوحة
// ════════════════════════════════════════════════════════
Route::get('/', fn() => view('welcome'))->name('home');

Route::get('/child/login', [App\Http\Controllers\Auth\ChildAuthController::class, 'showLogin'])->name('child.login');
Route::post('/child/login', [App\Http\Controllers\Auth\ChildAuthController::class, 'login'])->name('child.login.submit');

// ════════════════════════════════════════════════════════
// تسجيل الدخول بواسطة جوجل (خارج الحماية)
// ════════════════════════════════════════════════════════
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);



// ════════════════════════════════════════════════════════
// المنطقة المحمية (المستخدمين المسجلين والنشطين فقط)
// ════════════════════════════════════════════════════════
Route::middleware(['auth', 'verified', 'active_member'])->group(function () {
     
     Route::prefix('my-children')->name('children.')->group(function () {
          Route::post('/store', [FamilyController::class, 'storeChild'])->name('store');
          Route::post('/{child}/impersonate', [FamilyController::class, 'impersonateChild'])->name('impersonate');
          Route::delete('/{child}', [FamilyController::class, 'destroyChild'])->name('destroy');
     });

}); 
// ════════════════════════════════════════════════════════
// خدمات المنصة المحمية بـ التفعيل والتوثيق (الحسابات المفعّلة وجوجل)
// ════════════════════════════════════════════════════════
Route::middleware(['auth', 'verified'])->group(function () {
    
    // لوحة التحكم
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
     
    // الملف الشخصي الموحد
    Route::prefix('profile')->name('profile.')->group(function () {
         Route::get('/',           [ProfileController::class, 'show'])   ->name('show');
         Route::get('/edit',       [ProfileController::class, 'edit'])   ->name('edit');
         Route::patch('/',         [ProfileController::class, 'update']) ->name('update');
         Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password');
         Route::delete('/',        [ProfileController::class, 'destroy'])->name('destroy');
    });

    // العائلات
    Route::prefix('families')->name('families.')->group(function () {
        Route::get('/', [FamilyController::class, 'index'])->name('index');
        Route::get('/create', [FamilyController::class, 'create'])->name('create');
        Route::post('/', [FamilyController::class, 'store'])->name('store');
        Route::get('/{family}', [FamilyController::class, 'show'])->name('show');
        Route::get('/{family}/edit', [FamilyController::class, 'edit'])->name('edit')->middleware('family_admin');
        Route::patch('/{family}', [FamilyController::class, 'update'])->name('update')->middleware('family_admin');
        Route::post('/{family}/join', [FamilyController::class, 'join'])->name('join');

        // إدارة الأعضاء
        Route::post('/{family}/members/{member}/approve', [FamilyController::class, 'approveMember'])->name('members.approve')->middleware('family_admin');
        Route::post('/{family}/members/{member}/reject', [FamilyController::class, 'rejectMember'])->name('members.reject')->middleware('family_admin');
        Route::post('/{family}/members/{member}/promote', [FamilyController::class, 'promoteToAdmin'])->name('members.promote')->middleware('family_admin');
        Route::post('/{family}/members/{member}/demote', [FamilyController::class, 'demoteToMember'])->name('members.demote')->middleware('family_admin');
        Route::post('/{family}/members/{member}/suspend', [FamilyController::class, 'suspendMember'])->name('members.suspend')->middleware('family_admin');
        Route::post('/{family}/members/{member}/reactivate', [FamilyController::class, 'reactivateMember'])->name('members.reactivate')->middleware('family_admin');
        Route::delete('/{family}/members/{member}', [FamilyController::class, 'removeMember'])->name('members.remove')->middleware('family_admin');
    });

    // الختمات
    Route::prefix('khatmas')->name('khatmas.')->group(function () {
        Route::get('/', [KhatmaController::class, 'index'])->name('index');
        Route::get('/create', [KhatmaController::class, 'create'])->name('create');
        Route::post('/', [KhatmaController::class, 'store'])->name('store');
        Route::get('/{khatma}', [KhatmaController::class, 'show'])->name('show');
        Route::get('/{khatma}/edit', [KhatmaController::class, 'edit'])->name('edit');
        Route::patch('/{khatma}', [KhatmaController::class, 'update'])->name('update');
        Route::delete('/{khatma}', [KhatmaController::class, 'destroy'])->name('destroy');

        // أجزاء الختمة
        Route::post('/{khatma}/juz/{juz}/claim', [JuzAllocationController::class, 'claim'])->name('juz.claim');
        Route::post('/{khatma}/juz/{juz}/start', [JuzAllocationController::class, 'start'])->name('juz.start');
        Route::post('/{khatma}/juz/{juz}/complete', [JuzAllocationController::class, 'complete'])->name('juz.complete');
    });

    // الورد اليومي
    Route::prefix('ward')->name('ward.')->group(function () {
        Route::get('/', [DailyWardController::class, 'index'])->name('index');
        Route::post('/', [DailyWardController::class, 'store'])->name('store');
        Route::post('/complete', [DailyWardController::class, 'complete'])->name('complete');
        Route::patch('/{ward}', [DailyWardController::class, 'update'])->name('update');
        Route::delete('/{ward}', [DailyWardController::class, 'destroy'])->name('destroy');
    });

    // الحفظ
    Route::prefix('memorizations')->name('memorizations.')->group(function () {
        Route::get('/', [MemorizationController::class, 'index'])->name('index');
        Route::get('/create', [MemorizationController::class, 'create'])->name('create');
        Route::post('/', [MemorizationController::class, 'store'])->name('store');
        Route::get('/{memorization}/edit', [MemorizationController::class, 'edit'])->name('edit');
        Route::patch('/{memorization}', [MemorizationController::class, 'update'])->name('update');
        Route::delete('/{memorization}', [MemorizationController::class, 'destroy'])->name('destroy');
    });

    // التلاوات
    Route::get('/tilawats', [TilawatController::class, 'index'])->name('tilawats.index');
    Route::delete('/tilawats', [TilawatController::class, 'destroy'])->name('tilawats.destroy');
    Route::post('/tilawats', [TilawatController::class, 'store'])->name('tilawats.store');

    // المقالات التدبرية
    Route::prefix('articles')->name('articles.')->group(function () {
         Route::get('/',              [ArticleController::class, 'index']) ->name('index');
         Route::get('/create',        [ArticleController::class, 'create'])->name('create');
         Route::post('/',             [ArticleController::class, 'store']) ->name('store');
         Route::get('/{article}',     [ArticleController::class, 'show'])  ->name('show');
         Route::get('/{article}/edit',[ArticleController::class, 'edit'])  ->name('edit');
         Route::patch('/{article}',   [ArticleController::class, 'update'])->name('update');
         Route::delete('/{article}',  [ArticleController::class, 'destroy'])->name('destroy');
     });
     Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');

    // المصحف المشترك
    Route::prefix('mushaf')->name('mushaf.')->group(function () {
         Route::get('/', [MushafController::class, 'index'])->name('index');
         Route::get('/reader', [MushafController::class, 'reader'])->name('reader');
         Route::get('/stream-file', [MushafController::class, 'streamMoshaf'])->name('stream');
         Route::post('/save-page', [MushafController::class, 'savePage'])->name('save-page');
    });

    // المراجعة
    Route::prefix('revisions')->name('revisions.')->group(function () {
        Route::get('/', [RevisionController::class, 'index'])->name('index');
        Route::post('/{revision}/complete', [RevisionController::class, 'complete'])->name('complete');
        Route::post('/{revision}/skip', [RevisionController::class, 'skip'])->name('skip');
    });

    // لوحة الشرف
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // الإشعارات
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('read-all');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // الإشارات المرجعية
    Route::prefix('bookmarks')->name('bookmarks.')->group(function () {
        Route::get('/', [BookmarkController::class, 'index'])->name('index');
        Route::post('/', [BookmarkController::class, 'store'])->name('store');
        Route::delete('/{bookmark}', [BookmarkController::class, 'destroy'])->name('destroy');
    });

    // حلقات القرآن الكريم
    Route::resource('quran-classes', QuranClassController::class)->except(['create', 'edit']);
    Route::post('/quran-classes/{quranClass}/request', [QuranClassController::class, 'sendRequest'])->name('quran-classes.request');
    Route::post('/notifications/{notification}/accept-class', [QuranClassController::class, 'acceptRequest'])->name('notifications.accept-class');
    Route::post('/notifications/{notification}/reject-class', [QuranClassController::class, 'rejectRequest'])->name('notifications.reject-class');
    Route::delete('/quran-classes/{quranClass}/remove-file', [QuranClassController::class, 'removeFile'])->name('quran-classes.remove-file');
    Route::delete('/quran-classes/{quranClass}/remove-student/{student}', [QuranClassController::class, 'removeStudent'])->name('quran-classes.remove-student');
    Route::post('/quran-classes/{quranClass}/leave', [QuranClassController::class, 'leaveClass'])->name('quran-classes.leave');

});

// ════════════════════════════════════════════════════════
// لوحة الإدارة العامة — للمدير العام (Super Admin) فقط
// ════════════════════════════════════════════════════════
Route::prefix('admin')->name('admin.')->middleware('super_admin')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // المستخدمون للإدارة
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',         [AdminUserController::class, 'index'])->name('index');
        Route::get('/{user}',   [AdminUserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}',[AdminUserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('toggle');
        Route::post('/{user}/notify', [AdminUserController::class, 'sendNotification'])->name('notify');
        Route::post('/broadcast',     [AdminUserController::class, 'broadcastNotification'])->name('broadcast');
    });

    // العائلات للإدارة
    Route::prefix('families')->name('families.')->group(function () {
         Route::get('/',         [AdminFamilyController::class, 'index'])->name('index');
         Route::get('/{family}', [AdminFamilyController::class, 'show'])->name('show');
         Route::patch('/{family}',[AdminFamilyController::class, 'update'])->name('update');
         Route::delete('/{family}',[AdminFamilyController::class,'destroy'])->name('destroy');
         Route::post('/{family}/members/{member}/remove', [AdminFamilyController::class, 'removeMember'])->name('members.remove');
         Route::post('/{family}/notify', [AdminFamilyController::class, 'sendNotification'])->name('notify');
    });
});

// ════════════════════════════════════════════════════════
// حزم مسارات مصادقة لارافيل الأساسية (Breeze & Auth)
// ════════════════════════════════════════════════════════
require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');