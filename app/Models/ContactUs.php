<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static orderBy(string $string, string $string1)
 */
class ContactUs extends Model
{
    use SoftDeletes;
    protected $primaryKey = "user_id";

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'content',
        'agent',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
