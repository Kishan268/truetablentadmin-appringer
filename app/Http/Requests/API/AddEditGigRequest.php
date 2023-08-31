<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class AddEditGigRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'title'             => 'required',
            'description'       => 'required_if:status,==,publish',
            'gig_type_id'       => 'required_if:status,==,publish',
            'min_budget'        => 'required_if:status,==,publish',
            'max_budget'        => 'required_if:status,==,publish',
        ];
    }
}
