<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File; // أضفنا هذا السطر للتعامل مع الملفات
use Inertia\Inertia;

// الصفحة الرئيسية
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'goals' => auth()->check()
            ? auth()->user()->goals()->where('is_archived', false)->latest()->take(3)->get()
            : [],
    ]);
});

// مسارات الداشبورد والأهداف - محمية بـ Middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [GoalController::class, 'index'])->name('dashboard');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::put('/goals/{goal}', [GoalController::class, 'update'])->name('goals.update');
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');
    Route::get('/stats', [GoalController::class, 'stats'])->name('stats');
    Route::get('/archive', [GoalController::class, 'archiveIndex'])->name('archive');
    Route::patch('/goals/{goal}/archive', [GoalController::class, 'toggleArchive'])->name('goals.archive');
});

// مسارات الملف الشخصي
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ------------------------------------------------------------------
// مسار الطوارئ المطور لإنشاء ملف SQLite وبناء الجداول
// ------------------------------------------------------------------
Route::get('/init-db', function () {
    try {
        // تحديد مسار ملف قاعدة البيانات
        $dbPath = database_path('database.sqlite');

        // التأكد من وجود مجلد database
        if (!File::isDirectory(database_path())) {
            File::makeDirectory(database_path(), 0755, true);
        }

        // إنشاء الملف إذا لم يكن موجوداً
        if (!File::exists($dbPath)) {
            File::put($dbPath, '');
            chmod($dbPath, 0777); // إعطاء صلاحيات كاملة للملف
        }

        // تنفيذ الميجريشن
        Artisan::call('migrate:fresh --force');

        return "تم إنشاء ملف القاعدة وبناء الجداول بنجاح! جربي التسجيل الآن يا فرح.";
    } catch (\Exception $e) {
        return "حدث خطأ: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
