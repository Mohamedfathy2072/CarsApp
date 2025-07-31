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
}
