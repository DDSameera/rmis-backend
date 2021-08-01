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
            'user.logout'
        ];

        if (in_array($routeName, $excludeRoutes)) {
            return $next($request);
        }

        //Admin Privileges
        if (Auth::user()->role === "admin") {
            switch ($routeName) {

                case 'applicant.index' :
                    if (Auth::user()->tokenCan('applicant-index'))
                        return $next($request);
                    break;

                case 'applicant.store' :
                    if (Auth::user()->tokenCan('applicant-store'))
                        return $next($request);
                    break;

                case 'applicant.update' :
                    if (Auth::user()->tokenCan('applicant-update'))
                        return $next($request);
                    break;

                case 'applicant.show' :
                    if (Auth::user()->tokenCan('applicant-show'))
                        return $next($request);
                    break;

                case 'applicant.destroy' :
                    if (Auth::user()->tokenCan('applicant-destroy'))
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
