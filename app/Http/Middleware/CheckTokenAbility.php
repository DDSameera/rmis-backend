<?php

namespace App\Http\Middleware;

use App\Traits\SendResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTokenAbility
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {


        //Get Current Route Name
        $routeName = $request->route()->getName();


        //Exclude Routes
        $excludeRoutes = [
            'user.login',
            'user.register',
            'user.logout',

        ];

        if (in_array($routeName, $excludeRoutes)) {
            return $next($request);
        }

        //Admin Privileges

        if (Auth::user()->role === "admin") {
            switch ($routeName) {

                case 'generate.chart' :
                    if (Auth::user()->tokenCan('generate-chart'))
                        return $next($request);
                    break;


                //If not matched,Return Permission Error
                default:
                    return SendResponseTrait::sendError('You are not eligible to access this feature.', "Permission Denied", 403);


            }
        }

        return SendResponseTrait::sendError('You are not eligible to access this feature.', "Permission Denied", 403);


    }
}
