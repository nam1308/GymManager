<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find(int $id)
 * @method static updateOrCreate(int[] $array, array $array1)
 */
class BasicSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'vendor_id',
        'company_name',
        'store_name',
        'postal_code',
        'prefecture_id',
        'municipality',
        'business_hours',
        'address_building_name',
        'phone_number',
    ];

    /**
     * 住所取得
     * @return string
     */
    public function getViewAddressAttribute()
    {
        return prefectures($this->prefecture_id) . $this->municipality . $this->address_building_name;
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'vendor_id', 'vendor_id')->where('role',10);
    }
}
