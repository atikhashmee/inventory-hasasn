<?php

namespace App\Repositories;

use App\Models\WareHouse;
use App\Repositories\BaseRepository;

/**
 * Class WareHouseRepository
 * @package App\Repositories
 * @version July 28, 2021, 6:01 am UTC
*/

class WareHouseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ware_house_name'
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
        return WareHouse::class;
    }
}
