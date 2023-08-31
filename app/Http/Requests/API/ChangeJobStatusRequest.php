<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class ChangeJobStatusRequest extends BaseRequest
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
            'status' => 'required',
        ];
    }
}
