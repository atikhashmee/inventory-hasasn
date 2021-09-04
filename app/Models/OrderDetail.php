<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'shop_id',
        'product_name',
        'product_unit_price',
        'product_quantity',
        'returned_quantity',
        'final_quantity',
        'sub_total',
        'total',
        'returned_amount',
        'final_amount',
        'product_cost',
        'rejected_at',
        'status'
    ];

    
}
