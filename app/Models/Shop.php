<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Shop
 * @package App\Models
 * @version September 11, 2021, 7:34 pm UTC
 *
 * @property string $name
 * @property string $address
 * @property string $status
 * @property string $image
 */
class Shop extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'shops';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'address',
        'status',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'status' => 'string',
        'image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    
}
