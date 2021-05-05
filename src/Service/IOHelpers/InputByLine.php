<?php

declare(strict_types=1);

namespace CommissionTask\Service\IOHelpers;

/**
 * Interface InputByLine.
 */
interface InputByLine
{
    public function getLine(): iterable;
}
