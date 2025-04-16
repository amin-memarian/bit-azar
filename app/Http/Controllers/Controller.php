<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // For send normal message, we need template id in all the controllers
    public $templates_id = [
        'register' => 68891,
        'market_order' => 68892,
        'limit_order' => 68893,
        'toman_charge' => 68894,
        'forgot_password' => 68895,
        'login' => 68896,
        'sell_limit_order' => 68908,
        'sell_market_order' => 68909,
        'coinex_error' => 74293,
        'limit_order_complete' => 74373,
        'limit_order_cancel' => 74374,
        'change_password' => 68895
    ];
}
