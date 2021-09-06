<?php

namespace RicardoKovalski\Installments\Console\Util;

use RicardoKovalski\InstallmentsCalculator\Contracts\MonetaryFormatterContract;
use RicardoKovalski\InstallmentsCalculator\Installment;
use RicardoKovalski\InstallmentsCalculator\InstallmentFormatter;
use Symfony\Component\Console\Helper\Table;

class InstallmentsOutput
{
    private $monetaryFormatter;

    private $installmentFormatter;

    public function __construct()
    {
        $this->monetaryFormatter = null;
        $this->installmentFormatter = null;
    }

    public function makeMonetaryFormatter(MonetaryFormatterContract $monetaryFormatter)
    {
        $this->monetaryFormatter = $monetaryFormatter;
        return $this;
    }

    public function makeInstallmentFormatter(InstallmentFormatter $installmentFormatter)
    {
        $this->installmentFormatter = $installmentFormatter;
        return $this;
    }

    /**
     * @param Table $table
     * @param $collectionInstallments
     */
    public function write(Table $table, $collectionInstallments)
    {
        if (! $this->installmentFormatter) {
            $table->addRows(
                array(
                    array('valueInstallment', 'numberInstallment', 'interestValue', 'totalInterest'),
                    array('', '', '', ''),
                )
            );
        }

        foreach ($collectionInstallments as $installment) {
            $table->addRows($this->buildRows($installment));
        }
    }

    public function buildRows(Installment $installment)
    {
        if ($this->monetaryFormatter) {
            return array(
                array(
                    $this->monetaryFormatter->format($installment->getValueInstallment()),
                    $installment->getNumberInstallment(),
                    $this->monetaryFormatter->format($installment->getInterestValue()),
                    $this->monetaryFormatter->format($installment->getTotalInterest())
                )
            );
        }

        if ($this->installmentFormatter) {
            return array(
                array(
                    $this->installmentFormatter->format($installment)
                )
            );
        }

        return array(
            array(
                $installment->getValueInstallment(),
                $installment->getNumberInstallment(),
                $installment->getInterestValue(),
                $installment->getTotalInterest()
            )
        );
    }
}
