<?php

namespace App\Http\Requests\API;

use App\Http\Requests\API\BaseRequest;
use App\Constants\StringConstants;

class SendMessageRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'max:1000',
            'job_id'  => 'nullable|exists:company_jobs,id',
            'candidate_id'  => 'nullable|exists:users,id',
            'recruiter_id'  => 'nullable|exists:users,id',
            'chat_id'  => 'nullable|exists:chats,id',
            // 'media' => 'mimes:jpeg,png,jpg,csv,pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:16384'
            'media.*' => 'max:16384'
        ];
    }

    public function messages()
    {
        return [
            'message.max' => StringConstants::MESSAGE_LIMIT_ERROR,
            'media.*.max' => StringConstants::MEDIA_MESSAGE_LIMIT_ERROR,
        ];
    }
}
