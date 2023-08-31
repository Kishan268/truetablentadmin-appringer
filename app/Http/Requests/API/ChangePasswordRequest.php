<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|min:5|max:12',
            'confirm_password' => 'required|same:new_password',
        ];
    }
}
