<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{

    public $table = 'attachments';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        "file_name",
        "store_name",
        "file_path",
        "size",
        "extension",
        "content",
        "document_id",
        "temp_id",
        "is_draft",
        "downloadable",
        "mobile",
        "upload_by",
        "parsed",
        "process_lock",
        "process_msg",
        "retry_count",
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        "file_name" => 'string',
        "store_name" => 'string',
        "size" => 'integer',
        "extension" => 'string',
        "file_path" => 'string',
        "content" => 'string',
        "document_id" => 'integer',
        "temp_id" => 'string',
        "is_draft" => 'integer',
        "downloadable" => 'integer',
        "mobile" => 'integer',
        "upload_by" => 'integer',
        "parsed" => 'integer',
        "process_lock" => 'string',
        "process_msg" => 'string',
        "retry_count" => 'integer'
    ];
}
