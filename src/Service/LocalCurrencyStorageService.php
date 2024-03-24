<?php

namespace App\Service;

use App\Service\Interface\RatesServiceInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class LocalCurrencyStorageService implements RatesServiceInterface
{
    private $storageFile;

    public function __construct(KernelInterface $kernel)
    {
        $this->storageFile = $kernel->getProjectDir().DIRECTORY_SEPARATOR.'currency_rates.json';
    }

    public function getRates(): array
    {
        if (!file_exists($this->storageFile)) {
            return [];
        }

        return json_decode(file_get_contents($this->storageFile), true);
    }
}