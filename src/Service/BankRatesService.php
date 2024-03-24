<?php
namespace App\Service;

use App\Service\Interface\RatesServiceInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BankRatesService implements RatesServiceInterface
{
    private $httpClient;
    private $url;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function setBankRatesUrls($url):void
    {
        $this->url = $url;
    }

    public function getRates(): array
    {
        $response = $this->httpClient->request('GET', $this->url);
        return $response->toArray();
    }
}
