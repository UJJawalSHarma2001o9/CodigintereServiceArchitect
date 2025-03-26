<?php

namespace App\Libraries;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

class LoggerService
{
    public static function getLogger($channel = 'app')
    {
        // Generate a log file with today's date
        $date = date('Y-m-d');
        $logFile = WRITEPATH . "logs/logs_$date.log";

        $logger = new Logger($channel);

        // Create a StreamHandler for logging
        $streamHandler = new StreamHandler($logFile, Logger::DEBUG);
        $streamHandler->setFormatter(new JsonFormatter()); // Log in JSON format

        // Push the handler to the logger
        $logger->pushHandler($streamHandler);

        return $logger;
    }
}
