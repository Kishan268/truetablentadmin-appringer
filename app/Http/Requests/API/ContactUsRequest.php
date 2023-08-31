<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class ContactUsRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            // 'phone' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ];
    }
}
