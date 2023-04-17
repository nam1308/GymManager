<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminProfilePhoto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =
        [
            'vendor_id',
            'admin_id',
            'file'
        ];

    public function Admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'login_id');
    }

    /**
     * @return string
     */
    public function getPhotoUrl(): string
    {
        // return url(asset('storage/assets/images/admin/' . $this->vendor_id . '/profile/' . $this->admin_id . '/' . $this->file));
        return url(optimize_uri($this->imagePathExists()));
    }

    /**
     * 画像パスを取得する
     * @return string
     */
    public function imagePathExists(): string
    {
        $path = '/storage/assets/images/admin/' . $this->vendor_id . '/profile/' . $this->admin_id . '/' . $this->file;
        if (File::exists(public_path($path))) {
            return $path;
        }
        return '/storage/assets/images/profile_icon.png';
    }
}
