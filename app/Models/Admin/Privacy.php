<?php

namespace App\Models\Admin;

use Eloquent as Model;

/**
 * Class DmCapDonVi
 * @package App\Models\Danh_Muc
 * @version November 16, 2020, 1:50 pm +07
 *
 * @property string $ten
 * @property string $code
 */
class Privacy extends Model
{

    public $table = 'privacy';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name',
        'code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'code' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'code' => "string|nullable",
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


}
