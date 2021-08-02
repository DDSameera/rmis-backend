<?php

namespace App\Exceptions;


use App\Traits\SendResponseTrait;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Psy\Exception\TypeErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function report(Throwable $e)
    {

        //General Log
        Log::channel('exception')->error($e, ['user' => Auth::user()]);


    }

    public function render($request, Throwable $e)
    {

        if (!config('app.debug')) {

            if ($e instanceof ThrottleRequestsException) {
                //Report to Critical Log
                Log::channel('critical')->critical($e, ['user' => Auth::user()]);

                //Too Many Login Attempts
                return MaxLoginAttemptsException::render();

            } else if ($e instanceof QueryException) {
                //Query Exception,Duplicate Entry
                return CustomQueryException::render($e);
            } else if ($e instanceof ModelNotFoundException) {
                //Model Not Found Exception
                return SendResponseTrait::sendError('Page or Record Not Found. ', "Error", Response::HTTP_NOT_FOUND);

            } else if ($e instanceof TokenMismatchException) {
                //Token Mismatch Exception
                return SendResponseTrait::sendError('Token is mismatch', "Error", 500);

            } else if ($e instanceof NotFoundHttpException) {
                //Not Found Http Exception
                return SendResponseTrait::sendError('Invalid Url', "Error", 404);

            } else if ($e instanceof RouteNotFoundException) {
                //Route Not Found
                return SendResponseTrait::sendError('Invalid Route', "Error", 404);

            } else if ($e instanceof AuthenticationException) {
                //Authentication
                return SendResponseTrait::sendError('Token Expired.Please Login again', "Error", 404);

            } else {
                //Any Exception
                return SendResponseTrait::sendError('We are doing Some Maintenance . Please try again later. ', "Error", 404);

            }
        } else {
            return parent::render($request, $e);
        }
    }


}

