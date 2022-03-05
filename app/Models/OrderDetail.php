<?php

namespace App\Models;

use App\Models\Unit;
use App\Models\Order;
use App\Models\Product;
use App\Models\WarentySerial;
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
        'product_original_unit_price',
        'product_unit_price',
        'product_quantity',
        'returned_quantity',
        'final_quantity',
        'sub_total',
        'total',
        'returned_amount',
        'final_amount',
        'product_cost',
        'warenty_duration',
        'rejected_at',
        'status'
    ];


   
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'quantity_unit_id');
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }


    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * Get all of the warenty for the OrderDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warenty()
    {
        return $this->hasMany(WarentySerial::class, 'order_detail_id', 'id');
    }
    
}
