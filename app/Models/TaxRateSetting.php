<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TaxRateSetting
 * @package App\Models
 * @method static updateOrCreate(int[] $array, array $array1)
 */
class TaxRateSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'vendor_id',
        'tax',
    ];
}
