<?php
declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\Transaction;
use CommissionTask\App\TransactionBasket;
use CommissionTask\App\TransactionRule;
use Money\Currency;
use Money\Money;

class WithdrawPrivateAmountLessXPerWeekFreeRule extends WithdrawPrivateRule
{
    private $amountPerWeek = 0;

    public function __construct(Money $amountPerWeek)
    {
        $this->amountPerWeek = $amountPerWeek;
    }

    public function canApply(TransactionBasket $basket, Transaction $transaction): bool
    {
        return parent::canApply($basket, $transaction) && $basket->getAmountSum($transaction)->lessThanOrEqual($this->amountPerWeek);
    }

    public function calculateFee(TransactionBasket $basket, Transaction $transaction): Money
    {
        return new Money(0, $transaction->getAmount()->getCurrency());
    }
}