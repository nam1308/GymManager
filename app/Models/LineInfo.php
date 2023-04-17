<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'vendor_id',
        'callback',
        'login_channel_id',
        'login_channel_secret',
        'login_channel_secret',
        'message_channel_id',
        'message_channel_secret',
        'message_channel_access_token'
    ];

}
