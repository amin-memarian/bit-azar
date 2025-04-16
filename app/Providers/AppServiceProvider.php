<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        Schema::defaultStringLength(191);
    }

    public function boot()
    {

        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
        Blade::component('components.backend-breadcrumbs', 'backendBreadcrumbs');
        Response::macro('success', function($message, $data = null, $status = 1) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
                'code' => 200
            ]);
        });

        Response::macro('failed', function($message, $data = null, $code = 500, $status = 0) {
            return response()->json([
                'status' => $status,
                'error' => $message,
                'data' => $data,
                'code' => $code
            ]);
        });
    }
}
