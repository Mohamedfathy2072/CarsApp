<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAnswerController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::id(); // أو أي user_id لو مش مستخدم auth

        foreach ($request->all() as $attribute => $value) {

            // ✅ تحويل الـ array إلى string
            if (is_array($value)) {
                $value = json_encode($value);
            }

            \App\Models\QuizAnswer::updateOrCreate(
                ['user_id' => $userId, 'attribute' => $attribute],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Answers saved successfully']);
    }
    public function match(Request $request)
    {
        $userId = Auth::id(); // أو استخدم $request->user_id;

        $answers = QuizAnswer::where('user_id', $userId)->pluck('value', 'attribute');

        // 1. نوع الوظيفة → نسبة المقدم المقبولة
        $occupation = $answers['occupation'] ?? 'Employee';
        $dpPercentRange = match ($occupation) {
            'Employee', 'Business owner' => [0.10, 0.30],
            'Freelancer', 'Housewife', 'Unemployed' => [0.30, 0.40],
            default => [0.10, 0.30],
        };

        // 2. تحويل مدى المقدم لمتوسط
        $downPaymentRange = $answers['down_payment_range'] ?? '';
        $dpAvgValue = $this->parseAverageEGP($downPaymentRange); // E.g. 350000

        // حساب مدى سعر العربية المناسب حسب النسبة
        $expectedCarPriceMin = $dpAvgValue / $dpPercentRange[1]; // أكبر مقدم → أقل سعر
        $expectedCarPriceMax = $dpAvgValue / $dpPercentRange[0]; // أصغر مقدم → أكبر سعر

        // 3. المدى المتوقع للقسط
        $monthlyInstallmentRange = $answers['monthly_installment_range'] ?? '';
        $maxInstallment = $this->parseAverageEGP($monthlyInstallmentRange);

        // 4. الدخل وصافي الدخل بعد الأقساط
        $income = $this->parseAverageEGP($answers['income'] ?? '');
        $otherInstallments = is_numeric($answers['other_installments_value'] ?? null)
            ? (int) $answers['other_installments_value']
            : 0;

        $netIncome = $income - $otherInstallments;
        $installmentCap = $netIncome * 0.6; // 60% من الصافي

        // القسط المسموح بيه فعليًا
        $finalInstallmentLimit = min($maxInstallment, $installmentCap);

        // 5. البحث عن العربيات
        $cars = Car::whereBetween('price', [$expectedCarPriceMin, $expectedCarPriceMax])
            ->whereRaw('price - down_payment <= ?', [$finalInstallmentLimit * 60]) // قسط على 60 شهر
            ->get();

        return response()->json([
            'matched_cars' => $cars,
            'filters' => [
                'expected_price_range' => [$expectedCarPriceMin, $expectedCarPriceMax],
                'installment_limit' => $finalInstallmentLimit,
            ],
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
