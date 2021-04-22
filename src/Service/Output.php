<?php

namespace CommissionTask\Service;

use Money\Money;

interface Output
{
    public function output(Money $data);
}