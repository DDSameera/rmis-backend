<?php

namespace App\Http\Requests;

use App\Traits\SendResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplicantRequest extends FormRequest
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
            'onboarding_percentage' => 'required|numeric',
            'count_applications' => 'required|numeric',
            'count_accepted_applications' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            SendResponseTrait::sendError($validator->errors()->all(), "Validation Errors", 422)
        );
    }
}
