<?php

namespace App\Exceptions;

use App\Traits\SendResponseTrait;
use Exception;
use Illuminate\Http\Response;


class CustomQueryException extends Exception
{
    public static function render($exception)
    {

        switch ($exception->getCode()) {
            case  "23000" :
                $errorMessage = "Sorry.One User Can allow only to maintain one record.";
                break;

            default :
                $errorMessage = "Maintenance is in progress. Please come back few minutes";
        }

        return SendResponseTrait::sendError($errorMessage, "Error", Response::HTTP_UNAUTHORIZED);

    }
}
