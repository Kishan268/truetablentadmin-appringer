<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Constants\ResponseMessages;
use App\Communicator\Communicator;

class CandidateRegisterRequest extends FormRequest
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
        $rules = [
            'first_name'        => 'required',
            'email'             => 'required|email|unique:users',
            'password'          => 'required|min:6|max:12',
            'confirm_password'  => 'required|min:6|max:12',
        ];

        if ($this->isUpdateOperation()) {

            // Exclude the current record from the uniqueness check
            $rules['email'] = $rules['email']. ',id,' . $this->id;
        }

        return $rules;
    }

    protected function isUpdateOperation()
    {
        return $this->request->has('id');
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            Communicator::returnResponse(ResponseMessages::VALIDATOR_FAIL($validator->errors()->first(), $validator->errors()))
        );
    }
}
