<?php

declare(strict_types=1);

namespace CommissionTask\App\Rules;

abstract class CommissionRule implements TransactionRule
{
    /** @var float */
    private $commission;

    public function __construct(float $commission)
    {
        $this->commission = $commission;
    }

    public function getCommission(): float
    {
        return $this->commission;
    }
}
