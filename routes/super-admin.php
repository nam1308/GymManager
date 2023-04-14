<?php

use App\Http\Controllers\SuperAdmin\AdminUserController;
use App\Http\Controllers\SuperAdmin\ApplyController;
use App\Http\Controllers\SuperAdmin\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\BasicSettingController;
use App\Http\Controllers\SuperAdmin\HomeController;
use App\Http\Controllers\SuperAdmin\LineApplyController;
use App\Http\Controllers\SuperAdmin\ReservationController;
use App\Http\Controllers\SuperAdmin\ShopController;
use App\Http\Controllers\SuperAdmin\TrainerController;
use App\Http\Controllers\SuperAdmin\UserController;
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

    Route::get('/', [WelcomeController::class, 'index']);

    Auth::routes();
    /*************************************
     *
     * 管理画面ログイン前
     *
     *************************************/
    Route::group(['prefix' => 'super-admin', 'middleware' => 'guest:super-admin'], function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('super-admin.showLoginForm');
        Route::post('login', [LoginController::class, 'login'])->name('super-admin.login');
    });
    /*************************************
     *
     * 管理画面ログイン後
     *
     *************************************/
    Route::group(['prefix' => 'super-admin', 'middleware' => 'auth:super-admin', 'namespace' => 'SuperAdmin'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('super-admin.home');
        Route::post('logout', [LoginController::class, 'logout'])->name('super-admin.logout');
        ////////////////////////////////////
        // ベンターsuper-admin.trainer.export
        Route::get('reservation', [ReservationController::class, 'index'])->name('super-admin.reservation');
        Route::post('reservation/export', [ReservationController::class, 'export'])->name('super-admin.reservation.export');
        Route::get('reservation/{reservation_id}', [ReservationController::class, 'show'])->name('super-admin.reservation.show');
        ////////////////////////////////////
        // ベンター
        Route::get('basic-setting', [BasicSettingController::class, 'index'])->name('super-admin.basic-setting');
        Route::get('basic-setting/create', [BasicSettingController::class, 'create'])->name('super-admin.basic-setting.create');
        Route::get('basic-setting/{vendor_id}/show', [BasicSettingController::class, 'show'])->name('super-admin.basic-setting.show');
        Route::post('basic-setting/store', [BasicSettingController::class, 'store'])->name('super-admin.basic-setting.store');
        ////////////////////////////////////
        // 店舗
        Route::group(['prefix' => 'shop'], function () {
            Route::get('{shop_id}', [ShopController::class, 'show'])->name('super-admin.shop.show');
            Route::delete('{shop_id}/destroy', [ShopController::class, 'destroy'])->name('super-admin.shop.destroy');
        });
        ////////////////////////////////////
        // トレーナー
        Route::get('trainer', [TrainerController::class, 'index'])->name('super-admin.trainer');
        Route::post('trainer/export', [TrainerController::class, 'export'])->name('super-admin.trainer.export');
        Route::get('trainer/{trainer_id}/show', [TrainerController::class, 'show'])->name('super-admin.trainer.show');
        Route::get('trainer/{trainer_id}/edit', [TrainerController::class, 'edit'])->name('super-admin.trainer.edit');
        Route::put('trainer/{trainer_id}/update', [TrainerController::class, 'update'])->name('super-admin.trainer.update');
        Route::post('trainer/{trainer_id}/login', [TrainerController::class, 'login'])->name('super-admin.trainer.login');
        Route::post('trainer/{trainer_id}/destroy', [TrainerController::class, 'destroy'])->name('super-admin.trainer.destroy');

        ////////////////////////////////////
        // 会員
        // 一覧
        Route::post('user/export', [UserController::class, 'export'])->name('super-admin.user.export');
        Route::get('user', [UserController::class, 'index'])->name('super-admin.user');
        // 削除
        Route::get('user/delete', 'UserController@indexDelete')->name('super-admin.user.index-delete');
        // 登録
        Route::get('user/create', [UserController::class, 'create'])->name('super-admin.user.create');
        // 詳細
        Route::get('user/{user_id}', [UserController::class, 'show'])->name('super-admin.user.show');
        // 更新
        Route::put('user/{user_id}', [UserController::class, 'update'])->name('super-admin.user.update');
        // 登録処理
        Route::post('user', [UserController::class, 'store'])->name('super-admin.user.store');
        // 編集
        Route::get('user/{user_id}/edit', [UserController::class, 'edit'])->name('super-admin.user.edit');
        Route::delete('user/{user_id}', [UserController::class, 'destroy'])->name('super-admin.user.destroy');
        /////////////////////////
        ///
        /// 個別にパスワードを変更する
        Route::get('/password/change', 'Auth\ChangePasswordController@showChangePasswordForm')->name('super-admin.password.index');
        Route::post('/password/change', 'Auth\ChangePasswordController@ChangePassword')->name('super-admin.password.change');
        ////////////////
        ///
        /// お申込み
        Route::get('apply', [ApplyController::class, 'index'])->name('super-admin.apply');
        Route::get('apply/{id}/show', [ApplyController::class, 'show'])->name('super-admin.apply.show');
        Route::get('apply/{id}/edit', [ApplyController::class, 'edit'])->name('super-admin.apply.edit');
        Route::put('apply/{id}/update', [ApplyController::class, 'update'])->name('super-admin.apply.update');
        Route::delete('apply/{id}/delete', [ApplyController::class, 'destroy'])->name('super-admin.apply.destroy');
        ////////////////
        ///
        /// 管理者管理
        Route::get('admin-user', [AdminUserController::class, 'index'])->name('super-admin.admin-user');
        Route::get('admin-user/{vendor_id}/user-index', [AdminUserController::class, 'user_index'])->name('super-admin.admin-user.user-index');
        Route::get('admin-user/{id}/show', [AdminUserController::class, 'show'])->name('super-admin.admin-user.show');
        Route::get('admin-user/create', [AdminUserController::class, 'create'])->name('super-admin.admin-user.create');
        Route::post('admin-user', [AdminUserController::class, 'store'])->name('super-admin.admin-user.store');
        Route::delete('admin-user/{id}/delete', [AdminUserController::class, 'destroy'])->name('super-admin.admin-user.destroy');
        ////////////////
        ///
        /// LINE申込管理
        Route::get('line-apply', [LineApplyController::class, 'index'])->name('super-admin.line-apply');
        Route::get('line-apply/{vendor_id}', [LineApplyController::class, 'show'])->name('super-admin.line-apply.show');
        Route::get('line-apply/{vendor_id}/edit-line-login', [LineApplyController::class, 'edit_line_login'])->name('super-admin.line-apply.login-edit');
        Route::get('line-apply/{vendor_id}/edit-line-message', [LineApplyController::class, 'edit_line_message'])->name('super-admin.line-apply.message-edit');
        Route::put('line-apply/{vendor_id}/line-login-update', [LineApplyController::class, 'line_login_update'])->name('super-admin.line-apply.login-update');
        Route::put('line-apply/{vendor_id}/line-message-update', [LineApplyController::class, 'line_message_update'])->name('super-admin.line-apply.message-update');
        Route::put('line-apply/{vendor_id}/line-inform', [LineApplyController::class, 'line_inform'])->name('super-admin.line-apply.line-inform');
        Route::put('line-apply/status-change', [LineApplyController::class, 'statusChange'])->name('super-admin.line-apply.status-change');
    });
});
