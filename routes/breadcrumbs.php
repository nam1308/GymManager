<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

///////////////////////////////////////////////////
// 会員側
///////////////////////////////////////////////////
Breadcrumbs::for('home', function ($trail) {
    $trail->push('ホーム', route('home'));
});

breadcrumbs::for('channel', function ($trail) {
    $trail->parent('home');
    $trail->push('チャンネル一覧', route('channel'));
});
breadcrumbs::for('channel.trainer', function ($trail) {
    $trail->parent('home');
    $trail->push('チャンネル一覧', route('channel'));
});
breadcrumbs::for('user.edit', function ($trail) {
    $trail->parent('home');
    $trail->push('お客様情報', route('home'));
});
breadcrumbs::for('account.edit', function ($trail) {
    $trail->parent('home');
    $trail->push('カウント情報', route('home'));
});
breadcrumbs::for('reservation', function ($trail) {
    $trail->parent('home');
    $trail->push('予約一覧', route('home'));
});
/**
 * https://dev.chiba.triner.bushibrand.com/reservation/1/create
 */
breadcrumbs::for('reservation.create', function ($trail, $trainer) {
    $trail->parent('home');
    $trail->push('トレーナー', route('trainer'));
    $trail->push($trainer->name, route('trainer.show', $trainer->id));
    $trail->push('予約申請', route('reservation.create', $trainer->id));
});
/**
 * https://dev.chiba.triner.bushibrand.com/reservation/3MO68xUBxK/show
 */
breadcrumbs::for('reservation.show', function ($trail, $reservation) {
    $trail->parent('home');
    $trail->push('予約一覧', route('reservation'));
    $trail->push('詳細', route('reservation.create', $reservation->id));
});

breadcrumbs::for('help', function ($trail) {
    $trail->parent('home');
    $trail->push('ヘルプ', route('help'));
});
breadcrumbs::for('trainer', function ($trail) {
    $trail->parent('home');
    $trail->push('トレーナー', route('trainer'));
});
breadcrumbs::for('trainer.show', function ($trail, $trainer) {
    $trail->parent('home');
    $trail->push('トレーナー', route('channel.trainer', $trainer->vendor_id));
    $trail->push($trainer->name, route('channel.trainer', $trainer->vendor_id));
});


///////////////////////////////////////////////////
// 管理画面
///////////////////////////////////////////////////
Breadcrumbs::for('admin.home', function ($trail) {
    $trail->push('ホーム', route('admin.home'));
});
// プロフィール
Breadcrumbs::for('admin.profile.edit', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('プロフィール設定', route('admin.profile.edit'));
});
// パスワード
Breadcrumbs::for('admin.password.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('パスワード設定', route('admin.password.index'));
});
Breadcrumbs::for('admin.product.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('プラン購入', route('admin.product.index'));
});
Breadcrumbs::for('admin.purchase.show', function ($trail, $product) {
    $trail->parent('admin.home');
    $trail->push('プラン購入', route('admin.purchase.show', $product['name']));
});
Breadcrumbs::for('admin.purchase.succeeded', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('購入完了', route('admin.product.index'));
});


//////////////////////////////////////////
// 店舗
Breadcrumbs::for('admin.shop', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('店舗一覧', route('admin.shop'));
});
Breadcrumbs::for('admin.shop.create', function ($trail) {
    $trail->parent('admin.shop');
    $trail->push('店舗作成', route('admin.shop.create'));
});
Breadcrumbs::for('admin.shop.edit', function ($trail, $shop) {
    $trail->parent('admin.shop');
    $trail->push('店舗編集', route('admin.shop.edit', $shop->id));
});
//////////////////////////////////////////
// メニュー
Breadcrumbs::for('admin.course', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('メニュー一覧', route('admin.course'));
});
Breadcrumbs::for('admin.course.create', function ($trail) {
    $trail->parent('admin.course');
    $trail->push('メニュー登録', route('admin.course.create'));
});
Breadcrumbs::for('admin.course.edit', function ($trail, $course) {
    $trail->parent('admin.course');
    $trail->push('メニュー編集', route('admin.course.edit', $course->id));
});

Breadcrumbs::for('admin.trainer', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('トレーナ一覧', route('admin.trainer'));
});

Breadcrumbs::for('admin.trainer.create', function ($trail) {
    $trail->parent('admin.trainer');
    $trail->push('トレーナー登録', route('admin.trainer.create'));
});

Breadcrumbs::for('admin.trainer.show', function ($trail, $trainer) {
    $trail->parent('admin.trainer');
    $trail->push($trainer->name, route('admin.trainer.show', $trainer->name));
});

