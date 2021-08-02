<?php

namespace App\Http\Requests\Auth;

use App\Traits\SendResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
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
            'email' => 'required|string',
            'password' => 'required|string|min:6',
            'deviceName'=> 'required|in:mobile,desktop'
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            SendResponseTrait::sendError($validator->errors()->all(), "Validation Errors", 422)
        );
    }
}
