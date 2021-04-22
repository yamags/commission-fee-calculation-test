<?php
declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\Transaction;
use CommissionTask\App\TransactionBasket;
use CommissionTask\App\TransactionRule;

abstract class WithdrawRule implements TransactionRule
{
    const OPERATION_TYPE = 'withdraw';

    public function canApply(TransactionBasket $basket, Transaction $transaction): bool
    {
        return $transaction->getOperationType() == self::OPERATION_TYPE;
    }
}