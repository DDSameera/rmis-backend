<?php


namespace App\Traits;


trait SendResponseTrait
{
    public static function sendSuccessWithToken($result, $message,$status_code)
    {

        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];

        return response()->json($response, $status_code);


    }

    public static function sendErrorWithToken($errors, $message,$status_code)
    {

        $response = [
            'success' => false,
            'errors' => $errors,
            'message' => $message
        ];


        return response()->json($response, $status_code);

    }
}