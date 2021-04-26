<?php

declare(strict_types=1);

namespace CommissionTask\Service\IOHelpers;

interface InputByLine
{
    public function getLine(): iterable;
}
