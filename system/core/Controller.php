<?php

namespace system\core;

use system\classes\ArrayHolder;

abstract class Controller
{

  private $COMMON;
  protected View $view;
  private ?ArrayHolder $get = NULL;
  private ?ArrayHolder $post = NULL;

  public function __construct($COMMON)
  {
    $this->COMMON = $COMMON;
    $this->view = new View();
    if (!empty($_GET)) {
      $this->get = ArrayHolder::new($_GET);
    }
    if (!empty($_POST)) {
      $this->post = ArrayHolder::new($_POST);
    }
  }

  protected function render($vars = [])
  {
    $this->view->render(array_merge($vars, ['COMMON' => $this->COMMON]));
  }

  /**
   * Использован ли метод POST для запроса страницы
   * @return bool Да / Нет.
   */
  protected function isPost(): bool
  {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  /**
   * Использован ли метод GET для запроса страницы
   * @return bool Да / Нет.
   */
  protected function isGet(): bool
  {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
  }

  protected function get($key = NULL)
  {
    if (is_null($key)) {
      return $this->view->processValue($this->get);
    }
    return $this->view->processValue($this->get ? (property_exists($this->get, $key) ? $this->get->$key : NULL) : NULL);
  }

  protected function post($key = NULL)
  {
    if (is_null($key)) {
      return $this->view->processValue($this->post);
    }
    return $this->view->processValue($this->post ? (property_exists($this->post, $key) ? $this->post->$key : NULL) : NULL);
  }

  protected function issetPost($key)
  {
    return isset($this->post->$key);
  }

  protected function issetGet($key)
  {
    return isset($this->get->$key);
  }

}