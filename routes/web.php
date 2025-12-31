<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
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
// مسار الطوارئ النهائي - استخدام المجلد المؤقت /tmp
// ------------------------------------------------------------------
Route::get('/init-db', function () {
    try {
        // نستخدم المجلد المؤقت لأن السيرفر يسمح بالكتابة فيه دائماً
        $dbPath = '/tmp/database.sqlite';

        // إنشاء ملف قاعدة البيانات إذا لم يكن موجوداً
        if (!File::exists($dbPath)) {
            File::put($dbPath, '');
            chmod($dbPath, 0777);
        }

        // إجبار Laravel على استخدام هذا المسار في اللحظة الحالية
        config(['database.connections.sqlite.database' => $dbPath]);

        // تنفيذ الميجريشن
        Artisan::call('migrate:fresh --force');

        return "تمت العملية بنجاح في المجلد المؤقت! جربي التسجيل الآن يا فرح.";
    } catch (\Exception $e) {
        return "حدث خطأ أثناء بناء القاعدة: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
