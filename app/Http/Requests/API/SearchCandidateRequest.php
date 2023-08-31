<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class SearchCandidateRequest extends BaseRequest
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
            'work_authorization' => 'array',
        ];
    }
}
