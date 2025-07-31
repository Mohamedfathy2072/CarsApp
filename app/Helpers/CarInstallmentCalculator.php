<?php

namespace App\Helpers;
class CarInstallmentCalculator
{
    protected float $carPrice;
    protected float $downPayment;
    protected int $installmentMonths;
    protected float $annualInterestRate;

    public function __construct(
        float $carPrice,
        float $downPayment,
        int $installmentMonths,
        float $annualInterestRate = 15
    ) {
        $this->carPrice = $carPrice;
        $this->downPayment = $downPayment;
        $this->installmentMonths = $installmentMonths;
        $this->annualInterestRate = $annualInterestRate;
    }

    public function calculate(): array
    {
        $principal = $this->carPrice - $this->downPayment;

        $totalInterestRate = ($this->installmentMonths / 12) * $this->annualInterestRate;

        $interestAmount = $principal * ($totalInterestRate / 100);

        $totalPayable = $principal + $interestAmount;

        $monthlyInstallment = round($totalPayable / $this->installmentMonths, 2);

        return [
            'principal' => $principal,
            'interest_amount' => $interestAmount,
            'total_payable' => $totalPayable,
            'monthly_installment' => $monthlyInstallment,
            'total_months' => $this->installmentMonths
        ];
    }
}
