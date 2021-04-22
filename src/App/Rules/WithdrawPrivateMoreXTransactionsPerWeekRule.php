<?php
declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\Transaction;
use CommissionTask\App\TransactionBasket;
use CommissionTask\App\TransactionRule;
use Money\Money;

class WithdrawPrivateMoreXTransactionsPerWeekRule extends WithdrawPrivateRule
{
    private $numberPerWeek = 0;

    public function __construct($numberPerWeek)
    {
        $this->numberPerWeek = $numberPerWeek;
    }

    public function canApply(TransactionBasket $basket, Transaction $transaction): bool
    {
        return parent::canApply($basket, $transaction) && $basket->getCount($transaction) > $this->numberPerWeek;
    }

    public function calculateFee(TransactionBasket $basket, Transaction $transaction): Money
    {
        return $transaction->getAmount()->multiply(0.3/100);
    }
}