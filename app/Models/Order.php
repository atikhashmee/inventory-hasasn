<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'shop_id',
        'user_id',
        'sub_total',
        'discount_amount',
        'total_amount',
        'total_product_cost',
        'total_returned_amount',
        'total_final_amount',
        'returned_amount',
        'status',
        'notes',
        'challan_note',
        'order_challan_type',
        'refund_status',
        'delivered_at'
    ];


    /**
     * Get the user associated with the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    /**
     * Get all of the detail for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

   
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'id')->where('flag', 'payment');
    }

    
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

}
