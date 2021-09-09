<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table ='customers';
    protected $fillable = [
        'customer_name','customer_email','customer_phone','customer_address'
    ];


    public function getCurrentDueAttribute() {
        $totalDeposit = Transaction::where("type", "in")->where('customer_id', $this->id)->groupBy('customer_id')->sum('amount');
        $totalWithdraw = Transaction::where("type", "out")->where('customer_id', $this->id)->groupBy('customer_id')->sum('amount');
        return $totalWithdraw - $totalDeposit;
    }
}
