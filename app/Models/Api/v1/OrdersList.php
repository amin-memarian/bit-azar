<?php

namespace App\Models\Api\v1;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrdersList extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'orders_list';

    const MARKET_TYPE = '1';
    const LIMIT_TYPE = '2';

    const BUY_ROLE = '1';
    const SELL_ROLE = '2';

    const CANCEL_STATUS = '-1';
    const PENDING_STATUS = '0';
    const DONE_STATUS = '1';
    const PART_DEAL = '2';

    const IRR_PAYMENT = 'IRR';
    const USDT_PAYMENT = 'USDT';

    const IRT = 'IRT';
    const USDT = 'USDT';

    const REAL = 'real';
    const VIRTUAL = 'virtual';

    const FILLED = 100;

    const BTC = 'BTCUSDT';

    const ETH = 'ETHUSDT';

    public static function findByMarketBuy($market, $from_date = null, $to_date = null, $user_id)
    {
        if (isset($from_date) && isset($to_date)) {

            return self::query()
                ->where('status', '1')
                ->where('user_id', $user_id)
                ->where('market' , 'like',  $market.'%')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->get();

        } else {

            return self::query()
                ->where('status', '1')
                ->where('user_id', $user_id)
                ->where('market' , 'like',  $market.'%')
                ->get();

        }

    }


}

