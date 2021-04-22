<?php

namespace CommissionTask\App;

use Money\Money;

interface TransactionRule
{
    public function canApply(TransactionBasket $basket, Transaction $transaction): bool;

    public function calculateFee(TransactionBasket $basket, Transaction $transaction): Money;
}