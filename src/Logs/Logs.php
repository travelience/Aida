<?php 

namespace Travelience\Aida\Logs;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Logs {

    public function handle( $req, $res )
    {
        $path = ROOT_PATH . '/errors.log';

        $log = new Logger('logs');
        $log->pushHandler(new StreamHandler($path, Logger::WARNING));

        app()->set('log', $log);

    }

}