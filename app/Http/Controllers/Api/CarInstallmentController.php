<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CarInstallmentCalculator;
use Illuminate\Http\Request;

class CarInstallmentController extends BaseController
{
    public function calculateInstallment(Request $request)
    {
        $request->validate([
            'car_price' => 'required|numeric',
            'down_payment' => 'required|numeric',
            'installment_years' => 'required|integer',
            'months' => 'required|integer'
        ]);

        $calculator = new CarInstallmentCalculator(
            $request->car_price,
            $request->down_payment,
            $request->installment_years
        );

        $result = $calculator->calculate($request->months);

        return response()->json($result);
    }
}
