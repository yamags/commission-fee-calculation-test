<?php

declare(strict_types=1);

namespace CommissionTask\App\Factory;

use Carbon\Carbon;
use CommissionTask\App\Models\Transaction;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Parser\DecimalMoneyParser;

class TransactionFactory
{
    public static function createFromCSVLine($line)
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        $date = Carbon::createFromFormat('Y-m-d', $line[0]);
        $userId = (int) $line[1];
        $userType = $line[2];
        $operationType = $line[3];
        $amount = $moneyParser->parse($line[4], new Currency($line[5]));

        return new Transaction($date, $userId, $userType, $operationType, $amount);
    }
}
