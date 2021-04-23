<?php
declare(strict_types=1);

namespace CommissionTask\App;

use CommissionTask\App\Rules\DepositRule;
use CommissionTask\App\Rules\WithdrawBusinessRule;
use CommissionTask\App\Rules\WithdrawPrivateAmountGreaterXPerWeekFreeRule;
use CommissionTask\App\Rules\WithdrawPrivateAmountLessXPerWeekFreeRule;
use CommissionTask\App\Rules\WithdrawPrivateMoreXTransactionsPerWeekRule;
use CommissionTask\Service\ExchangeRates;
use CommissionTask\Service\InputByLine;
use CommissionTask\Service\Output;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
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
        $this->input  = $input;
        $this->output = $output;
        $this->rules  = [
            new DepositRule(0.03 / 100),
            new WithdrawBusinessRule(0.5 / 100),
            new WithdrawPrivateMoreXTransactionsPerWeekRule(3, 0.3 / 100),
            new WithdrawPrivateAmountLessXPerWeekFreeRule(new Money(100000, new Currency('EUR'))),
            new WithdrawPrivateAmountGreaterXPerWeekFreeRule(new Money(100000, new Currency('EUR')), 0.3 / 100),
        ];
    }

    private function getLastAplyebleRule(TransactionBasket $basket, Transaction $transaction): ?TransactionRule
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
        $transactionBasket       = new TransactionBasket();
        foreach ($this->input->getLine() as $line) {

            $transaction = new Transaction($line);
            //$this->output->output($line);
            if ( ! (isset($firstDayOfProcessedWeek) && $transaction->getDate()->isSameWeek($firstDayOfProcessedWeek))) {
                $transactionBasket->clear();
                $firstDayOfProcessedWeek = $transaction->getDate();
            }
            $transactionBasket->add($transaction);
            $transactionRule = $this->getLastAplyebleRule($transactionBasket, $transaction);
            $commission      = $transactionRule->calculateFee($transactionBasket, $transaction);
            $this->output->output($commission);
        }
    }
}