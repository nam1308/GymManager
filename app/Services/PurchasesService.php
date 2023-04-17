<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;


class PurchasesService
{
    public static function getPurchasesIndex($limit = 30)
    {
        return DB::table('purchases')
            ->leftJoin('users', 'purchases.user_id', '=', 'users.id')
            ->leftJoin('products', 'purchases.product_code', '=', 'products.code')
            ->select(
                'purchases.*',
                'products.name as productName',
                'users.id as userId',
                'users.sei as userSei',
                'users.mei as userMei',
                'users.sei_kana as userSei_kana',
                'users.mei_kana as userMei_kana',
                'users.email as email'

            )
            ->whereNull('purchases.deleted_at')
            ->orderBy('purchases.id','DESC')
            ->paginate($limit);
    }
}
