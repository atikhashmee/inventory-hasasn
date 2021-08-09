<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Shop
 * @package App\Models
 * @version August 8, 2021, 5:04 pm UTC
 *
 * @property string $name
 * @property string $address
 * @property string $status
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
        'status'
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
        'status' => 'string'
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
