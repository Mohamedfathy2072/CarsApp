<?php

namespace App\Helpers;

class CarInstallmentCalculator
{
    private $carPrice;
    private $downPayment;
    private $installmentYears;
    private $annualInterestRate = 15.5; // 17.5% annual interest

    public function __construct($carPrice, $downPayment, $installmentYears)
    {
        $this->carPrice = $this->convertToNumber($carPrice);
        $this->downPayment = $this->convertToNumber($downPayment);
        $this->installmentYears = $installmentYears;
    }

    public function calculate($months)
    {
        $financingAmount = $this->carPrice - $this->downPayment;

        // Calculate total interest
        $totalInterestPercentage = $this->installmentYears * $this->annualInterestRate;
        $totalInterestAmount = $financingAmount * ($totalInterestPercentage / 100);

        // Total amount to be paid (interest + principal)
        $totalAmount = $financingAmount + $totalInterestAmount;

        // Monthly installment
        $monthlyInstallment = $totalAmount / $months;

        return [
            'car_price' => $this->carPrice,
            'down_payment' => $this->downPayment,
            'financing_amount' => $financingAmount,
            'installment_years' => $this->installmentYears,
            'annual_interest_rate' => $this->annualInterestRate,
            'total_interest_percentage' => $totalInterestPercentage,
            'total_interest_amount' => $totalInterestAmount,
            'total_amount' => $totalAmount,
            'monthly_installment' => $monthlyInstallment,
            'months' => $months
        ];
    }

    private function convertToNumber($value)
    {
        if (is_string($value)) {
            // Remove commas and any other non-numeric characters (except decimal point)
            return (float) preg_replace('/[^0-9.]/', '', $value);
        }
        return $value;
    }
}
