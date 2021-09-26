<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Product
 * @package App\Models
 * @version September 10, 2021, 6:44 pm UTC
 *
 * @property \App\Models\Category $category
 * @property \App\Models\Country $origin
 * @property \App\Models\Brand $brand
 * @property \App\Models\Menufacture $menufacture
 * @property string $name
 * @property string $description
 * @property number $product_cost
 * @property number $selling_price
 * @property integer $category_id
 * @property integer $origin
 * @property integer $brand_id
 * @property integer $menufacture_id
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
        'code',
        'description',
        'product_cost',
        'selling_price',
        'category_id',
        'origin',
        'brand_id',
        'menufacture_id',
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
        'code' => 'string',
        'description' => 'string',
        'product_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'category_id' => 'integer',
        'origin' => 'integer',
        'brand_id' => 'integer',
        'menufacture_id' => 'integer',
        'feature_image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'code' => 'required|unique:products,code',
        'product_cost' => 'required',
        'selling_price' => 'required'
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
    public function origin()
    {
        return $this->belongsTo(\App\Models\Country::class, 'origin', 'id');
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
    public function menufacture()
    {
        return $this->belongsTo(\App\Models\Menufacture::class, 'menufacture_id', 'id');
    }
}
