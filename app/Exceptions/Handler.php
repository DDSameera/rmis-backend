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

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $e)
    {


        //Too Many Login Attempts
        if ($e instanceof ThrottleRequestsException) {
            return MaxLoginAttemptsException::render();
        }

        //Query Exception,Duplicate Entry
        if ($e instanceof QueryException) {
            return CustomQueryException::render($e);
        }

        //Model Not Found Exception
        if ($e instanceof ModelNotFoundException) {
            return SendResponseTrait::sendError('Page or Record Not Found. ', "Error", Response::HTTP_NOT_FOUND);

        }

        //Token Mismatch Exception
        if ($e instanceof TokenMismatchException) {
            return SendResponseTrait::sendError('Token is mismatch', "Error", 500);

        }

        //Not Found Http Exception
        if ($e instanceof NotFoundHttpException) {
            return SendResponseTrait::sendError('Invalid Url', "Error", 404);

        }

        //Route Not Found
        if ($e instanceof RouteNotFoundException) {
            return SendResponseTrait::sendError('Invalid Route', "Error", 404);

        }

        //Authentication
        if ($e instanceof AuthenticationException) {
            return SendResponseTrait::sendError('Token Expired.Please Login again', "Error", 404);

        }


        return parent::render($request, $e);
    }


}
