<?php

declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\Models\Transaction;
use CommissionTask\App\Models\TransactionBasket;
use Money\Money;

interface TransactionRule
{
    public function canApply(TransactionBasket $basket, Transaction $transaction): bool;

    public function calculateFee(TransactionBasket $basket, Transaction $transaction): Money;
}
