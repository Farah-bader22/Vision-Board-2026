<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan; // أضفنا هذا السطر
use Inertia\Inertia;

// الصفحة الرئيسية - تم التعديل هنا لإرسال الأهداف
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        // جلب آخر 3 أهداف للمستخدم إذا كان مسجلاً دخوله
        'goals' => auth()->check()
            ? auth()->user()->goals()->where('is_archived', false)->latest()->take(3)->get()
            : [],
    ]);
});

// مسارات الداشبورد والأهداف - محمية بـ Middleware
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. عرض الداشبورد (الأهداف النشطة)
    Route::get('/dashboard', [GoalController::class, 'index'])->name('dashboard');

    // 2. إضافة هدف جديد
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');

    // 3. تحديث/تعديل هدف موجود
    Route::put('/goals/{goal}', [GoalController::class, 'update'])->name('goals.update');

    // 4. حذف هدف نهائياً
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');

    // 5. صفحة الإحصائيات
    Route::get('/stats', [GoalController::class, 'stats'])->name('stats');

    // --- ميزات الأرشفة الجديدة ---

    // 6. صفحة الأرشيف (لعرض الأهداف المكتملة والمؤرشفة)
    Route::get('/archive', [GoalController::class, 'archiveIndex'])->name('archive');

    // 7. تنفيذ عملية الأرشفة أو الاستعادة
    Route::patch('/goals/{goal}/archive', [GoalController::class, 'toggleArchive'])->name('goals.archive');
});

// مسارات الملف الشخصي
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ------------------------------------------------------------------
// مسار الطوارئ لبناء قاعدة البيانات (لأن Shell في Render يحتاج اشتراك)
// ------------------------------------------------------------------
Route::get('/init-db', function () {
    try {
        // سيقوم هذا الأمر بمسح أي جداول قديمة وبناء الجداول الجديدة فوراً
        Artisan::call('migrate:fresh --force');
        return "تم بناء قاعدة البيانات بنجاح وبشكل نظيف! جربي تسجيل الدخول الآن يا فرح.";
    } catch (\Exception $e) {
        return "حدث خطأ أثناء بناء القاعدة: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
