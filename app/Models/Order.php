<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'sub_total',
        'discount_amount',
        'total_amount',
        'total_product_cost',
        'total_returned_amount',
        'total_final_amount',
        'returned_amount',
        'total_final_amount',
        'status',
        'notes',
        'refund_status',
        'delivered_at'
    ];

}