Breadcrumbs::for('admin.trainer.edit', function ($trail, $trainer) {
    $trail->parent('admin.trainer');
    $trail->push($trainer->name, route('admin.trainer.show', $trainer->id));
    $trail->push('編集', route('admin.trainer.edit', $trainer->name));
});
///////////////////////////////////////////
// 自動返信
Breadcrumbs::for('admin.notification', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('自動通知管理', route('admin.notification'));
});
// トレーナー招待
Breadcrumbs::for('admin.invitation', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('招待一覧', route('admin.invitation'));
});
// トレーナー招待新規
Breadcrumbs::for('admin.invitation.create', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('招待一覧', route('admin.invitation'));
    $trail->push('招待', route('admin.invitation.create'));
});
// 商品
Breadcrumbs::for('admin.product', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('商品一覧', route('admin.product'));
});
Breadcrumbs::for('admin.product.show', function ($trail, $product) {
    $trail->parent('admin.product');
    if ($product) {
        $trail->push($product->name, route('admin.product.show', $product->name));
    }
});
Breadcrumbs::for('admin.product.create', function ($trail) {
    $trail->parent('admin.product');
    $trail->push('商品登録', route('admin.product.create'));
});
// 製品編集
Breadcrumbs::for('admin.product.edit', function ($trail, $product) {
    $trail->parent('admin.product');
    if ($product) {
        $trail->push($product->name, route('admin.product.show', $product->id));
        $trail->push('編集', route('admin.product.edit', $product->id));
    }
});
//////////////////////////////////////////////////////////
///
/// 会員
Breadcrumbs::for('admin.user', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('会員一覧', route('admin.user'));
});
Breadcrumbs::for('admin.user.create', function ($trail) {
    $trail->parent('admin.user');
    $trail->push('会員登録', route('admin.user.create'));
});
// 会員編集
Breadcrumbs::for('admin.user.edit', function ($trail, $user) {
    $trail->parent('admin.user');
    if ($user) {
        $trail->push(optional($user)->view_full_name, route('admin.user.show', $user->id));
        $trail->push('編集', route('admin.user.edit', $user->id));
    }
});
Breadcrumbs::for('admin.user.show', function ($trail, $channel_join) {
    $trail->parent('admin.user');
    $trail->push($channel_join->user->display_name, route('admin.user.show', $channel_join->user->display_name));
});
Breadcrumbs::for('admin.reservation', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('全体予約', route('admin.reservation'));
});
Breadcrumbs::for('admin.reservation.individual', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('個別予約', route('admin.reservation.individual'));
});
Breadcrumbs::for('admin.reservation.show', function ($trail, $reservation) {
    $trail->parent('admin.home');
    $trail->push('予約一覧', route('admin.reservation'));
    $trail->push('予約番号【' . $reservation->id . '】', route('admin.reservation.show', $reservation->id));
});
//////////////////////////////////////////////////////////
Breadcrumbs::for('admin.basic-setting', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('会社設定', route('admin.basic-setting'));
});
Breadcrumbs::for('admin.basic-setting.edit', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('会社設定', route('admin.basic-setting'));
    $trail->push('会社編集', route('admin.basic-setting.edit'));
});
Breadcrumbs::for('admin.business-hours.edit', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('営業時間', route('admin.business-hours'));
});
//////////////////////////////////////////////////////////
// 税率設定
Breadcrumbs::for('admin.pay', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('決済情報', route('admin.pay'));
});

Breadcrumbs::for('admin.pay.create', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('決済情報', route('admin.pay'));
    $trail->push('決済情報登録', route('admin.pay.create'));
});

Breadcrumbs::for('admin.pay.edit', function ($trail, $accountInfor) {
    $trail->parent('admin.home');
    $trail->push('決済情報', route('admin.pay'));
    $trail->push($accountInfor['name'], route('admin.pay.edit', $accountInfor['pm_id']));
});
//////////////////////////////////////////////////
// 課金情報
Breadcrumbs::for('admin.tax-rate-setting', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('税率設定', route('admin.tax-rate-setting'));
});

