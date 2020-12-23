<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $table = 'configs';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'text_code',
        'save_path',
        'log_search',
        'log_view',
        'log_download',
        'log_comment',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'text_code' => 'string',
        'save_path' => 'string',
        'log_search' => 'integer',
        'log_view' => 'integer',
        'log_download' => 'integer',
        'log_comment' => 'integer',
    ];
}
