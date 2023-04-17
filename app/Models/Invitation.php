<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'vendor_id',
        'email',
        'token',
    ];

    public function getAllWithVendor($vendor_id){
        return Invitation::where('vendor_id', $vendor_id)->get();
    }

    /**
     * 登録日
     * @return string
     */
    public function getViewCreatedAtAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('Y年m月d日 H時i分s秒');
    }

    /**
     * 更新日
     * @return string
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
