<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopToShop extends Model
{
    use HasFactory;
    protected $table = 'shop_to_shops';

    protected $fillable = ['shop_from', 'shop_to', 'product_id', 'quantity', 'price'];
}
