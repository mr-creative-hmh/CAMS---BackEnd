<?php

namespace App\Traits;

trait ResponseMessage
{
    // Responce Trait

    public function SendResponse($message, $data, $statusCode)
    {
        return response([
            "Data" => $data,
            "Message" => $message,
            "StatusCode" => $statusCode
        ], $statusCode);
    }

    // Message Trait

    public function SendMessage($message, $statusCode)
    {
        return response([
            "Message" => $message,
            "StatusCode" => $statusCode
        ], $statusCode);
    }
}
