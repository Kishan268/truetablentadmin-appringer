<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class SearchJobRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'locations' => 'array',
            'skills' => 'array',
            'job_types' => 'array',
            'job_durations' => 'array',
            'work_auths' => 'array',
            'industry_domains' => 'array',
            'salary_types' => 'array',
            'min_salary' => 'array',
            'max_salary' => 'array',
            'joining_preferences' => 'array',
        ];
    }
}
