<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if(!function_exists('paymentData')){
    function paymentData(){
        $admin = Auth::guard('admin')->user();
        if($admin->stripe_id){
            $stripe = new \Stripe\StripeClient( config('app.stripe.st_key') );
            $dataCustomer = $stripe->customers->retrieve( $admin->stripe_id );
            $paymethod = $dataCustomer->invoice_settings->default_payment_method;
            $dataPaymethod = $stripe->customers->retrievePaymentMethod( $admin->stripe_id, $paymethod );
            $accountInfor = array(
                'cus_id' => $admin->stripe_id,
                'pm_id' => $paymethod,
                "name" => $dataPaymethod->billing_details->name,
                "month" => $dataPaymethod->card->exp_month > 10 ? $dataPaymethod->card->exp_month : '0'.$dataPaymethod->card->exp_month,
                "year" => (string)$dataPaymethod->card->exp_year,
                "last4" => $dataPaymethod->card->last4,
            );
            return $accountInfor;
        }else
        {
            return $accountInfor = [];
        }
    }
}

if(!function_exists('DataPay')){
    function DataPay($pm_id){
        $admin = Auth::guard('admin')->user();
        if($admin->stripe_id){
            $stripe = new \Stripe\StripeClient( config('app.stripe.st_key') );
            $dataPaymethod = $stripe->paymentMethods->retrieve( $pm_id );
            $accountInfor = array(
                'cus_id' => $admin->stripe_id,
                'pm_id' => $pm_id,
                "name" => $dataPaymethod->billing_details->name,
                "month" => $dataPaymethod->card->exp_month > 10 ? $dataPaymethod->card->exp_month : '0'.$dataPaymethod->card->exp_month,
                "year" => (string)$dataPaymethod->card->exp_year,
                "last4" => $dataPaymethod->card->last4,
            );
            return $accountInfor;
        }else{
            return $accountInfor = [];
        }
    }
}

if (!function_exists('checkPlan')) {
    function checkPlan(){
        $admin = Auth::guard('admin')->user();
        $subscription_item = $admin->subscription('default')->items->first();
        $current_product = Product::$products[$subscription_item->stripe_price];

        try {
            $products = Product::$products;
            usort($products, function ($a, $b) {
                return intval($a['price']) > intval($b['price']);
            });
        } catch (\Exception $e) {
            throw $e;
        }

        $before_index = array_keys($products, $current_product)[0] - 1;
        $before_product = $products[$before_index];

        return $before_product;
    }
}

if (!function_exists('get_course_times')) {
    /**
     * @throws \Exception
     */
    function get_course_times(): array
    {
        $data = [];
        $minutes = 60;
        for ($i = 1; $i <= 24; $i += 1) {
            $data[$i]['time'] = $i;
            $data[$i]['minutes'] = $minutes;
            $minutes += 60;
        }
        return $data;
    }
}

/**
 * 予約ステータス
 */
if (!function_exists('reservation_status')) {
    /**
     * @throws \Exception
     */
    function reservation_status(int $status)
    {
        if ($status) {
            foreach (config('const.RESERVATION_STATUS') as $val) {
                if ($val['STATUS'] == $status) {
                    return $val;
                }
            }
        }
        return $status;
    }
}

if (!function_exists('admin_role')) {
    function admin_role(int $role)
    {
        if ($role) {
            foreach (config('const.ADMIN_ROLE') as $val) {
                if ($val['STATUS'] == $role) {
                    return $val;
                }
            }
        }
        return $role;
    }
}

if (!function_exists('trainer_role')) {
    function trainer_role(int $role)
    {
        if ($role) {
            foreach (config('const.TRAINER_ROLE') as $val) {
                if ($val['STATUS'] == $role) {
                    return $val;
                }
            }
        }
        return $role;
    }
}

/**
 * ログインIDを発行する
 */
if (!function_exists('create_login_id')) {
    /**
     * @throws \Exception
     */
    function create_login_id($length = 8): string
    {
        $max = pow(10, $length) - 1;
        $rand = random_int(0, $max);
        $admin = Admin::where('login_id', $rand)->first();
        if ($admin) {
            $max = pow(10, $length) - 1;
            $rand = random_int(0, $max);
        }
        return sprintf('%0' . $length . 'd', $rand);
    }
}

