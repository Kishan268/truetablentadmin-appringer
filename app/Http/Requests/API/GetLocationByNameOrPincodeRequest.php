<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class GetLocationByNameOrPincodeRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'q' => 'required|min:3',
        ];
    }
}
