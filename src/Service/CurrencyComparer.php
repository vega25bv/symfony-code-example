<?php

namespace App\Service;

use App\Service\Interface\RatesServiceInterface;

class CurrencyComparer
{
    public function compare(RatesServiceInterface $BankRates, RatesServiceInterface $localRates): array
    {
        $changes = [];
        $localRates = $localRates->getRates();
        foreach ($BankRates->getRates() as $currency => $rate) {
            if (isset($localRates[$currency]) && $rate !== $localRates[$currency]) {
                $changes[$currency] = $rate > $localRates[$currency] ? 'increased' : 'decreased';
            }
        }
        return $changes;
    }
}