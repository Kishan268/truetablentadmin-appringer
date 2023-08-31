<?php

namespace App\Communicator;

use App\AppRinger\DTO\ResponseMessageDTO;
use App\Constants\Attributes;


class Communicator
{

    public static function returnResponse(ResponseMessageDTO $respMsg)
    {
        $response[Attributes::CODE]      = $respMsg->code;
        $response[Attributes::ERROR]      = $respMsg->error;
        $response[Attributes::MESSAGE]  = $respMsg->msg;
        $response[Attributes::DATA]      = $respMsg->data;
        return response()->json($response, $respMsg->code);
    }

    public static function returnSuccessResponse(ResponseMessageDTO $respMsg)
    {
        $response[Attributes::CODE]      = $respMsg->code;
        $response[Attributes::MESSAGE]  = $respMsg->msg;
        $response[Attributes::DATA]      = $respMsg->data;
        return response()->json($response, $respMsg->code);
    }
}
