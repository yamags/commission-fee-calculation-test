<?php

declare(strict_types=1);

namespace CommissionTask\Tests\Rules;

use Carbon\Carbon;
use CommissionTask\App\Models\Transaction;
use CommissionTask\App\Models\TransactionBasket;
use CommissionTask\App\Rules\WithdrawBusinessRule;
use CommissionTask\App\Rules\WithdrawPrivateAmountGreaterXPerWeekFreeRule;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class WithdrawPrivateAmountGreaterXPerWeekFreeRuleTest extends TestCase
{
    /**
     * @var WithdrawPrivateAmountGreaterXPerWeekFreeRule
     */
    private $depositRule;

    public function setUp()
    {
        $commission        = 0.003;
        $amountPerWeek     = 100000;
        $this->depositRule = new WithdrawPrivateAmountGreaterXPerWeekFreeRule(
            new Money($amountPerWeek, new Currency("EUR")), $commission
        );
    }

    /**
     * @param array $transactionsArray ,
     * @param bool $expectation
     *
     * @dataProvider dataProviderForCanApply
     */
    public function testCanApply(array $transactionsArray, bool $expectation)
    {
        $transactionBasket = new TransactionBasket("EUR");
        foreach ($transactionsArray as $transactionItem) {

            $amount      = new Money($transactionItem[0], new Currency($transactionItem[2]));
            $transaction = new Transaction(
                new Carbon($transactionItem[1]), $transactionItem[3], $transactionItem[4], $transactionItem[5], $amount
            );
            $transactionBasket->add($transaction);
        }
        $this->assertEquals(
            $expectation,
            $this->depositRule->canApply($transactionBasket, $transaction)
        );
    }

    public function dataProviderForCanApply(): array
    {
        return [
            'private withdraw 10 EUR' => [[[1000, '2021-04-26', 'EUR', 1, 'private', 'withdraw']], false],
            'private deposit 10 EUR' => [[[1000, '2021-04-26', 'EUR', 1, 'private', 'deposit']], false],
            'business withdraw 10 EUR' => [[[1000, '2021-04-26', 'EUR', 1, 'business', 'withdraw']], false],
            'business deposit 10 EUR' => [[[1000, '2021-04-26', 'EUR', 1, 'business', 'deposit']], false],
            'private withdraw 0 EUR' => [[[0, '2021-04-26', 'EUR', 1, 'private', 'withdraw']], false],
            'private withdraw 1000 EUR' => [[[100000, '2021-04-26', 'EUR', 1, 'private', 'withdraw']], false],
            'private withdraw 1000.01 EUR' => [[[1000001, '2021-04-26', 'EUR', 1, 'private', 'withdraw']], true],
            'private withdraw 500+600 EUR' => [
                [
                    [50000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                    [60000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                ],
                true,
            ],
            'private withdraw 300+300+300+300 EUR' => [
                [
                    [30000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                    [30000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                    [30000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                    [30000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                ],
                true,
            ],
            'private withdraw 00+30+30 EUR' => [
                [
                    [3000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                    [3000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                    [3000, '2021-04-26', 'EUR', 1, 'private', 'withdraw'],
                ],
                false,
            ],
        ];
    }

}
