<?php
declare(strict_types=1);

namespace CommissionTask\Service;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class CommissionsOutput implements Output
{

    public function output(Money $data)
    {

        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        echo $moneyFormatter->format($data)."\n"; // outputs 1.00
    }
}