<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'vendor_id',
        'name',
        'postal_code',
        'prefecture_id',
        'municipality',
        'address_building_name',
        'phone_number',
        'url',
        'contents',
    ];

    /**
     * @return string
     *
     * getViewUpdatedAtAttribute
     */
    public function getViewAddressAttribute(): string
    {
        return prefectures($this->prefecture_id) . $this->municipality . $this->address_building_name;
    }

    /**
     * @return mixed
     */
    public function getViewUrlAttribute(): string
    {
        if ($this->url) {
            return '<a target="_blank" href="' . $this->url . '">' . $this->url . '</a>';
        }
        return '';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'vendor_id', 'vendor_id');
    }
}
