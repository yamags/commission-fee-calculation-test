<?php

declare(strict_types=1);

namespace CommissionTask\Service;

use CommissionTask\App\Config\Configuration;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ExchangeRates
{
    public static function getRatesConverter()
    {
        $cache = new FilesystemAdapter();
        $exchangeRates = $cache->get(
            'exchangeratesapi_cache',
            function (ItemInterface $item) {
                $item->expiresAfter(86400);
                $endpoint = 'latest';
                $accessKey = Configuration::getInstance()->get('EXCHANGE_RATES_API_KEY');
                $url = Configuration::getInstance()->get('EXCHANGE_RATES_API_URL');

                $ch = curl_init($url.$endpoint.'?access_key='.$accessKey.'');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $json = curl_exec($ch);
                curl_close($ch);

                $exchangeRates = json_decode($json, true);

                return $exchangeRates['rates'];
            }
        );

        $exchange = new ReversedCurrenciesExchange(
            new FixedExchange(
                [
                    'EUR' => $exchangeRates,
                ]
            )
        );

        $converter = new Converter(new ISOCurrencies(), $exchange);

        return $converter;
    }
}
