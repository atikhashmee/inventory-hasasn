<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class WareHouse
 * @package App\Models
 * @version July 28, 2021, 6:01 am UTC
 *
 * @property string $ware_house_name
 */
class WareHouse extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'ware_houses';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'ware_house_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ware_house_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ware_house_name' => 'required'
    ];

    
}
