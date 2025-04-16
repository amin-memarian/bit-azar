<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\CoinexApi;
use App\Services\ProfitAndLossService;
use Illuminate\Http\Request;
use App\Models\Api\v1\UsdtPrice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Repositories\Api\v1\UserWalletsRepository;

class ProfitAndLossController extends Controller
{
    private UserWalletsRepository $userWalletsRepository;
    private float $tetherPrice;
    private int $userId;

    public function __construct(
        private ProfitAndLossService $service,
        UserWalletsRepository $userWalletsRepository
    ) {
        $this->userWalletsRepository = $userWalletsRepository;
        $this->tetherPrice = UsdtPrice::get()->quantity / 10;
        $this->userId = Auth::guard('api')->id();
    }

    public function calculateProfitAndLoss(Request $request)
    {

        $sanitized = $this->service->sanitizeRequest($request);
        $validationError = $this->service->validateRequest($sanitized);

        if ($validationError) return $validationError;

        $wallets = $sanitized->currency
            ? $this->userWalletsRepository::getWalletByCurrency($this->userId, $sanitized->currency)
            : $this->userWalletsRepository::getWallets($this->userId);

        $fromDate = $sanitized->from_date ?? null;
        $toDate = $sanitized->to_date ?? null;

        $resultList = collect();

        foreach ($wallets as $wallet) {
            $walletName = strtoupper($wallet->wallet);
            if (in_array($walletName, ['IRR', 'BTZ'])) continue;

            $data = $walletName === 'USDT'
                ? $this->service->calculateUsdtProfit($wallet, $fromDate, $toDate)
                : $this->service->calculateOtherCurrencyProfit($wallet, $fromDate, $toDate);

            $resultList->push($data);
        }

        $average = $resultList->pluck('percent')->avg() ?? 0;

        return Response::success('سود و زیان ارزها', [
            'list' => $resultList,
            'average' => $average,
        ], 200);
    }

}
