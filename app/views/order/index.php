<?php
/**
 * @var $orders \app\dto\Order[]
 * @var $COMMON \system\classes\ArrayHolder
 */

use system\classes\LinkBuilder;

?>

<div class="header-line d-flex justify-content-between align-content-center g-px-10">
  <h2 class="m-0">Список заказов</h2>
  <a href="/<?= LinkBuilder::url('order', 'create') ?>" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" data-placement="left" title="Создать новый заказ"
    <span>Создать</span>
  </a>
</div>

<div class="col-12 g-mt-20 g-px-20">
  <?php foreach ($orders as $order): ?>
    <div class="product row align-items-center g-my-10 g-pa-15 justify-content-between g-bg-dark g-transition-0_2">
      <div class="col-4">
        <p class="m-0 g-font-size-22 text-uppercase g-color-primary g-font-weight-500"><?= $order->product->name ?></p>
        <p class="m-0 g-font-weight-100">Упаковано:
          <span class="g-color-vine g-font-size-15 g-font-weight-500"><?= $order->packed ? (new DateTime($order->packed))->format('d.m.Y') : '-' ?></span>
          <i class="fa-bars-staggered fa-solid g-cursor-pointer g-ml-7" data-toggle="tooltip" title="Лог событий"></i>
        </p>
      </div>
      <div class="col-3 row align-items-center">
        <span class="g-font-size-45 flex-basis-0 p-0 d-block g-color-gray-dark-v5">
          <?= $order->quantity ?>
        </span>
        <div class="col-4">
          <p class="g-font-weight-100 m-0 text-uppercase">заказано</p>
          <p class="g-font-weight-500 m-0 text-uppercase">шт.</p>
        </div>
      </div>
      <div class="buttons col-2 text-right">
        <a href="#" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" title="Редактировать">
          <i class="fa fa-pen"></i>
        </a>
        <a href="/<?= LinkBuilder::url('order', 'remove', ['url' => ['id' => $order->ID]]) ?>" class="btn btn-primary g-color-white g-color-black--hover" data-toggle="tooltip" title="Удалить">
          <i class="fa fa-trash"></i>
        </a>
      </div>
    </div>
  <?php endforeach; ?>
</div>