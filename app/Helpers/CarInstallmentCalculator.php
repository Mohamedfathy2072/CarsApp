<?php

namespace App\Helpers;

class CarInstallmentCalculator
{
    protected float $carPrice;
    protected float $downPayment;
    protected float $annualInterest = 17.5;

    public function __construct(float $carPrice, float $downPayment)
    {
        $this->carPrice = $carPrice;
        $this->downPayment = $downPayment;
    }

    public function calculate(): array
    {
        $loanAmount = $this->carPrice - $this->downPayment;

        $installments = [];

        foreach ([1, 3, 5] as $years) {
            $months = $years * 12;
            $interestPercent = $years * $this->annualInterest;
            $interestAmount = ($loanAmount * $interestPercent) / 100;
            $totalToPay = $loanAmount + $interestAmount;
            $monthlyInstallment = $totalToPay / $months;

            $installments[$years . '_years'] = [
                'years' => $years,
                'months' => $months,
                'interest_percent' => $interestPercent,
                'interest_amount' => round($interestAmount, 2),
                'total_to_pay' => round($totalToPay, 2),
                'monthly_installment' => round($monthlyInstallment, 2),
            ];
        }

        return [
            'car_price' => $this->carPrice,
            'down_payment' => $this->downPayment,
            'loan_amount' => $loanAmount,
            'installment_plans' => $installments,
        ];
    }
}
