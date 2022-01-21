<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductStock extends Model
{
    use HasFactory;

    protected $table = 'shop_product_stocks';

    protected $fillable = ['warehouse_id', 'user_id', 'supplier_id', 'order_detail_id', 'shop_id', 'product_id', 'type', 'quantity', 'price'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class, 'supplier_id', 'id');
    }
}
