<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Expression;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method static where(Expression $raw, string $value)
 * @method static orderBy(string $string, string $string1)
 * @method static find($userId)
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'display_name',
        'name',
        'name_kana',
        'picture_url',
        'phone_number',
        'email',
        'birthday',
        'gender_id',
        'birthday_search',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 誕生日作成
     * @param $year
     * @param $month
     * @param $day
     * @return string
     */
    public static function createBirthdayFormat($year, $month, $day): string
    {
        return Carbon::parse($year . '-' . $month . '-' . $day)->format('Y-m-d');
    }

    /**
     * 電話番号
     * @param mixed $phone1
     * @param mixed $phone2
     * @param mixed $phone3
     * @return string
     */
    public static function createPhoneNumberFormat($phone1, $phone2, $phone3): string
    {
        return $phone1 . '-' . $phone2 . '-' . $phone3;
    }

    /**
     * 都道府県を取得する
     * @return array
     */
    public function getPrefectureAttribute()
    {
        return prefectures($this->prefecture_id);
    }

    /**
     * 住所取得
     * @return string
     */
    public function getViewAddressAttribute(): string
    {
        $prefectures = prefectures(); // config('const.PREFECTURES');
        return "{$prefectures[$this->prefecture_id]}{$this->municipality}{$this->address_building_name}";
    }

    /**
     * 登録日
     * @return string
     * @throws InvalidFormatException
     */
    public function getViewCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y年m月d日 H時i分s秒');
    }

    /**
     * 更新日
     * @return string
     * @throws InvalidFormatException
     */
    public function getViewUpdatedAtAttribute()
    {
        return Carbon::parse($this->updated_at)->format('Y年m月d日 H時i分s秒');
    }

    /**
     * 削除日
     * @return string|false
     * @throws InvalidFormatException
     */
    public function getViewDeletedAtAttribute()
    {
        if ($this->deleted_at) {
            return Carbon::parse($this->deleted_at)->format('Y年m月d日 H時i分s秒');
        }
        return false;
    }

    /**
     * お問い合わせ
     * @return HasMany
     */
    public function contactUs(): HasMany
    {
        return $this->hasMany(ContactUs::class);
    }

    public function getViewDisplayNameAndNameAttribute()
    {
        if (!is_null($this->name)) {
            // return $this->display_name . '（' . $this->name . '）';
            return $this->display_name;
        }
        return $this->name;
    }

    public function channelJoin(): HasMany
    {
        return $this->hasMany(ChannelJoin::class, 'user_id', 'id');
    }
}
