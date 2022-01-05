<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Challan
 * @package App\Models
 * @version September 14, 2021, 2:52 am UTC
 *
 * @property \App\Models\Shop $shop
 * @property \App\Models\Customer $customer
 * @property \App\Models\Unit $unit
 * @property integer $shop_id
 * @property integer $customer_id
 * @property string $product_type
 * @property integer $quantity
 * @property integer $unit_id
 * @property number $total_payable
 * @property string $challan_note
 */
class Challan extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'challans';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'shop_id',
        'customer_id',
        'product_type',
        'quantity',
        'unit_id',
        'challan_type',
        'total_payable',
        'challan_note'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'shop_id' => 'integer',
        'customer_id' => 'integer',
        'product_type' => 'string',
        'quantity' => 'integer',
        'unit_id' => 'integer',
        'total_payable' => 'decimal:2',
        'challan_note' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_type' => 'required',
        'quantity' => 'required',
        'total_payable' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function shop()
    {
        return $this->belongsTo(\App\Models\Shop::class, 'shop_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'unit_id', 'id');
    }
}
