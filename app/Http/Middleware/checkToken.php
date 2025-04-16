<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class checkToken
{

    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('api')->check()){
            return $next($request);
        }else{
            return Response::failed('توکن شما منقضی شده است', null, 401, null);
        }
    }
}
