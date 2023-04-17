<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class ChannelJoin extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'vendor_id',
    ];

    /**
     * @param string $vendor_id
     * @return \Illuminate\Support\Collection
     */
    public static function members(string $vendor_id): Collection
    {
        return DB::table('channel_joins')->where('vendor_id', $vendor_id)
            ->leftJoin('users', 'channel_joins.user_id', '=', 'users.id')
            ->whereNotNull('users.name')
            ->get();
    }

    /**
     * @param $query
     * @param string $user_id
     * @param string $vendor_id
     * @return mixed
     */
    public function scopejoin($query, string $user_id, string $vendor_id)
    {
        return $query->where('user_id', $user_id)->where('vendor_id', $vendor_id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withDefault();
    }

    public function lineMessage(): HasOne
    {
        return $this->hasOne(LineMessage::class, 'vendor_id', 'vendor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainers(): HasMany
    {
        return $this->hasMany(Admin::class, 'vendor_id', 'vendor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function basicSetting(): HasOne
    {
        return $this->hasOne(BasicSetting::class, 'vendor_id', 'vendor_id');
    }
}
