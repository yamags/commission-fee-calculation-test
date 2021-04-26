<?php

declare(strict_types=1);

namespace CommissionTask\App\Models;

use Carbon\Carbon;
use Money\Money;

class Transaction
{
    /**
     * @var Carbon
     */
    private $date;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $userType;
    /**
     * @var string
     */
    private $operationType;
    /**
     * @var Money
     */
    private $amount;

    public function __construct(Carbon $date, int $userId, string $userType, string $operationType, Money $amount)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->operationType = $operationType;
        $this->amount = $amount;
    }

    public function getDate(): Carbon
    {
        return $this->date;
    }

    public function setDate(Carbon $date)
    {
        $this->date = $date;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType)
    {
        $this->userType = $userType;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function setOperationType(string $operationType)
    {
        $this->operationType = $operationType;
    }

    /**
     * @return float
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(Money $amount)
    {
        $this->amount = $amount;
    }
}
