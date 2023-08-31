<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class CloseGigRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'close_reason_id' => 'required',
        ];
    }
}
