<?php

use system\classes\LinkBuilder;
use system\classes\Server;

/**
 * @var $content string
 * @var $page \system\core\Page
 */

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0">
  <title><?= $page->getTitle() ?></title>
  <meta name="keywords" content="<?= $page->getKeywords() ?>" />
  <meta name="description" content="<?= $page->getDescription() ?>" />
  <link href="<?= Cfg::$get->website['img'] ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <link href="<?= Cfg::$get->website['img'] ?>/favicon.ico" rel="icon" type="image/x-icon" />
  <?= $page->alternate ?>
  <?= $page->css ?>
  <?= $page->js ?>
  <script src='https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js'></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
</head>
<body class="m-0 g-bg-gray-dark-v2 g-color-gray-light-v4<?php if (Server::issetSession('loggedIn')): ?> g-height-100x<?php endif; ?>">

<?php View::getPopupMessage() ?>

<?php if (Server::issetSession('loggedIn')):
  $user = Server::getSession('user');
  ?>
  <div class="g-py-10 g-bg-gray-dark-v3 g-color-gray-light-v4 d-flex justify-content-between align-items-center">
    <p class="h4 m-0 g-ml-15">
      <a href="/<?= LinkBuilder::url('main', 'dashboard') ?>" class="g-color-white g-color-white--hover">My<strong
                class="text-primary">Stock</strong></a>
    </p>
    <a href="/<?= LinkBuilder::url('main', 'logout') ?>" class="d-block g-mr-15 pull-right g-font-weight-800">Выход</a>
  </div>
  <div class="container-fluid p-0 d-flex g-height-100x">
    <div class="menu g-width-250 g-height-100x g-brd-right g-brd-gray-dark-v3 g-pt-10">
      <a href="/<?= LinkBuilder::url('main', 'dashboard') ?>" class="d-block g-px-20 g-py-10 g-bg-gray-dark-v1--hover">Главная</a>
      <a href="/<?= LinkBuilder::url('order', 'index') ?>" class="d-block g-px-20 g-py-10 g-bg-gray-dark-v1--hover">Заказы</a>
      <a href="/<?= LinkBuilder::url('product', 'index') ?>" class="d-block g-px-20 g-py-10 g-bg-gray-dark-v1--hover">Товары</a>
      <a href="#" class="d-block g-px-20 g-py-10 g-bg-gray-dark-v1--hover">Статистика</a>
      <a href="#" class="d-block g-px-20 g-py-10 g-bg-gray-dark-v1--hover">Отчеты</a>
    </div>
    <div class="g-pa-20 g-width-100x">
      <?= $content ?>
    </div>
  </div>
<?php else: ?>
  <div><?= $content ?></div>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade g-bg-black-opacity-0_5" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="g-bg-gray-dark-v3 modal-content">
      <div class="g-brd-gray-dark-v4 modal-header g-pa-10">
        <h5 class="modal-title" id="modalTitle"></h5>
        <i class="fa fa-times g-cursor-pointer g-mr-8" data-bs-dismiss="modal" aria-label="Close"></i>
      </div>
      <div class="modal-body pb-0" id="modalBody">
        ...
      </div>
      <div class="g-brd-0 modal-footer">
        <button type="button" id="modalCancel" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
        <button type="button" id="modalProceed" class="btn"></button>
      </div>
    </div>
  </div>
</div>

</body>
</html>