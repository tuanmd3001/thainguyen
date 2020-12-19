<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    public $table = 'comments';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'document_id',
        'user_id',
        'content',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'document_id' => 'integer',
        'user_id' => 'integer',
        "content" => 'string',
    ];

    protected $appends = [
        'user', 'date'
    ];

    public function getUserAttribute(){
        $user = User::find($this->user_id);
        if ($user) return $user->name;
        return 'Ẩn danh';
    }

    public function getDateAttribute(){
        return date( 'd/m/Y H:i:s', strtotime( $this->created_at ));
    }
}
