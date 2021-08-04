<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Repositories\BaseRepository;

/**
 * Class ShopRepository
 * @package App\Repositories
 * @version July 28, 2021, 6:23 am UTC
*/

class ShopRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'address',
        'status'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Shop::class;
    }
}
