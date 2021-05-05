<?php

declare(strict_types=1);

namespace CommissionTask\Service\IOHelpers;

use Money\Money;

/**
 * Interface Output.
 */
interface Output
{
    /**
     * @return mixed
     */
    public function output(Money $data);
}
