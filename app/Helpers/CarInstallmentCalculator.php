<?php

namespace App\Helpers;

class CarInstallmentCalculator
{
    protected float $carPrice;
    protected float $downPayment;
    protected int $months;
    protected float $annualInterest = 17.5;

    public function __construct(float $carPrice, float $downPayment, int $months)
    {
        $this->carPrice = $carPrice;
        $this->downPayment = $downPayment;
        $this->months = $months;
    }

    public function calculate(): array
    {
        $loanAmount = $this->carPrice - $this->downPayment;
        $years = $this->months / 12;

        $interestPercent = $years * $this->annualInterest;
        $interestAmount = ($loanAmount * $interestPercent) / 100;

        $totalToPay = $loanAmount + $interestAmount;
        $monthlyInstallment = $totalToPay / $this->months;

        return [
            'car_price' => $this->carPrice,
            'down_payment' => $this->downPayment,
            'loan_amount' => $loanAmount,
            'interest_percent' => $interestPercent,
            'interest_amount' => $interestAmount,
            'total_to_pay' => $totalToPay,
            'monthly_installment' => round($monthlyInstallment, 2),
        ];
    }
}
