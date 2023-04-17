<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @method static orderBy(string $string, string $string1)
 */
class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['reference_date'];

    public static array $products = [
        'price_1Mq4d5BwPV1VfiXGzsOnZJFz' => [
            'id' => 'price_1Mq4d5BwPV1VfiXGzsOnZJFz',
            'name' => 'パーソナルプラン',
            'price' => '1480',
            'description' => '月額1480円<br>
                利用者数1名<br>
                <ul>
                  <li>予約機能</li>
                  <li>店舗管理</li>
                  <li>メニュー管理</li>
                  <li>ライン予約機能</li>
                </ul>',
            'type' => 'small',
            'coupons' => [
                'XH8xcet7' => [
                    'id' => 'XH8xcet7',
                    'api_id' => 'promo_1M100oJA7zcxKPztVhCKaYs8',
                ],
            ],
            'trainer_count' => 1,
            'or_more_less_than' => '==',
        ],
        'price_1Mq4eNBwPV1VfiXGgkQMpSew' => [
            'id' => 'price_1Mq4eNBwPV1VfiXGgkQMpSew',
            'name' => 'ビジネスプラン',
            'price' => '2980',
            'description' => '月額2980円<br>
利用者数5名<br>
<ul>
  <li>予約機能</li>
  <li>店舗管理</li>
  <li>メニュー管理</li>
  <li>ライン予約機能</li>
  <li>トレーナー予約機能</li>
</ul>',
            'type' => 'medium',
            'coupons' => [
                'lPd9HCb3' => [
                    'id' => 'lPd9HCb3',
                    'api_id' => 'promo_1M12k8JA7zcxKPztF09QyLwm',
                ],
            ],
            'trainer_count' => 5,
            'or_more_less_than' => '>=',
        ],
        'price_1Mq4ezBwPV1VfiXGdJwqbEEy' => [
            'id' => 'price_1Mq4ezBwPV1VfiXGdJwqbEEy',
            'name' => 'エンタープライスプラン',
            'price' => '6980',
            'description' => '月額6980円<br>
利用者数6名以上<br>
<ul>
  <li>予約機能</li>
  <li>店舗管理</li>
  <li>メニュー管理</li>
  <li>ライン予約機能</li>
  <li>トレーナー予約機能</li>
</ul>',
            'type' => 'large',
            'coupons' => [
                'lPd9HCb3' => [
                    'id' => 'lPd9HCb3',
                    'api_id' => 'promo_1M12k8JA7zcxKPztF09QyLwm',
                ],
            ],
            'trainer_count' => 1000,
            'or_more_less_than' => '<=',
        ],
    ];

    public function currentPlan()
    {
        $admin = Auth::guard('admin')->user();
        $subscription_item = $admin->subscription()->items->first();
        if ($subscription_item) {
            return Product::$products[$subscription_item->stripe_price];
        }
        return false;
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }
}
