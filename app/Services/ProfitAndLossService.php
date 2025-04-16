<?php

namespace App\Services;

use App\Helpers\CoinexApi;
use App\Models\Api\v1\OrdersList;
use App\Models\Api\v1\UsdtPrice;
use Elegant\Sanitizer\Sanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ProfitAndLossService
{
    private float $tetherPrice;
    private int $userId;

    private const COINEX_WAGE = 0.2;

    public function __construct()
    {
        $this->tetherPrice = UsdtPrice::get()->quantity / 10;
        $this->userId = Auth::guard('api')->id();
    }

    public function sanitizeRequest(Request $request): object
    {
        $sanitizer = new Sanitizer($request->all(), [
            'currency' => 'strip_tags',
            'from_date' => 'strip_tags',
            'to_date' => 'strip_tags',
        ]);

        return (object) $sanitizer->sanitize();
    }

    public function validateRequest(object $sanitized): ?JsonResponse
    {
        $validator = Validator::make((array) $sanitized, [
            'currency' => ['string', 'nullable'],
            'from_date' => ['date', 'nullable'],
            'to_date' => ['date', 'nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return null;
    }

    public function calculateUsdtProfit($wallet, ?string $fromDate, ?string $toDate): array
    {
        $orders = OrdersList::findByMarketBuy($wallet->wallet, $fromDate, $toDate, $this->userId);
        $costAndBenefit = $amount = $tomanValue = 0;

        foreach ($orders ?? [] as $order) {
            $netPrice = $order->usdt_price - (self::COINEX_WAGE / 100);
            $costAndBenefit += $netPrice / 100;
            $tomanValue += $netPrice * $this->tetherPrice;
            $amount += $order->amount;
        }

        return [
            'currency' => 'USDT',
            'percent' => round($costAndBenefit, 4),
            'tether' => round($tomanValue, 2),
            'amount' => $amount,
        ];
    }

    public function calculateOtherCurrencyProfit($wallet, ?string $fromDate, ?string $toDate): array
    {
        $market = strtoupper($wallet->wallet) . 'USDT';

        $apiResult = retry(3, fn() => CoinexApi::send('market/ticker', ['market' => $market], 'get'), 100);
        $lastPrice = (float) ($apiResult->data->ticker->last ?? 0);

        if (!$lastPrice) {
            return [
                'currency' => $market,
                'percent' => 0,
                'tether' => 0,
                'amount' => 0,
            ];
        }

        $orders = OrdersList::findByMarketBuy($wallet->wallet, $fromDate, $toDate, $this->userId);
        $costAndBenefit = $amount = 0;

        foreach ($orders ?? [] as $order) {
            $buyPrice = str_contains($order->market, 'IRT')
                ? $order->currency_price / $order->tether_price
                : $order->currency_price;

            $sellPrice = $lastPrice * (1 + self::COINEX_WAGE / 100);
            $costAndBenefit += ($buyPrice - $sellPrice) / 100;
            $amount += $order->amount;
        }

        return [
            'currency' => $market,
            'percent' => round($costAndBenefit, 4),
            'tether' => round($costAndBenefit * $lastPrice, 2),
            'amount' => $amount,
        ];
    }
}

