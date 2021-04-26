<?php

declare(strict_types=1);

namespace CommissionTask\Tests\Service;

use Carbon\Carbon;
use CommissionTask\App\Models\Transaction;
use CommissionTask\App\Models\TransactionBasket;
use CommissionTask\App\Rules\DepositRule;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class DepositRuleTest extends TestCase
{
    /**
     * @var DepositRule
     */
    private $depositRule;

    public function setUp()
    {
        $commission        = 0.005;
        $this->depositRule = new DepositRule($commission);
    }

    /**
     * @param int $amount
     * @param string $userType
     * @param string $operationType
     * @param string $expectation
     *
     * @dataProvider dataProviderForAddTesting
     */
    public function testCanApply(int $amount, string $userType, string $operationType, string $expectation)
    {
        $amount            = new Money($amount, new Currency("EUR"));
        $transaction       = new Transaction(new Carbon("2021-04-26"), 1, $userType, $operationType, $amount);
        $transactionBasket = new TransactionBasket();
        $this->assertEquals(
            $expectation,
            $this->depositRule->canApply($transactionBasket, $transaction)
        );
    }

    public function dataProviderForAddTesting(): array
    {
        return [
            'private withdraw 1000 EUR' => [1000, 'private', 'withdraw', false],
            'private deposit 1000 EUR' => [1000, 'private', 'deposit', true],
            'business withdraw 1000 EUR' => [1000, 'business', 'withdraw', false],
            'business deposit 1000 EUR' => [1000, 'business', 'deposit', true],
        ];
    }
}
