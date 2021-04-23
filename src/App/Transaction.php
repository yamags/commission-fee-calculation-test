<?php
declare(strict_types=1);

namespace CommissionTask\App;

use Carbon\Carbon;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class Transaction
{
    /**
     * @var Carbon
     */
    private $date;
    /**
     * @var integer
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

    public function __construct($line)
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        $this->date          = Carbon::createFromFormat('Y-m-d', $line[0]);
        $this->userId        = (int)$line[1];
        $this->userType      = $line[2];
        $this->operationType = $line[3];
        $this->amount        = $moneyParser->parse($line[4], new Currency($line[5]));;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @param Carbon $date
     */
    public function setDate(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * @param string $userType
     */
    public function setUserType(string $userType)
    {
        $this->userType = $userType;
    }

    /**
     * @return string
     */
    public function getOperationType(): string
    {
        return $this->operationType;
    }

    /**
     * @param string $operationType
     */
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

    /**
     * @param Money $amount
     */
    public function setAmount(Money $amount)
    {
        $this->amount = $amount;
    }

}
