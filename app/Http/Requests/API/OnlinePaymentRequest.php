<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class OnlinePaymentRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required',
            'nonce' => 'required',
            'deviceData' => 'required',
        ];
    }
}
