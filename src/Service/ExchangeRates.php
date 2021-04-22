<?php
declare(strict_types=1);

namespace CommissionTask\Service;

use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ExchangeRates
{

    public static function getRates()
    {
        $cache = new FilesystemAdapter();
        $exchangeRates = $cache->get('exchangeratesapi_cache', function (ItemInterface $item) {
            $item->expiresAfter(86400);
            $endpoint   = 'latest';
            $access_key = '00d941802a232fe9ed120f1ba30493bc';

            $ch = curl_init('http://api.exchangeratesapi.io/v1/'.$endpoint.'?access_key='.$access_key.'');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $json = curl_exec($ch);
            curl_close($ch);

            $exchangeRates = json_decode($json, true);
            return $exchangeRates['rates'];
        });

        $exchange = new ReversedCurrenciesExchange(new FixedExchange([
            'EUR' => $exchangeRates
        ]));

        $converter = new Converter(new ISOCurrencies(), $exchange);

        return $converter;
    }
}