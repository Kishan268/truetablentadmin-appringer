<?php

namespace App\Constants;

use App\AppRinger\DTO\ResponseMessageDTO;
use App\Constants\Attributes;
use App\AppRinger\Logger;
use App\Constants\StringConstants;

class ResponseMessages
{
    static function EXCEPTIONAL_HANDLING($e): ResponseMessageDTO
    {
        Logger::logError($e);
        if (env('API_DEBUG'))
            $error  = $e->getMessage();
        else
            $error  = StringConstants::SOMETHING_WRONG_MSG;
        return ResponseMessageDTO::error(Attributes::ERROR_CODE, StringConstants::SOMETHING_WRONG_MSG, $error, array());
    }

    static function VALIDATOR_FAIL(string $msg, object $error): ResponseMessageDTO
    {
        Logger::logError($error);
        return ResponseMessageDTO::validatorFail(Attributes::ERROR_CODE, $msg, $error, array());
    }

    static function ERROR(string $msg, string $error, array $data= []): ResponseMessageDTO
    {
        Logger::logError($error);
        return ResponseMessageDTO::error(Attributes::ERROR_CODE, $msg, $error, $data);
    }

    static function NO_REGISTRATION_SUCCESS(string $msg, $data): ResponseMessageDTO
    {
        return ResponseMessageDTO::success(Attributes::NO_REGISTRATION_CODE, $msg, $data);
    }

    static function NOT_FOUND(string $msg, $data): ResponseMessageDTO
    {
        return ResponseMessageDTO::success(Attributes::NOT_FOUND_CODE, $msg, $data);
    }

    static function ACCOUNT_DELETED_LOGIN_SUCCESS(string $msg, $data): ResponseMessageDTO
    {
        return ResponseMessageDTO::success(Attributes::ACCOUNT_DELETE_CODE, $msg, $data);
    }

    static function CONTACT_ADMIN_SUCCESS(string $msg, $data): ResponseMessageDTO
    {
        return ResponseMessageDTO::success(Attributes::CONTACT_ADMIN_CODE, $msg, $data);
    }

    static function SUCCESS(string $msg, $data): ResponseMessageDTO
    {
        return ResponseMessageDTO::success(Attributes::SUCCESS_CODE, $msg, $data);
    }
}
