<?php

use App\Http\Controllers\Api\v1\ProfitAndLossController;


Route::group(['prefix' => 'v1'], function () {

  //Order calculator
  Route::get('reports/profit-loss', [ProfitAndLossController::class, 'calculateProfitAndLoss']);


});
