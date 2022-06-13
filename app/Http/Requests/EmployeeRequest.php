<?php

namespace App\Http\Requests;

use App\Http\Traits\RequestValidationTrait;
use App\Rules\UniqueNationalID;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    use RequestValidationTrait;
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
            'name'          => 'required|string|min:3|max:50',
            'national_id'   => ['required','digits:11',new UniqueNationalID()],
            'image_file'    => 'required|max:10240',
            'image_file.*'  => 'mimes:jpeg,jpg,png',
            'video_file'    => 'required|max:20480',
            'video_file.*'  => 'mimes:mp4'
        ];
    }
}
