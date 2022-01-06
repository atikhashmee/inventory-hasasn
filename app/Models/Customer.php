<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table ='customers';
    protected $fillable = [
        'shop_id', 'user_id', 'customer_name','customer_email','customer_phone','customer_address'
    ];


    public function getCurrentDueAttribute() {
        $totalDeposit = Transaction::where("type", "in")->where('customer_id', $this->id)->groupBy('customer_id')->sum('amount');
        $totalWithdraw = Transaction::where("type", "out")->where('customer_id', $this->id)->groupBy('customer_id')->sum('amount');
        return $totalWithdraw - $totalDeposit;
    }

    /**
     * Get the shop that owns the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * Get the user that owns the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