if (!function_exists('years')) {
    function years($start = 1900): array
    {
        $data = [];
        $cb = Carbon::now();
        for ($i = $start; $i <= $cb->year; $i++) {
            $data[$i] = $i;
        }
        return $data;
    }
}

if (!function_exists('months')) {
    function months($start = 1, $end = 12): array
    {
        $data = [];
        for ($i = $start; $i <= $end; $i++) {
            $data[$i] = $i;
        }
        return $data;
    }
}

if (!function_exists('days')) {
    function days($start = 1, $end = 31): array
    {
        $data = [];
        for ($i = $start; $i <= $end; $i++) {
            $data[$i] = $i;
        }
        return $data;
    }
}

if (!function_exists('times')) {
    function times($id = null): array
    {
        $data = [];
        for ($i = 1; $i <= 24; $i++) {
            $data[$i] = sprintf('%02d', $i);
        }
        return $data;
    }
}

if (!function_exists('minutes')) {
    function minutes($id = null): array
    {
        $data = [];
        for ($i = 0; $i < 5 * 12; $i += 5) {
            $data[] = sprintf('%02d', $i);
        }
        return $data;
    }
}

/**
 * 性別
 */
if (!function_exists('genders')) {
    function genders($id = null)
    {
        $data[1] = '男性';
        $data[2] = '女性';
        if (is_null($id)) {
            return $data;
        }
        return $data[$id];
    }
}

if (!function_exists('car_model_years')) {
    function car_model_years(): array
    {
        $years = [];
        $carbon = new \Illuminate\Support\Carbon();
        $years[] = $carbon->year;
        for ($i = $carbon->year; $i > 1950; $i--) {
            $years[] = $carbon->subYears(1)->year;
        }
        return $years;
    }
}

if (!function_exists('prefectures')) {
    function prefectures($id = null)
    {
        $prefectures = [
            1 => '北海道',
            2 => '青森県',
            3 => '岩手県',
            4 => '宮城県',
            5 => '秋田県',
            6 => '山形県',
            7 => '福島県',
            8 => '茨城県',
            9 => '栃木県',
            10 => '群馬県',
            11 => '埼玉県',
            12 => '千葉県',
            13 => '東京都',
            14 => '神奈川県',
            15 => '新潟県',
            16 => '富山県',
            17 => '石川県',
            18 => '福井県',
            19 => '山梨県',
            20 => '長野県',
            21 => '岐阜県',
            22 => '静岡県',
            23 => '愛知県',
            24 => '三重県',
            25 => '滋賀県',
            26 => '京都府',
            27 => '大阪府',
            28 => '兵庫県',
            29 => '奈良県',
            30 => '和歌山県',
            31 => '鳥取県',
            32 => '島根県',
            33 => '岡山県',
            34 => '広島県',
            35 => '山口県',
            36 => '徳島県',
            37 => '香川県',
            38 => '愛媛県',
            39 => '高知県',
            40 => '福岡県',
            41 => '佐賀県',
            42 => '長崎県',
            43 => '熊本県',
            44 => '大分県',
            45 => '宮崎県',
            46 => '鹿児島県',
            47 => '沖縄県'
        ];
        if (is_null($id)) {
            return $prefectures;
        }
        return $prefectures[$id];
    }

    /**
     * 指定されたファイルの更新時刻を取得する関数だ。
     */
    if (!function_exists('optimize_uri')) {
        function optimize_uri($resource_absolute_path = null)
        {
            $doc_root = $_SERVER['DOCUMENT_ROOT'];
            if (!empty($resource_absolute_path) && file_exists($doc_root . $resource_absolute_path)) {
                $resource_absolute_path .= '?' . filemtime($doc_root . $resource_absolute_path);
            }
            return $resource_absolute_path;
        }
    }

    if (!function_exists('get_query')) {
        function get_query($url, $fullUrl = null)
        {
            return str_replace($url, '', $fullUrl);
        }
    }
}
