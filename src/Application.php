<?php

namespace RicardoKovalski\Installments\Console;

use RicardoKovalski\Installments\Console\Util;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 * @package RicardoKovalski\Installments\Console
 */
final class Application extends BaseApplication
{
    /**
     * Application constructor.
     */
    public function __construct()
    {
        Util\ErrorHandler::register();
        parent::__construct('ricardokovalski/installments-console', '1.0');
    }
}
