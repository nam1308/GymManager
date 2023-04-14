<?php

use App\Http\Controllers\Admin\Auth\ChangePasswordController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BasicSettingController;
use App\Http\Controllers\Admin\BusinessHoursController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\LineApplyController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserMemoController;
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
    /*************************************
     *
     * 管理画面ログイン前
     *
     *************************************/
    Route::group(['prefix' => 'admin'], function () {
        Route::get('register/{token}', [RegisterController::class, 'showRegistrationForm'])->name('admin.register.showForm');
        Route::post('register/{token}/store', [RegisterController::class, 'store'])->name('admin.register.store');
        Route::get('register/{token}/thanks', [RegisterController::class, 'thanks'])->name('admin.register.thanks');
        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.forgot-password');
        Route::post('password/update', [ForgotPasswordController::class, 'update'])->name('admin.forgot-password.update');
        Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('admin.forgot-password.token');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.forgot-password.email');
    });
    Route::group(['prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
        Route::get('/', [WelcomeController::class, 'index']);
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.showLoginForm');
        Route::post('login', [LoginController::class, 'login'])->name('admin.login');
        Route::post('trainer/store', [TrainerController::class, 'store'])->name('admin.trainer.store');
    });
    Auth::routes();
    /*************************************
     *
     * 管理画面ログイン後
     *
     *************************************/
    Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin', 'namespace' => 'Admin'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('admin.home');
        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');
        /////////////////////////
        /// 基本設定
        Route::get('basic-setting', [BasicSettingController::class, 'index'])->name('admin.basic-setting');
        Route::get('basic-setting/edit', [BasicSettingController::class, 'edit'])->name('admin.basic-setting.edit');
        Route::put('basic-setting', [BasicSettingController::class, 'update'])->name('admin.basic-setting.update');
        // 営業時間
        Route::get('business-hours', [BusinessHoursController::class, 'index'])->name('admin.business-hours');
        Route::get('business-hours/edit', [BusinessHoursController::class, 'edit'])->name('admin.business-hours.edit');
        Route::put('business-hours/update', [BusinessHoursController::class, 'update'])->name('admin.business-hours.update');
        /////////////////////////
        /// プロフィール設定
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');
        ////////////////////////////////////
        // トレーナー
        Route::get('trainer', [TrainerController::class, 'index'])->name('admin.trainer');
        Route::get('trainer/create', [TrainerController::class, 'create'])->name('admin.trainer.create');
        Route::post('trainer/change-trainer-role', [TrainerController::class, 'changeTrainerRole'])->name('admin.trainer.change-trainer-role');
        Route::get('trainer/{id}', [TrainerController::class, 'show'])->name('admin.trainer.show');
        Route::get('trainer/{id}/edit', [TrainerController::class, 'edit'])->name('admin.trainer.edit');
        Route::put('trainer/{id}', [TrainerController::class, 'update'])->name('admin.trainer.update');
        Route::delete('trainer/{id}', [TrainerController::class, 'destroy'])->name('admin.trainer.delete');
        ////////////////////////////////////
        // 予約
        Route::get('reservation', [ReservationController::class, 'index'])->name('admin.reservation');
        Route::get('reservation/individual', [ReservationController::class, 'individual'])->name('admin.reservation.individual');
        Route::get('reservation/setEvent', [ReservationController::class, 'setEvent'])->name('admin.reservation.setEvent');
        Route::get('reservation/setAllEvent', [ReservationController::class, 'setAllEvent'])->name('admin.reservation.setAllEvent');
        Route::get('reservation/getEvent', [ReservationController::class, 'getEvent'])->name('admin.reservation.getEvent');
        Route::get('reservation/getViewEvent', [ReservationController::class, 'getViewEvent'])->name('admin.reservation.getViewEvent');
        Route::post('reservation/change-status', [ReservationController::class, 'changeStatus'])->name('admin.reservation.change-status');
        Route::post('reservation/store', [ReservationController::class, 'store'])->name('admin.reservation.store');
        Route::post('reservation/close', [ReservationController::class, 'close'])->name('admin.reservation.close');
        Route::post('reservation/update', [ReservationController::class, 'update'])->name('admin.reservation.update');
        Route::post('reservation/storeRest', [ReservationController::class, 'storeRest'])->name('admin.reservation.storeRest');
        Route::post('reservation/destroy', [ReservationController::class, 'destroy'])->name('admin.reservation.destroy');
        Route::get('reservation/{reservation_id}', [ReservationController::class, 'show'])->name('admin.reservation.show');
        Route::get('reservation/{trainer_id}/times', [ReservationController::class, 'times'])->name('admin.reservation.times');
        ////////////////////////////////////
        // お店
        Route::get('shop', [ShopController::class, 'index'])->name('admin.shop');
        Route::get('shop/{shop_id}/show', [ShopController::class, 'show'])->name('admin.shop.show');
        Route::get('shop/create', [ShopController::class, 'create'])->name('admin.shop.create');
        Route::post('shop/store', [ShopController::class, 'store'])->name('admin.shop.store');
        Route::get('shop/{shop_id}/edit', [ShopController::class, 'edit'])->name('admin.shop.edit');
        Route::put('shop/{shop_id}/update', [ShopController::class, 'update'])->name('admin.shop.update');
        Route::delete('shop/{shop_id}', [ShopController::class, 'destroy'])->name('admin.shop.delete');
        ////////////////////////////////////
        // メニュー
        Route::get('course', [CourseController::class, 'index'])->name('admin.course');
        Route::get('course/{shop_id}/show', [CourseController::class, 'show'])->name('admin.course.show');
        Route::get('course/create', [CourseController::class, 'create'])->name('admin.course.create');
        Route::post('course/store', [CourseController::class, 'store'])->name('admin.course.store');
        Route::get('course/{shop_id}/edit', [CourseController::class, 'edit'])->name('admin.course.edit');
        Route::put('course/{shop_id}/update', [CourseController::class, 'update'])->name('admin.course.update');
        Route::delete('course/{shop_id}', [CourseController::class, 'destroy'])->name('admin.course.delete');
        ////////////////////////////////////
        // 招待
        Route::get('invitation', [InvitationController::class, 'index'])->name('admin.invitation');
        Route::get('invitation/create', [InvitationController::class, 'create'])->name('admin.invitation.create');
        Route::post('invitation/store', [InvitationController::class, 'store'])->name('admin.invitation.store');
        Route::post('invitation/retransmission', [InvitationController::class, 'retransmission'])->name('admin.invitation.retransmission');
        ////////////////////////////////////
        // 会員
        Route::get('user', [UserController::class, 'index'])->name('admin.user');
        Route::get('user/delete', 'UserController@indexDelete')->name('admin.user.index-delete');
        Route::get('user/create', [UserController::class, 'create'])->name('admin.user.create');
        Route::get('user/{user_id}', [UserController::class, 'show'])->name('admin.user.show');
        Route::put('user/{user_id}', [UserController::class, 'update'])->name('admin.user.update');
        Route::get('user/{user_id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::post('user', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('user/{user_id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::delete('user/{user_id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
        ////////////////////////////////////
        // 会員メモ
        Route::get('user/memo', [UserMemoController::class, 'index'])->name('admin.user.memo');
        Route::post('user/{user_id}/memo/update', [UserMemoController::class, 'update'])->name('admin.user.memo.update');
        /////////////////////////
        /// LINE申請
        Route::get('line-apply', [LineApplyController::class, 'index'])->name('admin.line-apply');
        Route::get('line-apply/create', [LineApplyController::class, 'create'])->name('admin.line-apply.create');
        Route::get('line-apply/edit', [LineApplyController::class, 'edit'])->name('admin.line-apply.edit');
        Route::post('line-apply/store', [LineApplyController::class, 'store'])->name('admin.line-apply.store');
        Route::post('line-apply/update', [LineApplyController::class, 'update'])->name('admin.line-apply.update');
        /////////////////////////
        /// 個別にパスワードを変更する
        Route::get('/password/change', [ChangePasswordController::class, 'showChangePasswordForm'])->name('admin.password.index');
        Route::post('/password/change', [ChangePasswordController::class, 'ChangePassword'])->name('admin.password.change');
        /////////////////////////
        /// 通知
        Route::get('/notification', [NotificationController::class, 'index'])->name('admin.notification');
        Route::post('/notification/birthday', [NotificationController::class, 'update'])->name('admin.notification.birthday.update');
    });
});
