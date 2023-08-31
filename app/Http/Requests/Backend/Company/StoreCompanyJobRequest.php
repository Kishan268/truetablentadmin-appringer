<?php

namespace App\Http\Requests\Backend\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class StoreUserRequest.
 */
class StoreCompanyJobRequest extends FormRequest
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
            'company_id'                            => 'required',
            'user_id'                       => 'required',
            'title'                                 => 'required',
            'description'                           => 'required_if:status,==,published',
            'job_type_id'                           => 'required_if:status,==,published',
            'work_locations'                        => 'required_if:status,==,published',
            'required_skills'                       => 'required_if:status,==,published',
            'minimum_experience_required'           => 'required_if:status,==,published',
            'maximum_experience_required'           => 'required_if:status,==,published',
            'salary_type_id'                        => 'required_if:status,==,published',
            'min_salary'                            => 'required_if:status,==,published|max:16',
            'max_salary'                            => 'required_if:status,==,published|max:16',
            'joining_preference_id'                 => 'required_if:status,==,published',
            'travel_percentage'                     => 'required_if:is_travel_required,0,1|nullable|min:1|max:100'
        ];
    }
}
