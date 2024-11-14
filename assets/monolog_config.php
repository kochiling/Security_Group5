<?php
require __DIR__ . "/../vendor/autoload.php";

include("config.php");

use Monolog\Logger; // Import the Logger class
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\JsonFormatter;

class MySQLHandler extends AbstractProcessingHandler
{
    private $mysqli;

    public function __construct($mysqli, $level = Logger::DEBUG, $bubble = true)
    {
        $this->mysqli = $mysqli;
        parent::__construct($level, $bubble);
    }

    // function for writing log record, record is an array here
    protected function write(array $record): void
    {
        // SQL Statement for inserting log record
        $stmt = $this->mysqli->prepare(
            'INSERT INTO logs (level, message, timestamp, context) VALUES (?, ?, ?, ?)'
        );

        $level = $record['level_name'];                          // extract log level
        $message = $record['message'];                      // extract log message
        $time = $record['datetime']->format('Y-m-d H:i:s'); // extract log time
        $context = json_encode($record['context']);         // extract log context

        $stmt->bind_param('ssss', $level, $message, $time, $context); // bind the parameters
        $stmt->execute(); // execute the SQL statement
        $stmt->close();  // close the statement
    }
}

// Create a log channel
$log = new Logger('name');
$mysqlHandler = new MySQLHandler($conn); // Use $conn from config.php
$mysqlHandler->setFormatter(new JsonFormatter());
$log->pushHandler($mysqlHandler);
