<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Shop
 * @package App\Models
 * @version July 28, 2021, 6:23 am UTC
 *
 * @property string $name
 * @property string $address
 * @property enum(['active' $status
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
        'name' => 'string',
        'address' => 'string'
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
