<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
  require dirname(__DIR__) . '/system/_init.php';
  bodyaframeInit();
  (new \system\core\Router())->run();
} catch (Error $e) {
  echo $e->getMessage() . ", файл: " . $e->getFile() . ":" . $e->getLine(); //TODO: Make this nice
  debug($e->getTrace());
}

