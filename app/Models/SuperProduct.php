<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperProduct extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $id = 'id';
    protected $fillable = [
        'vendor_id',
        'product_category_id',
        'name', 'code',
        'client_ip',
        'purchase_count',
        'free_term',
        'intial_price',
        'reference_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
