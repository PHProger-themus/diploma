<?php

define('HOME_DIR', dirname(__DIR__));
const APP_DIR = HOME_DIR . DIRECTORY_SEPARATOR . 'app';
const SYSTEM_DIR = HOME_DIR . DIRECTORY_SEPARATOR . 'system';
const LOGS_DIR = HOME_DIR . DIRECTORY_SEPARATOR . 'logs';

$logFileName = HOME_DIR . "/logs/log.log";
$logFile = fopen($logFileName, 'a');
fwrite($logFile, "Начало работы сервера\n");
fclose($logFile);

require HOME_DIR . '/vendor/autoload.php';
require SYSTEM_DIR . '/aliases.php';
require_once APP_DIR . '/config/functions.php';
configInit();