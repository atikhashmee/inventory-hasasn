<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class QuotationItem
 * @package App\Models
 * @version September 14, 2021, 3:58 am UTC
 *
 * @property \App\Models\Quotation $quotation
 * @property integer $quotation_id
 * @property string $item_name
 * @property string $brand
 * @property string $model
 * @property string $origin
 * @property integer $quantity
 * @property number $unit_price
 * @property number $total_price
 */
class QuotationItem extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'quotation_items';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'quotation_id',
        'item_name',
        'brand',
        'model',
        'origin',
        'quantity',
        'unit_price',
        'total_price'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quotation_id' => 'integer',
        'item_name' => 'string',
        'brand' => 'string',
        'model' => 'string',
        'origin' => 'string',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'item_name' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function quotation()
    {
        return $this->belongsTo(\App\Models\Quotation::class, 'quotation_id', 'id');
    }
}
