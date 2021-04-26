<?php

declare(strict_types=1);

namespace CommissionTask\Tests\Rules;

use Carbon\Carbon;
use CommissionTask\App\Models\Transaction;
use CommissionTask\App\Models\TransactionBasket;
use CommissionTask\App\Rules\WithdrawBusinessRule;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class WithdrawBusinessRuleTest extends TestCase
{
    /**
     * @var WithdrawBusinessRule
     */
    private $depositRule;

    public function setUp()
    {
        $commission        = 0.005;
        $this->depositRule = new WithdrawBusinessRule($commission);
    }

    /**
     * @param int $amount
     * @param string $userType
     * @param string $operationType
     * @param bool $expectation
     *
     * @dataProvider dataProviderForCanApply
     */
    public function testCanApply(int $amount, string $userType, string $operationType, bool $expectation)
    {
        $amount            = new Money($amount, new Currency("EUR"));
        $transaction       = new Transaction(new Carbon("2021-04-26"), 1, $userType, $operationType, $amount);
        $transactionBasket = new TransactionBasket("EUR");
        $transactionBasket->add($transaction);
        $this->assertEquals(
            $expectation,
            $this->depositRule->canApply($transactionBasket, $transaction)
        );
    }

    public function dataProviderForCanApply(): array
    {
        return [
            'private withdraw 10 EUR' => [1000, 'private', 'withdraw', false],
            'private deposit 10 EUR' => [1000, 'private', 'deposit', false],
            'business withdraw 10 EUR' => [1000, 'business', 'withdraw', true],
            'business deposit 10 EUR' => [1000, 'business', 'deposit', false],
            'business withdraw 0 EUR' => [0, 'business', 'withdraw', true],
            'business withdraw 1000 EUR' => [100000, 'business', 'withdraw', true],
        ];
    }

    /**
     * @param int $amount
     * @param string $userType
     * @param string $operationType
     * @param int $amountAfterCommission
     * @param bool $expectation
     *
     * @dataProvider dataProviderForFeeValue
     */
    public function testFeeValue(
        int $amount,
        string $userType,
        string $operationType,
        int $amountAfterCommission,
        bool $expectation
    ) {
        $amount                = new Money($amount, new Currency("EUR"));
        $amountAfterCommission = new Money($amountAfterCommission, new Currency("EUR"));
        $transaction           = new Transaction(new Carbon("2021-04-26"), 1, $userType, $operationType, $amount);
        $transactionBasket     = new TransactionBasket("EUR");
        $transactionBasket->add($transaction);
        $this->assertEquals(
            $expectation,
            $this->depositRule->calculateFee($transactionBasket, $transaction)->equals($amountAfterCommission)
        );
    }

    public function dataProviderForFeeValue(): array
    {
        return [
            'business withdraw 0 EUR' => [0, 'business', 'withdraw', 0, true],
            'business withdraw 10 EUR' => [1000, 'business', 'withdraw', 5, true],
            'business withdraw 1000 EUR' => [100000, 'business', 'withdraw', 500, true],
        ];
    }

}
