<?php

namespace App\Models\Admin;

use Eloquent as Model;
use Spatie\Tags\HasTags;

/**
 * Class Document
 * @package App\Models\Admin
 * @version November 24, 2020, 9:29 pm +07
 *
 * @property string $name
 * @property string $description
 * @property integer $privacy
 * @property integer $status
 * @property string $thumbnail
 */
class Document extends Model
{
    use HasTags;

    public $table = 'documents';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_RESTRICT = 1;
    const STATUS_EXPLOIT = 2;
    const STATUS_LABEL = [
        self::STATUS_RESTRICT => 'Hạn chế',
        self::STATUS_EXPLOIT => 'Khai thác'
    ];

    const SAVE_TYPE_DRAFT = 1;
    const SAVE_TYPE_PUBLIC = 0;
    const SAVE_TYPE_LABEL = [
        self::SAVE_TYPE_DRAFT => 'Tài liệu nháp',
        self::SAVE_TYPE_PUBLIC => 'Tài liệu chính thức'
    ];

    public $fillable = [
        'name',
        'description',
        'privacy',
        'status',
        'thumbnail',
        'disable_comment',
        'draft'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'privacy' => 'integer',
        'status' => 'integer',
        'thumbnail' => 'string',
        'disable_comment' => 'integer',
        'draft' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'privacy' => 'required|integer',
        'status' => 'required|integer',
        'thumbnail' => 'nullable|file',
        'disable_comment' => 'nullable',
        'save_type' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


}
