<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Constants\ResponseMessages;
use App\Communicator\Communicator;
use App\Constants\StringConstants;

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
            'email'     => 'required|email|exists:users,email|max:50',
            'password'  => 'required'
       ];
    }

    public function messages()
    {
        return [
            'email.exists' => StringConstants::USER_NOT_FOUND_MSG,
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            Communicator::returnResponse(ResponseMessages::VALIDATOR_FAIL($validator->errors()->first(), $validator->errors()))
        );
    }
}
