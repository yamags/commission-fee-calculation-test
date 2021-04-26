<?php

declare(strict_types=1);

namespace CommissionTask\Service\IOHelpers;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class CommissionsOutput implements Output
{
    public function output(Money $data)
    {
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        echo $moneyFormatter->format($data)."\n";
    }
}
