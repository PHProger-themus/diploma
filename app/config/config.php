<?php

return [

  //Установка опции в false переведет сайт в режим обслуживания, сделав его недоступным для пользователя
  'active' => true,
  'allowedFor' => [
    '',
  ],

  //Все routes приложения
  'routes' => require(APP_DIR . '/web/routes.php'),

  //Текущий URL страницы без GET-параметров
  'url' => strstr(PHP::getServer('REQUEST_URI') . '?', '?', true),

  //С какого устройства была загружена страница ( mobile / desktop )
  'device' => (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", PHP::getServer('HTTP_USER_AGENT'))) ? 'mobile' : 'desktop',

  //Массив данных вебсайта
  'website' => [
    'prefix' => '',
    'root' => HOME_DIR,
    'img' => 'app/files/images',
  ],

  //CSS и JS файлы, их местоположение, кеширование
  'cssFolder' => 'app/files/css',
  'jsFolder' => 'app/files/js',
  'disableCache' => false,
  'links' => [
    'css' => [
      'bootstrap.min.css',
      'unify-components.css',
      'unify-globals.css',
      'style.css',
    ],
    'js' => [
      'bootstrap.bundle.min.js',
      'jquery-3.6.0.min.js',
      'common.js',
    ],
  ],
  'errorsCss' => APP_DIR . '/files/css/error.css', // TODO: What is this?

  //Массив данных, используемых в функциях по обеспечению безопасности передаваемых данных
  'safety' => [
    'beginSalt' => 'dk85nx312mh9bs4dsj5',
    'endSalt' => 'fhgs0567k32c9s8vfnj6',
    'csrfProtection' => false,
    'xFrameOptions' => 'DENY', // DENY или SAMEORIGIN
    'sessionCookieName' => 's_token',
  ],

];
