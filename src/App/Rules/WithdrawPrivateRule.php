<?php
declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\Transaction;
use CommissionTask\App\TransactionBasket;

abstract class WithdrawPrivateRule extends WithdrawRule
{
    const USER_TYPE = 'private';

    public function canApply(TransactionBasket $basket, Transaction $transaction): bool
    {
        return parent::canApply($basket, $transaction) && $transaction->getUserType() == self::USER_TYPE;
    }
}