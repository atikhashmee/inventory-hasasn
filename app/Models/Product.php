<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Product
 * @package App\Models
 * @version August 8, 2021, 4:57 pm UTC
 *
 * @property \App\Models\Category $category
 * @property \App\Models\Brand $brand
 * @property \App\Models\Supplier $supplier
 * @property \App\Models\Menufacture $menufacture
 * @property \App\Models\WareHouse $warehouse
 * @property string $name
 * @property string $description
 * @property number $old_price
 * @property number $price
 * @property number $selling_price
 * @property integer $quantity
 * @property string $slug
 * @property string $sku
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $supplier_id
 * @property integer $menufacture_id
 * @property integer $warehouse_id
 * @property string $feature_image
 */
class Product extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'products';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'description',
        'old_price',
        'price',
        'selling_price',
        'quantity',
        'slug',
        'sku',
        'category_id',
        'brand_id',
        'supplier_id',
        'menufacture_id',
        'warehouse_id',
        'feature_image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'old_price' => 'decimal:2',
        'price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
        'slug' => 'string',
        'sku' => 'string',
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'supplier_id' => 'integer',
        'menufacture_id' => 'integer',
        'warehouse_id' => 'integer',
        'feature_image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'price' => 'required',
        'selling_price' => 'required',
        'quantity' => 'required',
        'slug' => 'required|unique:products,slug',
        'sku' => 'required|unique:products,sku'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brand_id', 'id');
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
    public function menufacture()
    {
        return $this->belongsTo(\App\Models\Menufacture::class, 'menufacture_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function warehouse()
    {
        return $this->belongsTo(\App\Models\WareHouse::class, 'warehouse_id', 'id');
    }
}
