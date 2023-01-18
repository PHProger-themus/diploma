<?php

namespace system\core;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{

  private bool $useSystem;

  public function __construct(bool $useSystem = false)
  {
    $this->useSystem = $useSystem;
  }

  public function emergency($message, array $context = [])
  {

    $this->addLog(LogLevel::EMERGENCY, $message, $context);

  }

  private function addLog($level, $message, array $context = [])
  {

    if ($context != NULL) $this->processContext($message, $context);

    $time = date('[D, F d, Y H:i:s]');
    $level = strtoupper($level);

    $logMessage = "$time $level: $message" . PHP_EOL;
    $logMessageSystem = "$level: $message";

    if ($this->useSystem) error_log($logMessageSystem);
    else {
      $logFileName = HOME_DIR . "/logs/$level.log";
      $logFile = fopen($logFileName, 'a');
      fwrite($logFile, $logMessage);
      fclose($logFile);
    }

  }

  private function processContext(&$message, array $context)
  {

    foreach ($context as $var => $value) {
      $message = str_replace("{$var}", $value, $message);
    }

  }

  public function alert($message, array $context = [])
  {

    $this->addLog(LogLevel::ALERT, $message, $context);

  }

  public function critical($message, array $context = [])
  {

    $this->addLog(LogLevel::CRITICAL, $message, $context);

  }

  public function error($message, array $context = [])
  {

    $this->addLog(LogLevel::ERROR, $message, $context);

  }

  public function warning($message, array $context = [])
  {

    $this->addLog(LogLevel::WARNING, $message, $context);

  }

  public function notice($message, array $context = [])
  {

    $this->addLog(LogLevel::NOTICE, $message, $context);

  }

  public function info($message, array $context = [])
  {

    $this->addLog(LogLevel::INFO, $message, $context);

  }

  public function debug($message, array $context = [])
  {

    $this->addLog(LogLevel::DEBUG, $message, $context);

  }

  public function log($level, $message, array $context = [])
  {

    $this->addLog($level, $message, $context);

  }

}
