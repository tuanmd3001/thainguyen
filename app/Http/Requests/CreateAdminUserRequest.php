<?php

namespace App\Http\Requests;

use App\Models\Admin\AdminUser;
use Illuminate\Foundation\Http\FormRequest;

class CreateAdminUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return AdminUser::$rules;
    }
}
