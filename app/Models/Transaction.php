<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable  = ['customer_id', 'order_id', 'user_id', 'status', 'type', 'flag', 'amount', 'detail', 'note', 'tnx_id'];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }



    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'other_key');
    }
}