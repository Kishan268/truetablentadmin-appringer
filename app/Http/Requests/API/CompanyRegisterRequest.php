<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Constants\ResponseMessages;
use App\Communicator\Communicator;

class CompanyRegisterRequest extends FormRequest
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
            'first_name'        => 'required',
            'email'             => 'required|email|unique:users',
            'password'          => 'required|min:6|max:12|same:confirm_password',
            'confirm_password'  => 'required|min:6|max:12',
            'company_name'      => 'required|string',
            'website'           => 'required|unique:companies',
            'location'          => 'required',
            'company_size'      => 'required',
            'industry_domain'   => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            Communicator::returnResponse(ResponseMessages::VALIDATOR_FAIL($validator->errors()->first(), $validator->errors()))
        );
    }
}
