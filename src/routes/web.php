<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeAttendanceController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//登録とログインとログアウトの処理
Route::get('register', [RegisterUserController::class, 'create'])->name('register');
Route::post('register', [RegisterUserController::class, 'store'])->name('register.store');

Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
// メール認証ルート
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    //ユーザーを認証してログインさせる
    Auth::login($request->user());

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証リンクを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware('auth')->group(function () {
// ホームページのルート
Route::get('/', [AttendanceController::class, 'index'])->name('HOME');

// 勤怠のレコード記録
Route::post('/start-work', [AttendanceController::class, 'startWork'])->name('work.start');
Route::post('/end-work', [AttendanceController::class, 'endWork'])->name('work.end');
Route::post('/start-break', [AttendanceController::class, 'startBreak'])->name('break.start');
Route::post('/end-break', [AttendanceController::class, 'endBreak'])->name('break.end');

// 日付別勤怠情報のルート
Route::get('/attendance', [AttendanceController::class,'showAttendanceIndex'])->name('attendance.index');
Route::get('/attendance/{date}', [AttendanceController::class, 'show'])->name('attendance.show');

// 社員一覧のルート
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
// ユーザーごとの勤怠表のルート
Route::get('/employee/{userId}/attendance/{date}', [EmployeeAttendanceController::class, 'show'])->name('employee.attendance.show');

});

//breezeのデフォルトの認証ルートを無効化
//require __DIR__.'/auth.php';
