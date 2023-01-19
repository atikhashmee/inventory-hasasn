<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\QuotationItem;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Quotation
 * @package App\Models
 * @version September 14, 2021, 3:21 am UTC
 *
 * @property \App\Models\Shop $shop
 * @property integer $shop_id
 * @property string $recipient
 * @property string $recipient_address
 * @property string $date
 * @property string $subject
 * @property string $notes
 */
class Quotation extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'quotations';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'shop_id',
        'recipient',
        'recipient_address',
        'date',
        'subject',
        'notes',
        'terms_and_con'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'shop_id' => 'integer',
        'recipient' => 'string',
        'recipient_address' => 'string',
        'subject' => 'string',
        'notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'recipient' => 'required',
        'date' => 'required',
        'subject' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function shop()
    {
        return $this->belongsTo(\App\Models\Shop::class, 'shop_id', 'id');
    }

    /**
     * Get all of the items for the Quotation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
