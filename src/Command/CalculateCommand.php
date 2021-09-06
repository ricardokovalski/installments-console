<?php

namespace RicardoKovalski\Installments\Console\Command;

use Exception;
use RicardoKovalski\Installments\Console\Util\InstallmentsOutput;
use RicardoKovalski\InstallmentsCalculator\Adapters\InterestCalculation;
use RicardoKovalski\InstallmentsCalculator\Adapters\MonetaryFormatter;
use RicardoKovalski\InstallmentsCalculator\Adapters\MonetaryFormatterConfig;
use RicardoKovalski\InstallmentsCalculator\Enums\IsoCodes;
use RicardoKovalski\InstallmentsCalculator\Enums\Patterns;
use RicardoKovalski\InstallmentsCalculator\InstallmentCalculation;
use RicardoKovalski\InstallmentsCalculator\InstallmentCalculationConfig;
use RicardoKovalski\InstallmentsCalculator\InstallmentFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CalculateCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->setName('calculate')
            ->setDescription('Calculate installments')
            ->addArgument(
                'totalPurchase',
                InputArgument::REQUIRED,
                'Double supported type.'
            )
            ->addOption(
                'typeInterest',
                'ti',
                InputOption::VALUE_REQUIRED,
                'Supported type: Financial, Compound and Simple.',
                'Financial'
            )
            ->addOption(
                'interestValue',
                'iv',
                InputOption::VALUE_REQUIRED,
                'Double Supported type.',
                0.00
            )
            ->addOption(
                'limitValueInstallment',
                'lvi',
                InputOption::VALUE_OPTIONAL,
                'Use this option to reset the installment limit value. The default value is 5.00. Double supported type.'
            )
            ->addOption(
                'numberMaxInstallments',
                'nmi',
                InputOption::VALUE_OPTIONAL,
                'Use this option to reset number maximus installments. The default value is 12. Int supported type.'
            )
            ->addOption(
                'limitInstallments',
                'li',
                InputOption::VALUE_OPTIONAL,
                'Use this option to disable the installment limit. The default value is true (active). Boolean supported type.',
                true
            )
            ->addOption(
                'monetaryFormatterConfig',
                null,
                InputOption::VALUE_NONE,
                'Use this option to use object "MonetaryFormatterConfig".'
            )
            ->addOption(
                'currencyIsoCodes',
                'cic',
                InputOption::VALUE_REQUIRED,
                'Use this option to configure currency isoCodes. Supported type: "BRL" and "USD".',
                IsoCodes::BRL
            )
            ->addOption(
                'locale',
                'l',
                InputOption::VALUE_REQUIRED,
                'Use this option to configure locale. Supported type: "pt_BR" and "en_US".',
                'pt-br'
            )
            ->addOption(
                'fractionDigits',
                'fd',
                InputOption::VALUE_OPTIONAL,
                'Use this option to configure fraction digits. Int supported type.'
            )
            ->addOption(
                'monetaryFormatter',
                'mf',
                InputOption::VALUE_REQUIRED,
                'Use this option to configure method formatter. Supported type: "IntlCurrency", "IntlDecimal" and "Decimal".'
            )
            ->addOption(
                'installmentFormatter',
                'if',
                InputOption::VALUE_NONE,
                'Use this option to use object "InstallmentFormatter".'
            )
            ->addOption(
                'pattern',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Use this option to reset pattern used object "InstallmentFormatter".'
            );
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installmentCalculationConfig = $this->buildInstallmentCalculationConfig($input);

        $totalPurchase = filter_Var($input->getArgument('totalPurchase'), FILTER_VALIDATE_FLOAT);

        $installmentCalculation = new InstallmentCalculation($installmentCalculationConfig);
        $installmentCalculation->appendTotalPurchase($totalPurchase);
        $installmentCalculation->calculate();

        $table = $this->createTable($output);
        $installmentOutput = new InstallmentsOutput();

        if (((bool) $input->getOption('monetaryFormatterConfig')) === false) {
            $installmentOutput->write($table, $installmentCalculation->getCollection());

            $table->render();
            return;
        }

        if (((bool) $input->getOption('installmentFormatter')) === true) {
            $installmentOutput
                ->makeInstallmentFormatter($this->buildInstallmentFormatter($input))
                ->write($table, $installmentCalculation->getCollection());

            $table->render();
            return;
        }

        $installmentOutput
            ->makeMonetaryFormatter($this->buildMonetaryFormatter($input))
            ->write($table, $installmentCalculation->getCollection());

        $table->render();
    }

    /**
     * @param InputInterface $input
     * @return InstallmentCalculationConfig
     */
    protected function buildInstallmentCalculationConfig(InputInterface $input)
    {
        $typeInterest = ucfirst(strtolower($input->getOption('typeInterest')));

        $interestValue = filter_var($input->getOption('interestValue'), FILTER_VALIDATE_FLOAT);

        $limitValueInstallments = filter_var($input->getOption('limitValueInstallment'), FILTER_VALIDATE_FLOAT);

        $numberMaxInstallments = filter_var($input->getOption('numberMaxInstallments'), FILTER_VALIDATE_INT);

        $limitInstallments = filter_var($input->getOption('limitInstallments'), FILTER_VALIDATE_BOOLEAN);

        $installmentCalculationConfig = new InstallmentCalculationConfig(
            InterestCalculation::$typeInterest($interestValue)
        );

        if ($limitValueInstallments) {
            $installmentCalculationConfig->resetLimitValueInstallment($limitValueInstallments);
        }

        if ($numberMaxInstallments) {
            $installmentCalculationConfig->resetNumberMaxInstallments($numberMaxInstallments);
        }

        if (! $limitInstallments) {
            $installmentCalculationConfig->resetLimitInstallments($limitInstallments);
        }

        return $installmentCalculationConfig;
    }

    /**
     * @param OutputInterface $output
     * @return Table
     */
    protected function createTable(OutputInterface $output)
    {
        return new Table($output);
    }

    /**
     * @param InputInterface $input
     * @return mixed
     * @throws Exception
     */
    protected function buildMonetaryFormatter(InputInterface $input)
    {
        $methodFormatter = $input->getOption('monetaryFormatter');

        $this->checkMethodFormatter($methodFormatter);

        $methodFormatter = "to{$methodFormatter}";

        return MonetaryFormatter::$methodFormatter($this->buildMonetaryFormatterConfig($input));
    }

    /**
     * @throws Exception
     */
    protected function buildInstallmentFormatter(InputInterface $input)
    {
        $installmentFormatter = new InstallmentFormatter($this->buildMonetaryFormatter($input));

        $pattern = $input->getOption('pattern');

        if (! $pattern) {
            return $installmentFormatter;
        }

        $this->checkPattern($pattern);

        $installmentFormatter->resetPattern(Patterns::PATTERN_B);

        return $installmentFormatter;
    }

    /**
     * @param $pattern
     * @throws Exception
     */
    protected function checkPattern($pattern)
    {
        $patterns = [
            'pattern_a',
            'pattern_b',
        ];

        if (! in_array($pattern, $patterns)) {
            throw new Exception('Invalid pattern. Supported "pattern_a" and "pattern_b".');
        }
    }

    /**
     * @param $methodFormatter
     * @throws Exception
     */
    protected function checkMethodFormatter($methodFormatter)
    {
        $methodFormatterDefaults = [
            'IntlCurrency',
            'IntlDecimal',
            'Decimal'
        ];

        if (! in_array($methodFormatter, $methodFormatterDefaults)) {
            throw new Exception('Invalid method formatter. Supported "IntlCurrency", "IntlDecimal" and "Decimal".');
        }
    }

    /**
     * @param InputInterface $input
     * @return mixed
     * @throws Exception
     */
    protected function buildMonetaryFormatterConfig(InputInterface $input)
    {
        $currencyIsoCode = strtoupper($input->getOption('currencyIsoCodes'));

        $this->checkCurrencyIsoCode($currencyIsoCode);

        $formatterConfig = MonetaryFormatterConfig::$currencyIsoCode($input->getOption('locale'));

        $fractionDigits = filter_var($input->getOption('fractionDigits'), FILTER_VALIDATE_INT);

        if ($fractionDigits) {
            $formatterConfig->resetFractionDigits($fractionDigits);
        }

        return $formatterConfig;
    }

    /**
     * @param $currencyIsoCode
     * @throws Exception
     */
    protected function checkCurrencyIsoCode($currencyIsoCode)
    {
        $currencyDefaults = [
            IsoCodes::BRL,
            IsoCodes::USD,
        ];

        if (! in_array($currencyIsoCode, $currencyDefaults)) {
            throw new Exception('Invalid CurrencyIsoCode. Supported "BRL" and "USD".');
        }
    }
}
