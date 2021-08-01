<?php


namespace App\Traits;




trait SendResponseTrait
{
    public static function sendSuccessWithToken($result, $message,$status_code)
    {

        $response = [
            'success' => true,
            'message' => $message
        ];

        if ($result) {
            $response['data'] = $result;
        }

        return response()->json($response, $status_code);


    }

    public static function sendError($errors, $message,$status_code)
    {

        $response = [
            'success' => false,
            'errors' => $errors,
            'message' => $message
        ];


        return response()->json($response,$status_code);

    }
}