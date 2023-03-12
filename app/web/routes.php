<?php

return [
  '' => [
    'controller' => 'main',
    'action' => 'index',
  ],
  /*'signup' => [
      'controller' => 'main',
      'action' => 'signup'
  ],*/
  'dashboard' => [
    'controller' => 'main',
    'action' => 'dashboard',
  ],
  'logout' => [
    'controller' => 'main',
    'action' => 'logout',
  ],

  // Products
  'products' => [
    'controller' => 'product',
    'action' => 'index',
  ],

  // Orders
  'orders' => [
    'controller' => 'order',
    'action' => 'index',
  ],
  'orders/add' => [
    'controller' => 'order',
    'action' => 'create',
  ],
  'orders/{id}/edit' => [
    'controller' => 'order',
    'action' => 'update',
  ],
  'orders/{id}/remove' => [
    'controller' => 'order',
    'action' => 'remove',
  ],

  // Reserve
  'reserve/{id}/remove' => [
    'controller' => 'reserve',
    'action' => 'remove',
  ],

  // Ajax API
  'api/clients' => [
    'controller' => 'ajax',
    'action' => 'clients',
  ],
  'api/products' => [
    'controller' => 'ajax',
    'action' => 'products',
  ]
];
