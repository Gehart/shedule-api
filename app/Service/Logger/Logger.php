<?php

namespace App\Service\Logger;

use Monolog\Handler\StreamHandler;

class Logger extends \Monolog\Logger
{
    public function init()
    {
        // create a log channel
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler('path/to/your.log', 'warning'));

        // add records to the log
        $log->warning('Foo');
        $log->error('Bar');
    }

}
