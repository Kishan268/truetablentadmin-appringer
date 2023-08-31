<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class ChangeApplicantStatusRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'job_id' => 'required',
            'candidate_id' => 'required',
            'recruiter_action' => 'required',
        ];
    }
}
