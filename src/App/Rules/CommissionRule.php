<?php
declare(strict_types=1);

namespace CommissionTask\App\Rules;

use CommissionTask\App\TransactionRule;

abstract class CommissionRule implements TransactionRule
{
    /** @var float */
    private $commission;

    public function __construct($commission)
    {
        $this->commission = $commission;
    }

    /**
     * @return float
     */
    public function getCommission(): float
    {
        return $this->commission;
    }
}