<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;


class MaxLoginAttemptsException extends Exception
{

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public static function render()
    {

        $apiMaxLoginDelay = config('app.api_max_login_delay');

        $response = [
            'success' => false,
            'message' => "Too Many Login Attempts.Please wait " . $apiMaxLoginDelay . " minutes"
        ];


        return response()->json($response, Response::HTTP_TOO_MANY_REQUESTS);

    }




}
