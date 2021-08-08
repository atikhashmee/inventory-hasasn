<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Product
 * @package App\Models
 * @version August 8, 2021, 1:10 pm UTC
 *
 * @property \App\Models\Category $category
 * @property \App\Models\WareHouse $warehouse
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string $sku
 * @property integer $category_id
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
        'slug',
        'sku',
        'category_id',
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
        'slug' => 'string',
        'sku' => 'string',
        'category_id' => 'integer',
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
    public function warehouse()
    {
        return $this->belongsTo(\App\Models\WareHouse::class, 'warehouse_id', 'id');
    }
}
