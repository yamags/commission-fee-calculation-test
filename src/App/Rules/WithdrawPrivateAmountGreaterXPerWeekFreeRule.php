<?php
declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\Transaction;
use CommissionTask\App\TransactionBasket;
use CommissionTask\Service\ExchangeRates;
use Money\Money;

class WithdrawPrivateAmountGreaterXPerWeekFreeRule extends WithdrawPrivateRule
{
    private $amountPerWeek = 0;

    public function __construct(Money $amountPerWeek, float $commission)
    {
        $this->amountPerWeek = $amountPerWeek;
        parent::__construct($commission);
    }

    public function canApply(TransactionBasket $basket, Transaction $transaction): bool
    {
        return parent::canApply($basket, $transaction) && $basket->getAmountSum($transaction)->greaterThan(
                $this->amountPerWeek
            );
    }

    public function calculateFee(TransactionBasket $basket, Transaction $transaction): Money
    {
        $delta = $basket->getAmountSum($transaction)->subtract($this->amountPerWeek);
        $delta = ExchangeRates::getRates()->convert($delta, $transaction->getAmount()->getCurrency());
        if ($delta->greaterThan($transaction->getAmount())) {
            $delta = $transaction->getAmount();
        }

        return $delta->multiply($this->getCommission());
    }
}