<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserActivityLog
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

        //Get Response
        $response = $next($request);

        $log = [
            'URL' => $request->getUri(),
            'METHOD' => $request->getMethod(),
            'BODY' => $request->all(),
            'RESPONSE' => $response->getContent()

        ];
        Log::channel('activity')->info(json_encode($log), ['user' => Auth::user()]);

        return $next($request);
    }
}
