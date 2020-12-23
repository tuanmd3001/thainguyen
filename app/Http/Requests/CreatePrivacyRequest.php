<?php

namespace App\Http\Requests;

use App\Models\Admin\Privacy;
use Illuminate\Foundation\Http\FormRequest;

class CreatePrivacyRequest extends FormRequest
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
        return Privacy::$rules;
    }
}
