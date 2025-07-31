<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CarInstallmentCalculator;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFinancingRequest;
use App\Models\FinancingRequest;
use Illuminate\Http\Request;

class FinancingRequestController extends Controller
{

    public function store(StoreFinancingRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['card_front'] = $request->file('card_front')->store('cards', 'public');
        $data['card_back'] = $request->file('card_back')->store('cards', 'public');
        if ($request->hasFile('club_membership_card')) {
            $data['club_membership_card'] = $request->file('club_membership_card')->store('documents', 'public');
        }
        if ($request->hasFile('medical_insurance_card')) {
            $data['medical_insurance_card'] = $request->file('medical_insurance_card')->store('documents', 'public');
        }
        if ($request->hasFile('owned_car_license_front')) {
            $data['owned_car_license_front'] = $request->file('owned_car_license_front')->store('documents', 'public');
        }
        if ($request->hasFile('owned_car_license_back')) {
            $data['owned_car_license_back'] = $request->file('owned_car_license_back')->store('documents', 'public');
        }

        $financing = FinancingRequest::create($data);
        return response()->json($financing, 201);
    }

    public function calculateInstallment(Request $request) {

        $price = $request->input('price', 0);
        $downPayment = $request->input('down_payment', 0);
        $months = $request->input('months', 0);
        $interestRate = $request->input('interest_rate', 0);

        if ($price <= 0 || $downPayment < 0 || $months <= 0 || $interestRate < 0) {
            return response()->json(['error' => 'Invalid input values'], 400);
        }

        $carInstallmentCalculator = new CarInstallmentCalculator(
            $price,
            $downPayment,
            $months,
            $interestRate
        );
        $monthlyPayment = $carInstallmentCalculator->calculate();

        return response()->json($monthlyPayment);
    }
}
