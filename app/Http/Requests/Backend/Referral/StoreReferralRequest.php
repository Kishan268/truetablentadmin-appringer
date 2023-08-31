<?php

namespace App\Http\Requests\Backend\Referral;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class StoreUserRequest.
 */
class StoreReferralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return $this->user()->isAdmin();
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_type'             => 'required',
            'program_name'          => 'required',
            'program_description'   => 'required',
            'start_date'            => 'required',
            'end_date'              => 'required',
            'amount'                => 'required',
            'eligiblity_number'     => 'required',
        ];
    }
}
