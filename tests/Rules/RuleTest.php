<?php
declare(strict_types=1);

namespace CommissionTask\Tests\Rules;

use CommissionTask\App\Rules\WithdrawPrivateAmountGreaterXPerWeekFreeRule;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;
use Money\Money;
use PHPUnit\Framework\TestCase;

abstract class RuleTest extends TestCase
{
    /**
     * @var Converter
     */
    protected $converter;

    public function setUp()
    {
        $exchange = new ReversedCurrenciesExchange(
            new FixedExchange(
                [
                    'EUR' => [
                        'EUR' => 1,
                        'JPY' => 129.53,
                        'USD ' => 1.1497,
                    ],
                ]
            )
        );

        $this->converter = new Converter(new ISOCurrencies(), $exchange);
    }
}