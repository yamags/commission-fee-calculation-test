<?php
declare(strict_types=1);

namespace CommissionTask\App;

use CommissionTask\Service\ExchangeRates;
use Money\Currency;
use Money\Money;

class TransactionBasket
{
    protected $transactionsByUserAndType = [];

    public function clear(): void
    {
        $this->transactionsByUserAndType = [];
    }

    public function add(Transaction $transaction): void
    {
        $this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType()][] = $transaction;
    }

    public function getAmountSum(Transaction $transaction): Money
    {
        $sum = new Money(0, new Currency('EUR'));

        if ( ! isset(
                $this->transactionsByUserAndType[$transaction->getUserId()]
            ) || ! isset(
                $this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType()]
            )) {
            return $sum;
        }

        foreach ($this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType(
        )] as $savedTransaction) {
            /** @var Transaction $savedTransaction */
            $converted = ExchangeRates::getRates()->convert($savedTransaction->getAmount(), $sum->getCurrency());
            $sum       = $sum->add($converted);
        }

        return $sum;
    }

    public function getCount(Transaction $transaction): float
    {
        if ( ! isset(
                $this->transactionsByUserAndType[$transaction->getUserId()]
            ) || ! isset(
                $this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType()]
            )) {
            return 0;
        }

        return count($this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType()]);
    }
}