<?php

namespace App\AppRinger\DTO;

class ResponseMessageDTO
{
    public $code;
    public $msg;
    public $error;
    public $data;

    private function __construct()
    {
    }

    static function validatorFail(int $code, string $msg, object $error, array $data): ResponseMessageDTO
    {
        $instance = new ResponseMessageDTO();
        $instance->code = $code;
        $instance->msg  = $msg;
        $instance->error = $error;
        $instance->data = $data;
        return $instance;
    }

    static function error(int $code, string $msg, string $error, array $data): ResponseMessageDTO
    {
        $instance = new ResponseMessageDTO();
        $instance->code = $code;
        $instance->msg  = $msg;
        $instance->error = $error;
        $instance->data = $data;
        return $instance;
    }

    static function success(int $code, string $msg, $data): ResponseMessageDTO
    {
        $instance = new ResponseMessageDTO();
        $instance->code = $code;
        $instance->msg  = $msg;
        $instance->data = $data;
        return $instance;
    }
}
