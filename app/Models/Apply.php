<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apply extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'company_name',
        'postal_code',
        'prefecture_id',
        'municipality',
        'address_building_name',
        'phone_number',
        'name',
        'email',
        'status',
        'status_updated_at',
    ];

    /**
     * 住所取得
     * @return string
     */
    public function getViewAddressAttribute(): string
    {
        return prefectures($this->prefecture_id) . $this->municipality . $this->address_building_name;
    }

    /**
     * 登録日
     * @return string
     * @throws InvalidFormatException
     */
    public function getViewCreatedAtAttribute(): string
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
     * ステータス
     * @return string
     */
    public function getViewStatusAttribute()
    {
        if ($this->status == 1) {
            return '<span class="badge bg - success text - white">受理</span>';
        } else if ($this->status == 2) {
            return '<span class="badge bg - warning text - dark">却下</span>';
        }
        return '<span class="badge badge - light">未対応</span>';
    }
}
