<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class LineLogin extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'vendor_id',
        'status',
        'callback',
        'channel_icon',
        'channel_id',
        'channel_name',
        'channel_description',
        'email',
        'privacy_policy_url',
        'terms_of_use_url',
        'channel_secret',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function basicSetting(): HasOne
    {
        return $this->hasOne(BasicSetting::class, 'vendor_id', 'vendor_id');

    }

    /**
     * @return string
     */
    public function getViewStatusAttribute(): string
    {
        if ($this->status == config('const.LINE_STATUS.APPLYING.STATUS')) {
            // 申請中
            return '<span class="badge bg-danger text-white">' . config('const.LINE_STATUS.APPLYING.LABEL') . '</span>';
        } else if ($this->status == config('const.LINE_STATUS.ENTERED.STATUS')) {
            // 入力済
            return '<span class="badge bg-warning text-dark">' . config('const.LINE_STATUS.ENTERED.LABEL') . '</span>';
        } else if ($this->status == config('const.LINE_STATUS.ACCOMPLICE.STATUS')) {
            // 済
            return '<span class="badge bg-warning text-dark">' . config('const.LINE_STATUS.ACCOMPLICE.LABEL') . '</span>';
        }
        return '';
    }

//    public function getViewCompleteStatusAttribute()
//    {
//        if ($this->status == 30) {
//            return '<span class="badge badge-success">対応済</span>';
//        } else {
//            return '<span class="badge bg-danger text-white">未対応</span>';
//        }
//
//    }

    public function getViewCreatedAtAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('Y年m月d日 H時i分s秒');
    }

    /**
     * 更新日
     * @return string
     * @throws InvalidFormatException
     */
    public function getViewUpdatedAtAttribute(): string
    {
        return Carbon::parse($this->updated_at)->format('Y年m月d日 H時i分s秒');
    }

    /**
     * 削除日
     * @return string
     */
    public function getViewDeletedAtAttribute(): string
    {
        if ($this->deleted_at) {
            return Carbon::parse($this->deleted_at)->format('Y年m月d日 H時i分s秒');
        }
        return '';
    }

}
