<?php

declare(strict_types=1);

namespace CommissionTask\Service;

use CommissionTask\App\Config\Configuration;
use CommissionTask\App\Factory\TransactionFactory;
use CommissionTask\App\Models\Transaction;
use CommissionTask\App\Models\TransactionBasket;
use CommissionTask\App\Rules\DepositRule;
use CommissionTask\App\Rules\TransactionRule;
use CommissionTask\App\Rules\WithdrawBusinessRule;
use CommissionTask\App\Rules\WithdrawPrivateAmountGreaterXPerWeekFreeRule;
use CommissionTask\App\Rules\WithdrawPrivateAmountLessXPerWeekFreeRule;
use CommissionTask\App\Rules\WithdrawPrivateMoreXTransactionsPerWeekRule;
use CommissionTask\Service\IOHelpers\InputByLine;
use CommissionTask\Service\IOHelpers\Output;
use Money\Currency;
use Money\Money;

class TransactionsProcessor
{
    /**
     * @var InputByLine
     */
    protected $input;
    /**
     * @var Output
     */
    protected $output;
    protected $rules;

    public function __construct(InputByLine $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;
        $freeFeeLimitCents = Configuration::getInstance()->get('FREE_FEE_LIMIT_CENTS');
        $baseCurrency = Configuration::getInstance()->get('BASE_CURRENCY');
        $this->rules = [
            new DepositRule((float) Configuration::getInstance()->get('RULE_DEPOSIT_FEE')),
            new WithdrawBusinessRule((float) Configuration::getInstance()->get('RULE_WITHDRAW_BUSINESS_FEE')),
            new WithdrawPrivateMoreXTransactionsPerWeekRule(
                (int) Configuration::getInstance()->get('FREE_FEE_NUMBER_PER_WEEK'),
                (float) Configuration::getInstance()->get('RULE_WITHDRAW_PRIVATE_FEE')
            ),
            new WithdrawPrivateAmountLessXPerWeekFreeRule(new Money($freeFeeLimitCents, new Currency($baseCurrency))),
            new WithdrawPrivateAmountGreaterXPerWeekFreeRule(
                new Money($freeFeeLimitCents, new Currency($baseCurrency)),
                (float) Configuration::getInstance()->get('RULE_WITHDRAW_PRIVATE_FEE')
            ),
        ];
    }

    private function getLastAplayebleRule(TransactionBasket $basket, Transaction $transaction): ?TransactionRule
    {
        $apply = null;
        foreach ($this->rules as $rule) {
            if ($rule->canApply($basket, $transaction)) {
                $apply = $rule;
            }
        }

        return $apply;
    }

    public function process()
    {
        $firstDayOfProcessedWeek = null;
        $transactionBasket = new TransactionBasket();
        foreach ($this->input->getLine() as $line) {
            $transaction = TransactionFactory::createFromCSVLine($line);
            if (!(isset($firstDayOfProcessedWeek) && $transaction->getDate()->isSameWeek($firstDayOfProcessedWeek))) {
                $transactionBasket->clear();
                $firstDayOfProcessedWeek = $transaction->getDate();
            }
            $transactionBasket->add($transaction);
            $transactionRule = $this->getLastAplayebleRule($transactionBasket, $transaction);
            $commission = $transactionRule->calculateFee($transactionBasket, $transaction);
            $this->output->output($commission);
        }
    }
}
