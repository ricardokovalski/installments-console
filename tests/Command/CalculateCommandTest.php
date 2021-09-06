<?php

namespace RicardoKovalski\Installments\Console\Tests\Command;

use Exception;
use PHPUnit\Framework\TestCase;
use RicardoKovalski\Installments\Console\Application;
use RicardoKovalski\Installments\Console\Command\CalculateCommand;
use RicardoKovalski\Installments\Console\Tests\Util\TestOutput;
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

        $expected = file_get_contents('tests/mocks/testCommandWithoutOptionCalculationConfig.txt');

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

        $expected = file_get_contents('tests/mocks/testCommandWithOptionCalculationConfig.txt');

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

    /*public function testExpectedExceptionWhenCommandFirstArgumentIsEmpty()
    {
        $command = new CalculateCommand();

        $input = new StringInput('');

        $input->bind($command->getDefinition());

        $output = new TestOutput();

        $this->expectException(Exception::class);

        $this->execute->invoke($command, $input, $output);
    }*/

    /*public function testExpectedExceptionWhenCommandFirstArgumentIsInvalid()
    {
        $command = new CalculateCommand();

        $input = new StringInput('XYZ');

        $input->bind($command->getDefinition());

        $output = new TestOutput();

        $this->expectException(Exception::class);

        $this->execute->invoke($command, $input, $output);
    }*/

    /*public function testCommandFirstArgumentIsFinancial()
    {
        $command = new CalculateCommand();

        $input = new StringInput('Financial');

        $input->bind($command->getDefinition());

        $output = new TestOutput();

        $this->execute->invoke($command, $input, $output);
        $this->assertCount(1, $output->messages);
        $this->assertEquals(0, $output->messages[0]);
    }*/

    /*public function testCommandSecondArgumentCompleted()
    {
        $command = new CalculateCommand();

        $input = new StringInput('Financial 2.99');

        $input->bind($command->getDefinition());

        $output = new TestOutput();

        $this->execute->invoke($command, $input, $output);
        $this->assertCount(1, $output->messages);
        $this->assertEquals(0, $output->messages[0]);
    }*/

    /*public function testCommandThirdArgumentCompleted()
    {
        $command = new CalculateCommand();

        $input = new StringInput('Financial 2.99 350.90');

        $input->bind($command->getDefinition());

        $output = new TestOutput();

        $this->execute->invoke($command, $input, $output);
        $this->assertCount(1, $output->messages);
        $this->assertEquals(350.9, $output->messages[0]);
    }*/

    /*public function testCommandFourteenArgumentCompleted()
    {
        $command = new CalculateCommand();

        $input = new StringInput('Financial 2.99 350.90 2');

        $input->bind($command->getDefinition());

        $output = new TestOutput();

        $this->execute->invoke($command, $input, $output);
        $this->assertCount(1, $output->messages);
        $this->assertEquals(366.71513681364, $output->messages[0]);
    }*/
}
