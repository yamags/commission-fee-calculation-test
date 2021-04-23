<?php
declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\Transaction;
use CommissionTask\App\TransactionBasket;
use Money\Money;

class DepositRule extends CommissionRule
{
    const OPERATION_TYPE = 'deposit';

    public function canApply(TransactionBasket $basket, Transaction $transaction): bool
    {
        return $transaction->getOperationType() == self::OPERATION_TYPE;
    }

    public function calculateFee(TransactionBasket $basket, Transaction $transaction): Money
    {
        return $transaction->getAmount()->multiply($this->getCommission());
    }
}