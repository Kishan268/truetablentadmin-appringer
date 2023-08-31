<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;


class UploadMediaRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|file',
            'type' => 'required',
        ];
    }
}
