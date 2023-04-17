<?php

namespace App\Models;

use App\Scopes\ScopeVendor;
use Auth;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Billable;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'vendor_id',
        'login_id',
        'name',
        'email',
        'password',
        'role',
        'trainer_role',
        'token',
        'self_introduction',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeAdminVendorId($query)
    {
        return $query->where('vendor_id', Auth::guard('admin')->user()->vendor_id);
    }

    /**
     * @param string $vendor_id
     * @param int $admin_id
     * @param int|null $trainer_role
     * @return \App\Models\Admin
     */
    public static function getTrainer(string $vendor_id, int $admin_id, int $trainer_role = null)
    {
        $data = ['vendor_id' => $vendor_id, 'id' => $admin_id];
        if (!is_null($trainer_role)) {
            $data['trainer_role'] = $trainer_role;
        }
        return self::where($data)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profileImage(): HasOne
    {
        return $this->hasOne(AdminProfilePhoto::class)->withDefault();
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

    /**
     * 管理者権限
     * @return string
     */
    public function getViewRoleAttribute(): string
    {
        if ($this->role) {
            foreach (config('const.ADMIN_ROLE') as $role) {
                if ($role['STATUS'] == $this->role) {
                    return $role['LABEL'];
                }
            }
        }
        return $this->role;
    }

    /**
     * トレーナー権限
     * @return string
     */
    public function getViewTrainerRoleAttribute(): string
    {
        if ($this->role) {
            foreach (config('const.TRAINER_ROLE') as $role) {
                if ($role['STATUS'] == $this->trainer_role) {
                    return $role['LABEL'];
                }
            }
        }
        return $this->role;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adminProfilePhoto(): HasMany
    {
        return $this->hasMany(AdminProfilePhoto::class, 'login_id');
    }

    /**
     * トレーナー
     * @param $query
     * @return mixed
     */
    public function scopeTrainer($query)
    {
        return $query->where('trainer_role', config('const.TRAINER_ROLE.TRAINER.STATUS'));
    }

    public function basicSetting(): BelongsTo
    {
        return $this->belongsTo(BasicSetting::class, 'vendor_id', 'vendor_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function lineMessage()
    {
        return $this->hasOne(LineMessage::class, 'vendor_id', 'vendor_id');
    }

    public function apply()
    {
        return $this->hasOne(Apply::class, 'id', 'vendor_id')->withTrashed();
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'vendor_id', 'vendor_id');
    }
}
