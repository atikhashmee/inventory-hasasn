<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'shop_id',
        'quantity_unit_id',
        'quantity_unit_value',
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


   
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'quantity_unit_id');
    }

    
}
