<?php

namespace system\classes;

use Cfg;

class FormHelper
{

  private static string $form = "";
  private static string $CSRFToken = "";

  public static function createForm(string $form_name)
  {
    if (Cfg::$get->safety['csrfProtection']) {
      if (self::$CSRFToken == "") {
        $token = self::generateCsrfToken();
        self::$CSRFToken = $token;
        Server::setSession(['csrfToken' => $token]);
      }
      self::$form = "<input type='hidden' name='_csrfToken' value='" . self::$CSRFToken . "' />" . self::$form;
    }

    $enctype = strpos(self::$form, "input type='file'") !== false ? " enctype='multipart/form-data'" : '';
    $formCreated = "<form method='POST'$enctype><input type='hidden' name='_formName' value='$form_name' />" . self::$form . "</form>";
    echo $formCreated;
    self::$form = '';
  }

  private static function generateCsrfToken(): string
  {
    return SafetyManager::generateRandomString(25);
  }

  public static function addInput($type, $name, $attributes = [])
  {
    self::$form .= "<input type='$type' name='$name'" . self::processAttributes($attributes) . " />";
  }

  private static function processAttributes($attributes)
  {
    $attributes_str = "";
    foreach ($attributes as $attr => $value) {
      $attributes_str .= " $attr='$value'";
    }
    return $attributes_str;
  }

  public static function addSubmit($name, $value, $attributes = [])
  {
    self::$form .= "<input type='submit' name='$name' value='$value'" . self::processAttributes($attributes) . " />";
  }

  public static function getValue($input_name, $form_name, $initial_value = NULL)
  {
    $session_key = $form_name . '_' . $input_name;
    if (Server::issetSession($session_key)) {
      return Server::extractSession($session_key);
    }
    return $initial_value;
  }

}
