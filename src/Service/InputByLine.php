<?php

namespace CommissionTask\Service;

interface InputByLine
{
    public function getLine(): iterable;
}