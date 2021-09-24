<?php

namespace RicardoKovalski\Installments\Console\Tests\Command;

use PHPUnit\Framework\TestCase;
use RicardoKovalski\Installments\Console\Application;
use RicardoKovalski\Installments\Console\Command\CalculateCommand;
use RicardoKovalski\Installments\Console\Util\BufferedOutput;
use Symfony\Component\Console\Input\StringInput;

class CalculateCommandTest extends TestCase
{
    protected $execute;

    protected $calculate;

    protected function setUp()
    {
        parent::setUp();

        $this->execute = new \ReflectionMethod('RicardoKovalski\\Installments\\Console\\Command\\CalculateCommand', 'execute');
        $this->execute->setAccessible(true);

        $this->calculate = new CalculateCommand();
        $this->calculate->setApplication(new Application());
    }

    public function testConfigure()
    {
        $command = new CalculateCommand();

        $this->assertEquals('calculate', $command->getName());
        $this->assertEquals('Calculate installments', $command->getDescription());
    }

    public function testCommandWithoutOptionCalculationConfig()
    {
        $command = new CalculateCommand();

        $expected = file_get_contents($this->getMockScenery(1));

        $stringCommand = '343.90
            --typeInterest=Financial
            --interestValue=2.99
            --limitValueInstallment=10.09';

        $input = new StringInput($stringCommand);

        $input->bind($command->getDefinition());

        $output = new BufferedOutput();

        $this->execute->invoke($this->calculate, $input, $output);

        $this->assertEquals($expected, $output->fetch());
    }

    public function testCommandWithOptionCalculationConfig()
    {
        $command = new CalculateCommand();

        $expected = file_get_contents($this->getMockScenery(2));

        $stringCommand = '343.90
            --typeInterest=Financial
            --interestValue=2.99
            --limitValueInstallment=10.09
            --monetaryFormatterConfig
            --currencyIsoCodes=usd
            --locale=en_us
            --fractionDigits=3
            --monetaryFormatter=IntlCurrency';

        $input = new StringInput($stringCommand);

        $input->bind($command->getDefinition());

        $output = new BufferedOutput();

        $this->execute->invoke($this->calculate, $input, $output);

        $this->assertEquals($expected, $output->fetch());
    }

    public function testCommandWithLimitValueInstallments()
    {
        $command = new CalculateCommand();

        $expected = file_get_contents($this->getMockScenery(3));

        $stringCommand = '39.90
            --typeInterest=Financial
            --interestValue=2.99
            --limitValueInstallment=6.00
            --monetaryFormatterConfig
            --currencyIsoCodes=usd
            --locale=en_us
            --monetaryFormatter=IntlDecimal';

        $input = new StringInput($stringCommand);

        $input->bind($command->getDefinition());

        $output = new BufferedOutput();

        $this->execute->invoke($this->calculate, $input, $output);

        $this->assertEquals($expected, $output->fetch());
    }

    public function testCommandWithNumberMaxInstallments()
    {
        $command = new CalculateCommand();

        $expected = file_get_contents($this->getMockScenery(4));

        $stringCommand = '120.35
            --typeInterest=Financial
            --interestValue=1.79
            --numberMaxInstallments=5
            --monetaryFormatterConfig
            --currencyIsoCodes=usd
            --locale=en_us
            --monetaryFormatter=IntlDecimal';

        $input = new StringInput($stringCommand);

        $input->bind($command->getDefinition());

        $output = new BufferedOutput();

        $this->execute->invoke($this->calculate, $input, $output);

        $this->assertEquals($expected, $output->fetch());
    }

    public function testCommandWithObjectInstallmentFormatter()
    {
        $command = new CalculateCommand();

        $expected = file_get_contents($this->getMockScenery(5));

        $stringCommand = '786.44
            --typeInterest=Financial
            --interestValue=2.75
            --monetaryFormatterConfig
            --currencyIsoCodes=usd
            --locale=en_us
            --monetaryFormatter=IntlCurrency
            --installmentFormatter';

        $input = new StringInput($stringCommand);

        $input->bind($command->getDefinition());

        $output = new BufferedOutput();

        $this->execute->invoke($this->calculate, $input, $output);

        $this->assertEquals($expected, $output->fetch());
    }

    public function testCommandWithObjectInstallmentFormatterAndPatternFormatted()
    {
        $command = new CalculateCommand();

        $expected = file_get_contents($this->getMockScenery(6));

        $stringCommand = '689.65
            --typeInterest=Financial
            --interestValue=2.99
            --monetaryFormatterConfig
            --currencyIsoCodes=usd
            --locale=en_us
            --monetaryFormatter=IntlCurrency
            --installmentFormatter
            --pattern=pattern_b';

        $input = new StringInput($stringCommand);

        $input->bind($command->getDefinition());

        $output = new BufferedOutput();

        $this->execute->invoke($this->calculate, $input, $output);

        $this->assertEquals($expected, $output->fetch());
    }

    /**
     * @param $scenery
     * @return string|void
     */
    public function getMockScenery($scenery)
    {
        switch ($scenery) {
            case 1:
                return 'tests/mocks/testCommandWithoutOptionCalculationConfig.txt';
            case 2:
                return 'tests/mocks/testCommandWithOptionCalculationConfig.txt';
            case 3:
                return 'tests/mocks/testCommandWithLimitValueInstallments.txt';
            case 4:
                return 'tests/mocks/testCommandWithNumberMaxInstallments.txt';
            case 5:
                return 'tests/mocks/testCommandWithObjectInstallmentFormatter.txt';
            case 6:
                return 'tests/mocks/testCommandWithObjectInstallmentFormatterAndPatternFormatted.txt';
        }
    }


}
