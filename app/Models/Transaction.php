<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable  = ['customer_id', 'order_id', 'user_id', 'status', 'type', 'flag', 'amount', 'detail'];
}
