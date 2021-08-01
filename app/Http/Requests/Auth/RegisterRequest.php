<?php

namespace App\Http\Requests\Auth;

use App\Traits\SendResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Password;


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
            'password' => 'required|string|confirmed|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'role' => 'required|in:admin,user'
        ];
    }

    public function messages()
    {
        return[
            'password.regex'=>'Password should contain alphabetic characters,numbers,& symbol'
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            SendResponseTrait::sendError($validator->errors()->all(), "Validation Errors", Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
