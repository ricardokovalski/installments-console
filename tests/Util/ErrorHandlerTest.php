<?php

namespace RicardoKovalski\Installments\Console\Tests\Util;

use PHPUnit\Framework\TestCase;
use RicardoKovalski\Installments\Console\Util\ErrorHandler;

class ErrorHandlerTest extends TestCase
{
    public function testRegister()
    {
        $expected = array(
            'RicardoKovalski\\Installments\\Console\\Util\\ErrorHandler',
            'handle',
        );

        $originalHandler = set_error_handler(function () {
        });

        ErrorHandler::register();
        $testHandler = set_error_handler(function () {
        });

        set_error_handler($originalHandler);

        $this->assertEquals($expected, $testHandler);
    }

    /**
     * @expectedException ErrorException
     * @expectedExceptionMessage Test exception
     */
    public function testHandle()
    {
        error_reporting(E_ALL);
        ErrorHandler::handle(1, 'Test exception', __FILE__, __LINE__);
    }

    public function testHandleNoException()
    {
        error_reporting(0);

        $this->assertEmpty(ErrorHandler::handle(1, 'Test exception', __FILE__, __LINE__));
    }
}
