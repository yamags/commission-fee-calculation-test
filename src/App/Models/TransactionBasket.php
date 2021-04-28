<?php

declare(strict_types=1);

namespace CommissionTask\App\Models;

use Money\Converter;
use Money\Currency;
use Money\Money;

class TransactionBasket
{
    protected $transactionsByUserAndType = [];
    protected $baseCurrency;

    /** @var Converter */
    protected $converter;

    public function __construct(string $baseCurrency, Converter $converter)
    {
        $this->baseCurrency = $baseCurrency;
        $this->converter = $converter;
    }

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
        $sum = new Money(0, new Currency($this->baseCurrency));

        if (!isset(
                $this->transactionsByUserAndType[$transaction->getUserId()]
            ) || !isset(
                $this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType()]
            )) {
            return $sum;
        }

        foreach ($this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType(
        )] as $savedTransaction) {
            /** @var Transaction $savedTransaction */
            $converted = $this->converter->convert(
                $savedTransaction->getAmount(),
                $sum->getCurrency()
            );
            $sum = $sum->add($converted);
        }

        return $sum;
    }

    public function getCount(Transaction $transaction): float
    {
        if (!isset(
                $this->transactionsByUserAndType[$transaction->getUserId()]
            ) || !isset(
                $this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType()]
            )) {
            return 0;
        }

        return count($this->transactionsByUserAndType[$transaction->getUserId()][$transaction->getOperationType()]);
    }

    public function getConverter(): Converter
    {
        return $this->converter;
    }
}
