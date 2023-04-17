<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;

class LineMessage extends Model
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
        'channel_id',
        'channel_icon',
        'channel_name',
        'channel_description',
        'email',
        'privacy_policy_url',
        'terms_of_use_url',
        'store_url',
        'channel_secret',
        'channel_access_token',
        'line_uri1',
    ];

//    protected static function boot()
//    {
//        parent::boot();
//        static::addGlobalScope(new VendorIdScope());
//    }


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
        return '<span class="badge bg-warning text-dark">' . config('const.LINE_STATUS.ACCOMPLICE.UNSET') . '</span>';
    }

    /**
     * @return string
     */
    public function getPhotoUrl(): string
    {
        return url(optimize_uri($this->imagePathExists()));
    }

    /**
     * チャンネル画像パスを取得する
     * @return string
     */
    public function imagePathExists(): string
    {
        $path = '/storage/assets/images/admin/' . $this->vendor_id . '/line-icon/' . $this->channel_icon;
        if (File::exists(public_path($path))) {
            return $path;
        }
        return config('const.CHANNEL_ICON');// '/storage/assets/images/channel_icon.png';
    }

    /**
     * @return string
     */
    public function getQrCodeUrl(): string
    {
        return url(optimize_uri($this->qrCodeImagePathExists()));
    }

    /**
     * @return string
     */
    public function qrCodeImagePathExists(): string
    {
        $path = '/storage/assets/images/admin/' . $this->vendor_id . '/qr-code/' . $this->qr_code;
        if (File::exists(public_path($path))) {
            return $path;
        }
        return config('const.CHANNEL_ICON');// '/storage/assets/images/channel_icon.png';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function basicSetting(): HasOne
    {
        return $this->hasOne(BasicSetting::class, 'vendor_id', 'vendor_id');
    }
}
