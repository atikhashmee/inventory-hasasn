<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Supplier
 * @package App\Models
 * @version August 20, 2021, 3:06 am UTC
 *
 * @property \App\Models\Country $country
 * @property string $name
 * @property string $website_url
 * @property string $contact_person_name
 * @property string $contact_email
 * @property string $contact_phone
 * @property integer $country_id
 * @property string $address
 */
class Supplier extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'suppliers';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'website_url',
        'contact_person_name',
        'contact_email',
        'contact_phone',
        'country_id',
        'address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'website_url' => 'string',
        'contact_person_name' => 'string',
        'contact_email' => 'string',
        'contact_phone' => 'string',
        'country_id' => 'integer',
        'address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'contact_person_name' => 'required',
        'contact_email' => 'required',
        'contact_phone' => 'required',
        'address' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class, 'country_id', 'id');
    }
}
