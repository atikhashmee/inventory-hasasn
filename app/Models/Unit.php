<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Unit
 * @package App\Models
 * @version September 10, 2021, 2:43 am UTC
 *
 * @property string $name
 * @property integer $quantity_base
 * @property string $status
 */
class Unit extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'units';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'quantity_base',
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
        'quantity_base' => 'integer',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'quantity_base' => 'required'
    ];

    
}
