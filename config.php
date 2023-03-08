<?php

return [

  'db' => [
    'active' => true,
    'databases' => [
      'db' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'diploma',
        'prefix' => 'd_',
        'trusted_tables' => ['users', 'products', 'orders', 'reserve', 'plans', 'comments', 'clients'],
      ],
    ],
    'useAttributes' => false, // TODO: remove this opportunity as it's inconvenient
  ],

  //Используется ли мультиязычность
  'multilang' => false,
  'lang' => 'ru',
  'langs' => [],
  'useFile' => false,

  //"debug" - отображение специальных ошибок на уровне фреймворка (не создан controller, action и тд.). "db_debug" - вывод выполненных SQL-запросов на текущей странице и ошибок SQL
  'debug' => true,
  'db_debug' => false,

  //Используются ли сессии на сайте
  'useSessions' => true,

];