<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CarInstallmentCalculator;
use Illuminate\Http\Request;

class CarInstallmentController extends BaseController
{
    public function calculateInstallment(Request $request)
    {
        $request->validate([
            'car_price' => 'required|numeric|min:1',
            'down_payment' => 'required|numeric|min:0',
        ]);

        $calculator = new CarInstallmentCalculator(
            $request->car_price,
            $request->down_payment
        );

        $result = $calculator->calculate();

        return response()->json($result);
    }
}
