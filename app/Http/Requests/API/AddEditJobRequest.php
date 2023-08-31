<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class AddEditJobRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => 'required',
            'description'       => 'required_if:status,==,publish',
            'job_type_id'          => 'required_if:status,==,publish',
            'salary_type_id'       => 'required_if:status,==,publish',
            'min_salary'      => 'required_if:status,==,publish|max:16',
            'max_salary'      => 'required_if:status,==,publish|max:16',
            'work_authorization_id'        => 'required_if:status,==,publish',
            'joining_preference_id'        => 'required_if:status,==,publish',
            'travel_percentage'     => 'required_if:is_travel_required,0,1|nullable|min:1|max:100'
        ];
    }
}
