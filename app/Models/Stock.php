<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Stock
 * @package App\Models
 * @version August 20, 2021, 3:17 am UTC
 *
 * @property \App\Models\Product $product
 * @property \App\Models\Supplier $supplier
 * @property \App\Models\WareHouse $warehouse
 * @property unsignedBigInteger $product_id
 * @property unsignedBigInteger $supplier_id
 * @property unsignedBigInteger $warehouse_id
 * @property string $sku
 * @property number $old_price
 * @property number $price
 * @property number $selling_price
 * @property integer $quantity
 */
class Stock extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'stocks';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'product_id',
        'supplier_id',
        'warehouse_id',
        'sku',
        'old_price',
        'price',
        'selling_price',
        'quantity'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'sku' => 'string',
        'old_price' => 'decimal:2',
        'price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'sku' => 'required|unique:products,sku',
        'price' => 'required',
        'selling_price' => 'required',
        'quantity' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class, 'supplier_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function warehouse()
    {
        return $this->belongsTo(\App\Models\WareHouse::class, 'warehouse_id', 'id');
    }
}
