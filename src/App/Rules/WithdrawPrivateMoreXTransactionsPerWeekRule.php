<?php
declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\Transaction;
use CommissionTask\App\TransactionBasket;
use Money\Money;

class WithdrawPrivateMoreXTransactionsPerWeekRule extends WithdrawPrivateRule
{
    /** @var int */
    private $numberPerWeek = 0;

    public function __construct(int $numberPerWeek, float $commission)
    {
        $this->numberPerWeek = $numberPerWeek;
        parent::__construct($commission);
    }

    public function canApply(TransactionBasket $basket, Transaction $transaction): bool
    {
        return parent::canApply($basket, $transaction) && $basket->getCount($transaction) > $this->numberPerWeek;
    }

    public function calculateFee(TransactionBasket $basket, Transaction $transaction): Money
    {
        return $transaction->getAmount()->multiply($this->getCommission());
    }
}