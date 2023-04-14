<?php

use App\Http\Controllers\Auth\LineLoginController;
use App\Http\Controllers\ApplyController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\VendorTrainerReservationController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth.very_basic'], function () {

    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

//    Route::get('/{callback_id}', [WelcomeController::class, 'show']);
    ///////////////////////
    // ログイン
    Route::get('/login/line', [LineLoginController::class, 'index'])->name('login.line');
    Route::get('/login/line/social-login', [LineLoginController::class, 'socialLogin'])->name('login.line.social-login');
    Route::get('/login/line/callback', [LineLoginController::class, 'handleProviderCallback'])->name('login.line.callback');
    ///////////////////////
    /// 申込
    Route::get('apply', [ApplyController::class, 'index'])->name('apply');
    Route::post('apply/store', [ApplyController::class, 'store'])->name('apply.store');
    Route::get('apply/thanks', [ApplyController::class, 'thanks'])->name('apply.thanks');
    ///////////////////////
    // 招待
    Route::get('invitation/{token}', [InvitationController::class, 'store'])->name('invitation');
    Route::get('invitation/{token}/create', [InvitationController::class, 'create'])->name('invitation.create');
    Route::get('invitation/{token}/store', [InvitationController::class, 'store'])->name('invitation.store');
    ///////////////////////
    // ヘルプ
    Auth::routes();
    Route::get('help', [HelpController::class, 'index'])->name('help');
    ///////////////////////
    /// 予約する
    /// // channel.trainer.reservation.store
    /// // https://dev.chiba.triner.bushibrand.com/vendor/D7dpC307qQqsXhzHIHDK/trainer/1/reservation/store
    Route::get('channel/{vendor_id}/trainer/{trainer_id}/reservation/show', [VendorTrainerReservationController::class, 'show'])->name('channel.trainer.reservation.show');
    Route::get('channel/{vendor_id}/trainer/{trainer_id}/reservation/times', [VendorTrainerReservationController::class, 'times'])->name('channel.trainer.reservation.times');
    Route::post('channel/{vendor_id}/trainer/{trainer_id}/reservation/store', [VendorTrainerReservationController::class, 'store'])->name('channel.trainer.reservation.store');
    Route::get('channel/{vendor_id}/trainer/{trainer_id}/reservation/thanks', [VendorTrainerReservationController::class, 'thanks'])->name('channel.trainer.reservation.thanks');
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('channel', [ChannelController::class, 'index'])->name('channel');
    Route::get('channel/{vendor_id}', [ChannelController::class, 'show'])->name('channel.show');
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('user/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update', [UserController::class, 'update'])->name('user.update');
    ///////////////////////
    /// トレーナー
    Route::get('trainer', [TrainerController::class, 'index'])->name('trainer');
    Route::get('trainer/{trainer_id}/show', [TrainerController::class, 'show'])->name('trainer.show');
    Route::get('channel/{vendor_id}/trainer', [TrainerController::class, 'index'])->name('channel.trainer');
    Route::get('channel/{vendor_id}/trainer/{trainer_id}', [TrainerController::class, 'show'])->name('channel.trainer.show');
    ///////////////////////
    /// メニュー
    Route::get('course', [CourseController::class, 'index'])->name('course');
    Route::get('course/{trainer_id}/show', [CourseController::class, 'show'])->name('course.show');
    ///////////////////////
    /// 予約確認
    Route::get('reservation', [ReservationController::class, 'index'])->name('reservation');
    Route::get('reservation/{trainer_id}/create', [ReservationController::class, 'create'])->name('reservation.create');
    Route::get('reservation/{trainer_id}/times', [ReservationController::class, 'times'])->name('reservation.times');
    Route::post('reservation/{trainer_id}/store', [ReservationController::class, 'store'])->name('reservation.store');
    Route::post('reservation/{reservation_id}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    // 予約確認
    Route::get('reservation/{reservation_id}/show', [ReservationController::class, 'show'])->name('reservation.show');
    Route::get('reservation/{trainer_id}/thanks', [ReservationController::class, 'thanks'])->name('reservation.thanks');
});
