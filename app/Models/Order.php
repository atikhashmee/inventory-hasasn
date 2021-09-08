<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\OrderDetail;
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
        'total_final_amount',
        'status',
        'notes',
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

    /**
     * Get the user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
