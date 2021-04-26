<?php

declare(strict_types=1);

namespace CommissionTask\Service\IOHelpers;

use Money\Money;

interface Output
{
    public function output(Money $data);
}
