<?php
use system\classes\LinkBuilder;
use system\classes\Server;
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
    <body class="m-0 g-bg-gray-dark-v2 g-color-gray-light-v4<?php if(Server::issetSession('loggedIn')):?> g-height-100x<?php endif; ?>">
	
	<?php if(Server::issetSession('loggedIn')):
			$user = Server::getSession('user');
		?>
		<div class="g-py-10 g-bg-gray-dark-v3 g-color-gray-light-v4 d-flex justify-content-between align-items-center">
			<p class="h4 m-0 g-ml-15">
				<a href="#" class="g-color-white g-color-white--hover">My<strong class="text-primary">Stock</strong></a>
			</p>
			<a href="/<?= LinkBuilder::url('main', 'logout') ?>" class="d-block g-mr-15 pull-right g-font-weight-800">Выход</a>
		</div>
		<div class="container-fluid p-0 d-flex g-height-100x">
			<div class="menu g-width-250 g-height-100x g-brd-right g-brd-gray-dark-v3 g-pt-10">
				<a href="#" class="d-block g-px-20 g-py-10 g-bg-gray-dark-v1--hover">Главная</a>
				<a href="#" class="d-block g-px-20 g-py-10 g-bg-gray-dark-v1--hover">Заказы</a>
				<a href="#" class="d-block g-px-20 g-py-10 g-bg-gray-dark-v1--hover">Товары</a>
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

    </body>
</html>