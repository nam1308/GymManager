<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'vendor_id',
        'name',
        'price',
        'time',
        'course_time',
        'course_minutes',
        'contents',
    ];

    /**
     * @return string
     */
    public function getViewPriceAttribute(): string
    {
        if ($this->price) {
            return number_format($this->price) . '円';
        }
        return '無料';
    }

    /**
     * @return string
     */
    public function getViewCourseTimeAttribute(): string
    {
        return $this->course_time + $this->course_minutes . '分';
    }
}
