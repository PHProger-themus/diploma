<?php

namespace system\core;

use app\user\models\UserInputParser;
use system\classes\ArrayHolder;
use system\classes\LinkBuilder;
use system\classes\SafetyManager;
use system\classes\Server;

class Model
{

  private array $errors = [];
  private ArrayHolder $form;

  public function __construct(ArrayHolder $data = NULL)
  {
    if (!is_null($data)) {
      $this->form = $data;
    }
  }

  public function generateSeoUrl(string $text): string
  {
    $text = mb_strtolower($text);
    $rus = ['кс', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' '];
    $lat = ['x', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', '', 'y', '', 'e', 'yu', 'ya', '-'];
    return str_replace($rus, $lat, $text);
  }

  public function getField(string $key)
  {
    return $this->form->$key;
  }

  public function correct(array $rules = []): bool
  {
    $this->checkCSRF();
    $this->addFilesToForm();

    if (method_exists($this, 'rules')) {
      $parser = new UserInputParser($this);
      $rules = !empty($rules) ? $rules : $this->rules();
      foreach ($this->form as $key => $value) {
        if ($this->notServiceField($key) && array_key_exists($key, $rules)) {
          $rule = $rules[$key];
          $this->makeAssocUnique($rule);
          $this->processInputByRules($parser, $rule, $key, $value);
        }
      }
      if (!$this->emptyErrors()) {
        $this->saveForm();
        return false;
      }
    }

    $this->destroyFormSession();
    return true;
  }

  private function checkCSRF()
  {
    if (Cfg::$get->safety['csrfProtection']) {
      if (!isset($this->form->_csrfToken) || !Server::issetSession('csrfToken') || $this->form->_csrfToken != Server::getSession('csrfToken')) {
        Errors::code(419);
      }
    }
  }

  private function addFilesToForm()
  {
    foreach ($_FILES as $name => $file) {
      if ($file['error']) {
        $file = [];
      }
      $this->form->$name = $file;
    }
  }

  public function rules()
  {
    return [];
  }

  public function notServiceField(string $field)
  {
    return !in_array($field, ['_csrfToken', '_formName']);
  }

  /**
   * <i>Данная функция используется функцией <b>correct()</b>.</i><br><br>
   * Унифицирует значения массива правил валидации. Убирает повторяющиеся значения массива, повторяющиеся по ключам исключаются автоматически, а в случае присутствия правила по ключу и по значению одновременно приоритетом будет правило по значению - по ключу удаляется.
   *
   * @param array $array Массив с правилами валидации.
   */
  private function makeAssocUnique(array &$arr): void
  {
    $arr = array_unique($arr, SORT_REGULAR);
    foreach ($arr as $key => $value) {
      if (is_array($value)) {
        $repeating_rule = $key;
      } else {
        $repeating_rule = $value;
      }
      if (array_key_exists($repeating_rule, $arr) && array_search($repeating_rule, $arr) !== false) {
        unset($arr[$repeating_rule]);
      }
    }

  }

  /**
   * <i>Данный метод используется методом <b>correct()</b>.</i><br><br>
   * Обрабатывает каждое правило валидации. Если значение - массив, значит правило содержит параметры, иначе же значение воспринимается как единое правило,<br>действующее на всех страницах с полем данного <b>name</b>, и где подключена модель-обработчик.
   *
   * @param array $input Массив валидаторов.
   * @param string $key Название поля из атрибута <b>name</b>.
   * @param string|array $value Значение этого поля, которое будет валидироваться.
   */
  private function processInputByRules(UserInputParser $parser, array $input, string $key, $value): void
  {
    foreach ($input as $rule_key => $rule) {
      if (isset($this->errors[$key])) {
        break;
      }
      $rule_method = (is_array($rule) ? $rule_key : $rule);
      if ($this->routesSpecified($rule) && $this->conditionSpecified($rule)) {
        $parser->$rule_method($value, $key, $this->errors, ($rule['values'] ?? NULL));
      }
    }
  }

  private function routesSpecified($routes)
  {
    return !isset($routes['routes']) || in_array(Cfg::$get->route->getController() . '/' . Cfg::$get->route->getAction(), $routes['routes']);
  }

  private function conditionSpecified($condition)
  {
    return !isset($condition['if']) || $condition['if']();
  }

  public function emptyErrors(): bool
  {
    return empty($this->errors);
  }

  public function saveForm()
  {
    foreach ($this->form as $input => $value) {
      if ($this->notServiceField($input) && !is_array($value)) {
        Server::setSession([$this->form->_formName . '_' . $input => $value]);
      }
    }
  }

  private function destroyFormSession()
  {
    $sessionToDelete = [];
    foreach ($this->form as $input => $value) {
      $inputName = $this->form->_formName . '_' . $input;
      if ($this->notServiceField($input) && Server::issetSession($inputName)) {
        $sessionToDelete[] = $inputName;
      }
    }
    Server::unsetSession($sessionToDelete);
  }

  public function getErrors(): array
  {
    return $this->errors;
  }

  public function uploadFile(array $formFile, string $path, callable $generateName = NULL)
  {
    if (!empty($formFile) && $formFile['error'] == 0) {
      $new_file_name =
        (is_null($generateName) ?
          SafetyManager::generateRandomString(15) :
          $generateName())
        . '.'
        . pathinfo($formFile['name'], PATHINFO_EXTENSION);
      $path = $path . $new_file_name;
      move_uploaded_file($formFile['tmp_name'], $path);
      return $new_file_name;
    }
    return false;
  }

  public function fields()
  {
    return [];
  }

  public function backWithError(string $error, string $defaultUrl = '')
  {
    View::setPopupMessage($error, Errors::ERROR); // Создаем окно с ошибкой и отправляем пользователя на форму
    $referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $defaultUrl;
    LinkBuilder::redirect(
      str_replace(
        Server::getProtocol() . '://' . $_SERVER['HTTP_HOST'] . '/' .
        (Cfg::$get->website['prefix'] ? Cfg::$get->website['prefix'] . '/' : ''), '', $referer
      )
    );
  }

  /**
   * Убирает из формы сервисные поля
   * @param $form ArrayHolder | array
   * @return array
   */
  public function clearForm($form): ArrayHolder {
    return ArrayHolder::new(array_filter(
      $form instanceof ArrayHolder ? ArrayHolder::old($form) : $form,
      fn($field) => $this->notServiceField($field),
      ARRAY_FILTER_USE_KEY
    ));
  }

}
