<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = "id";
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'vendor_id',
        'user_id',
        'admin_id',
        'course_id',
        'store_id',
        'status',
        'category',
        'reservation_start',
        'reservation_end',
        'created_at',
        'updated_at',
    ];

    /**
     * @param $selectedDate
     * @param $admin_id
     * @return array
     */
    public static function getTimes($selectedDate, $admin_id): array
    {
        $times = [];
        // 対象日付
        $cb = Carbon::parse($selectedDate);
        $selectedDate = $cb->year . '-' . sprintf('%02d', $cb->month) . '-' . sprintf('%02d', $cb->day);
        $trainer = Admin::find($admin_id);
        if (!$trainer) {
            return $times;
        }
        $business_hour = BusinessHours::selectRaw('DATE_FORMAT(start, "%H:%i") AS start')
            ->selectRaw('DATE_FORMAT(end, "%H:%i") AS end')
            ->where('vendor_id', $trainer->vendor_id)->first();
        $t = $business_hour ? $business_hour->start : config('const.DEFAULT_BUSINESS_HOURS.START');
        $tn = $business_hour ? $business_hour->end : config('const.DEFAULT_BUSINESS_HOURS.END');
        // 5分ごとの配列
        for ($i = 0; $i <= 15 * 12 * 5; $i += 5) {
            $time = date('H:i', strtotime("+{$i} minutes", strtotime($t)));
            if ($time == date('H:i', strtotime($tn))) {
                break;
            }
            $times[] = $time;
        }
        // スタート
        $start = Carbon::parse($selectedDate)->startOfDay();
        // 終了
        $end = Carbon::parse($selectedDate)->endOfDay();
        // 予約取得
        $reservations = Reservation::reserved()->where('admin_id', $admin_id)->reserved()->whereBetween('reservation_start', [$start, $end])->get();
        Log::debug($reservations);
        if ($reservations) {
            foreach ($reservations as $reservation) {
                foreach ($times as $k2 => $val) {
                    // 2021-07-16 09:00:00,2021-07-16 09:15:00など
//                    $time = $request->selectedDate . $val;
//                    Log::debug(json_encode($time));
                    $select_date = new Carbon($selectedDate . ' ' . $val);
                    if ($select_date >= $reservation->reservation_start && $select_date < $reservation->reservation_end) {
                        Log::debug('消す対象:' . $select_date);
                        unset($times[$k2]);
                    }
//                    if ($select_date->between($reservation->reservation_start, $reservation->reservation_end)) {
//                        Log::debug('消す対象:' . $select_date);
//                        unset($times[$k2]);
//                    }
                }
            }
        }
        return array_values($times);
    }

    /**
     * @param string $vendor_id
     * @param int $admin_id
     * @return array
     */
    static public function getCloses(string $vendor_id, int $admin_id): array
    {
        $closes = [];
        $close_data = Reservation::select('reservation_start')
            ->where('vendor_id', $vendor_id)
            ->where('admin_id', $admin_id)
            ->where('status', config('const.RESERVATION_STATUS.CLOSE.STATUS'))
            ->get();
        if ($close_data) {
            foreach ($close_data as $close) {
                $closes[] = Carbon::parse($close->reservation_start)->format('Y-m-d');
            }
        }
        return $closes;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course(): HasOne
    {
        return $this->hasOne(Course::class, 'id', 'course_id')->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function basicSetting(): HasOne
    {
        return $this->hasOne(BasicSetting::class, 'vendor_id', 'vendor_id');
    }

    /**
     * トレーナー権限
     * @return string
     */
    public function getViewStatusAttribute(): string
    {
        if ($this->status) {
            foreach (config('const.RESERVATION_STATUS') as $val) {
                if ($val['STATUS'] == $this->status) {
                    return $val['LABEL'];
                }
            }
        }
        return $this->status;
    }

    public function scopeReserved($query)
    {
        return $query->selectRaw('
        id, 
        vendor_id, 
        user_id, 
        admin_id, 
        status, 
        category, 
        course_id, 
        shop_id, 
        created_at, 
        updated_at, 
        reservation_start,
        reservation_end, 
        DATE_FORMAT(`reservation_start`, "%Y-%m-%d %H:%i") as start,
        DATE_FORMAT(`reservation_start`, "%Y-%m-%d") AS `start_date`, 
        DATE_FORMAT(`reservation_start`, "%H:%i") AS `start_time`,
        DATE_FORMAT(`reservation_end`, "%Y-%m-%d %H:%i") as end,
        DATE_FORMAT(`reservation_end`, "%Y-%m-%d") AS `end_date`, 
        DATE_FORMAT(`reservation_end`, "%H:%i") AS `end_time`
        ');
    }
}
