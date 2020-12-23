<?php

namespace App\Repositories;

use App\Models\Admin\Privacy;
use App\Repositories\BaseRepository;

/**
 * Class DmCapDonViRepository
 * @package App\Repositories\Danh_Muc
 * @version November 16, 2020, 1:50 pm +07
*/

class PrivacyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'code'
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
        return Privacy::class;
    }
}
