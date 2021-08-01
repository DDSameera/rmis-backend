<?php

namespace App\Http\Requests\Auth;

use App\Traits\SendResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;


class RegisterRequest extends FormRequest
{
    use SendResponseTrait;

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
            'fname' => 'required|string',
            'mname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|string|unique:users|email',
            'mobile' => 'required|string',
            'password' => 'required|string|confirmed',
            'role' => 'required|in:admin,user'
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            SendResponseTrait::sendError($validator->errors()->all(), "Validation Errors", Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
