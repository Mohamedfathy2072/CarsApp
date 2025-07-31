<?php

namespace App\Helpers;
class CarInstallmentCalculator
{
    protected float $carPrice;
    protected float $downPayment;
    protected int $installmentYears;
    protected float $annualInterestRate;

    public function __construct(
        float $carPrice,
        float $downPayment,
        int $installmentYears,
        float $annualInterestRate = 15
    ) {
        $this->carPrice = $carPrice;
        $this->downPayment = $downPayment;
        $this->installmentYears = $installmentYears;
        $this->annualInterestRate = $annualInterestRate;
    }

    public function calculate(): array
    {
        $principal = $this->carPrice - $this->downPayment;

        $totalInterestRate = $this->installmentYears * $this->annualInterestRate;

        $interestAmount = $principal * ($totalInterestRate / 100);

        $totalPayable = $principal + $interestAmount;

        $totalMonths = $this->installmentYears * 12;

        $monthlyInstallment = round($totalPayable / $totalMonths, 2);

        return [
            'principal' => $principal,
            'interest_amount' => $interestAmount,
            'total_payable' => $totalPayable,
            'monthly_installment' => $monthlyInstallment,
            'total_months' => $totalMonths
        ];
    }
}
