<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInventory extends Model
{
    use HasFactory;

    protected $fillable = ['type','order_detail_id', 'transfer_id', 'stock_id','shop_id', 'product_id', 'quantity'];
}
