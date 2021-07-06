<?php

namespace RicardoKovalski\Installments\Console\Tests;

use PHPUnit\Framework\TestCase;
use RicardoKovalski\Installments\Console\Application;

class ApplicationTest extends TestCase
{
    public function testConstructor()
    {
        $app = new Application();

        restore_error_handler();

        $this->assertInstanceOf('RicardoKovalski\\Installments\\Console\\Application', $app);
        $this->assertEquals('ricardokovalski/installments-console', $app->getName());
        $this->assertEquals('1.0', $app->getVersion());
    }
}
