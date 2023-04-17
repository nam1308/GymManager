<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'vendor_id',        // 業者ID
        'purchase_id',      // 購入ID
        'user_id',          // 会員ID
        'product_code',       // 製品ID
        'introducer',       // 紹介者
        'question_1',       // 本サービスをどこでお知りになりましたか？
        'question_2',       // 何に興味がありますか？
        'other',            // その他
    ];
}
