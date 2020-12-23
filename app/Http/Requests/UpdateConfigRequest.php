<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigRequest extends FormRequest
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
        return [
            'text_code' => 'required',
            'save_path' => 'required|string|max:255|regex:"^[a-zA-Z0-9]+[a-zA-Z0-9\/\_]*[a-zA-Z0-9]+$"',
            'log_search' => 'integer|nullable',
            'log_view' => 'integer|nullable',
            'log_download' => 'integer|nullable',
            'log_comment' => 'integer|nullable',
        ];
    }

    public function messages()
    {
        return [
            'save_path.regex' => 'Đường dẫn không đúng định dạng'
        ];
    }
}