//////////////////////////////////////////////////
// 管理画面
///////////////////////////////////////////////////
Breadcrumbs::for('super-admin.home', function ($trail) {
    $trail->push('ホーム', route('super-admin.home'));
});
//////////////////////////////////////////////////////////
// 予約
Breadcrumbs::for('super-admin.reservation', function ($trail) {
    $trail->parent('super-admin.home');
    $trail->push('予約一覧', route('super-admin.reservation'));
});
//////////////////////////////////////////////////////////
// パスワード
Breadcrumbs::for('super-admin.password.index', function ($trail) {
    $trail->parent('super-admin.home');
    $trail->push('パスワード設定', route('super-admin.password.index'));
});
//////////////////////////////////////////////////////////
// 商品
Breadcrumbs::for('super-admin.product', function ($trail) {
    $trail->parent('super-admin.home');
    $trail->push('商品一覧', route('super-admin.product'));
});
Breadcrumbs::for('super-admin.product.show', function ($trail, $product) {
    $trail->parent('super-admin.product');
    if ($product) {
        $trail->push($product->name, route('super-admin.product.show', $product->name));
    }
});
Breadcrumbs::for('super-admin.product.create', function ($trail) {
    $trail->parent('super-admin.product');
    $trail->push('商品登録', route('super-admin.product.create'));
});
// 製品編集
Breadcrumbs::for('super-admin.product.edit', function ($trail, $product) {
    $trail->parent('super-admin.product');
    if ($product) {
        $trail->push($product->name, route('super-admin.product.show', $product->id));
        $trail->push('編集', route('super-admin.product.edit', $product->id));
    }
});
//////////////////////////////////////////////////////////
///
/// 会員
Breadcrumbs::for('super-admin.user', function ($trail) {
    $trail->parent('super-admin.home');
    $trail->push('会員一覧', route('super-admin.user'));
});
Breadcrumbs::for('super-admin.user.create', function ($trail) {
    $trail->parent('super-admin.user');
    $trail->push('会員登録', route('super-admin.user.create'));
});
// 会員編集
Breadcrumbs::for('super-admin.user.edit', function ($trail, $user) {
    $trail->parent('super-admin.user');
    if ($user) {
        $trail->push(optional($user)->view_full_name, route('super-admin.user.show', $user->id));
        $trail->push('編集', route('super-admin.user.edit', $user->id));
    }
});
Breadcrumbs::for('super-admin.user.show', function ($trail, $user) {
    $trail->parent('super-admin.user');
    $trail->push(optional($user)->display_name, route('super-admin.user.show', $user->display_name));
});
//////////////////////////////////////////////////////////
// 税率設定
Breadcrumbs::for('super-admin.tax-rate-setting', function ($trail) {
    $trail->parent('super-admin.home');
    $trail->push('税率設定', route('super-admin.tax-rate-setting'));
});
//////////////////////////////////////////////////////////
// car csv top
//Breadcrumbs::for('admin.car-csv', function ($trail) {
//    $trail->parent('admin.home');
//    $trail->push('CSVアップ', route('admin.car-csv'));
//});
//////////////////////////////////////////////////////////
Breadcrumbs::for('super-admin.apply', function ($trail) {
    $trail->parent('super-admin.home');
    $trail->push('申込一覧', route('super-admin.apply'));
});
Breadcrumbs::for('super-admin.apply.show', function ($trail, $apply) {
    $trail->parent('super-admin.home');
    $trail->push($apply->name, route('super-admin.apply.show', $apply->id));
});
Breadcrumbs::for('super-admin.apply.edit', function ($trail, $apply) {
    $trail->parent('super-admin.home');
    $trail->push($apply->name, route('super-admin.apply.edit', $apply->id));
});
Breadcrumbs::for('super-admin.trainer', function ($trail) {
    $trail->parent('super-admin.home');
    $trail->push('トレーナ一覧', route('super-admin.trainer'));
});
Breadcrumbs::for('super-admin.trainer.show', function ($trail, $trainer) {
    $trail->parent('super-admin.home');
    $trail->push('トレーナ一覧', route('super-admin.trainer'));
    $trail->push($trainer->name, route('super-admin.trainer.show', $trainer->id));
});
Breadcrumbs::for('super-admin.basic-setting.show', function ($trail, $basic_setting) {
    $trail->parent('super-admin.home');
    $trail->push('店舗一覧', route('super-admin.basic-setting'));
    $trail->push($basic_setting->company_name, route('super-admin.basic-setting.show', $basic_setting->vendor_id));
});
Breadcrumbs::for('super-admin.basic-setting', function ($trail) {
    $trail->parent('super-admin.home');
    $trail->push('基本設定', route('super-admin.basic-setting'));
});

Breadcrumbs::for('super-admin.basic-setting.index',function ($trail, $basic_setting){
    $trail->parent('super-admin.basic-setting');
    $trail->push($basic_setting->company_name);
});

//Breadcrumbs::for('super-admin.admin-user', function ($trail) {
//    $trail->parent('super-admin.home');
//    $trail->push('店舗一覧', route('super-admin.admin-user'));
//});
//Breadcrumbs::for('super-admin.admin-user.show', function ($trail, $channel) {
//    $trail->parent('super-admin.admin-user');
//    $trail->push(optional($channel)->company_name, route('super-admin.admin-user.show', $channel->vendor_id));
//});
//Breadcrumbs::for('super-admin.admin-user.user-index', function ($trail, $channel) {
//    $trail->parent('super-admin.admin-user.show', $channel);
//    $trail->push('会員一覧', route('super-admin.admin-user.user-index', $channel->vendor_id));
//});

//////////////////////////////////////////////////////////
// LINE利用申請
Breadcrumbs::for('admin.line-apply.create', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('LINE利用申請', route('admin.line-apply'));
});
Breadcrumbs::for('admin.line-apply.edit', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('LINE利用申請', route('admin.line-apply'));
    $trail->push('編集', route('admin.line-apply.edit'));
});


