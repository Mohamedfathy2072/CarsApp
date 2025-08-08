<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;


class QuizMatchController extends Controller
{
    public function match(Request $request)
{
    $userId = Auth::id();
    $answers = QuizAnswer::where('user_id', $userId)->pluck('value', 'attribute');

    // Step 1: Occupation logic
    $occupation = $answers['occupation'] ?? 'Employee';
    $dpPercentRange = match ($occupation) {
        'Employee', 'Business owner' => [0.10, 0.30],
        'Freelancer', 'Housewife', 'Unemployed' => [0.30, 0.40],
        default => [0.10, 0.30],
    };

    // Step 2: down_payment_range → average value
    $downPaymentRange = $answers['down_payment_range'] ?? '';
    $dpAvgValue = $this->parseAverageEGP($downPaymentRange);
    $expectedCarPriceMin = $dpAvgValue / $dpPercentRange[1];
    $expectedCarPriceMax = $dpAvgValue / $dpPercentRange[0];

    // Step 3: Monthly installment
    $monthlyInstallmentRange = $answers['monthly_installment_range'] ?? '';
    $maxInstallment = $this->parseAverageEGP($monthlyInstallmentRange);

    // Step 4: Income vs. existing installments
    $income = $this->parseAverageEGP($answers['income'] ?? '');
    $otherInstallments = is_numeric($answers['other_installments_value'] ?? null)
        ? (int) $answers['other_installments_value']
        : 0;

    $netIncome = $income - $otherInstallments;
    $installmentCap = $netIncome * 0.6;

    // Step 5: Filter matching cars
    $finalInstallmentLimit = min($maxInstallment, $installmentCap);

    $cars = Car::with([
        'images',
        'brand',
        'exteriorConditions',
        'interiorConditions',
        'mechanicalConditions'
    ])
    ->whereBetween('price', [$expectedCarPriceMin, $expectedCarPriceMax])
    ->whereRaw('price - down_payment <= ?', [$finalInstallmentLimit * 60])
    ->paginate(10);

    return response()->json([
        'status' => true,
        'message' => 'Cars fetched successfully.',
        'data' => [
            'items' => $cars->items(),
            'pagination' => [
                'current_page' => $cars->currentPage(),
                'per_page' => $cars->perPage(),
                'total' => $cars->total(),
                'last_page' => $cars->lastPage(),
            ]
        ]
    ]);
}


    private function parseAverageEGP($range)
    {
        if (!$range || $range === 'Not sure yet') return 0;

        $range = str_replace(['EGP', ',', 'More than', 'Less than'], '', $range);
        preg_match_all('/\d+/', $range, $matches);

        $numbers = $matches[0] ?? [];
        if (count($numbers) === 1) return (int) $numbers[0];
        if (count($numbers) === 2) return ((int)$numbers[0] + (int)$numbers[1]) / 2;

        return 0;
    }
}
