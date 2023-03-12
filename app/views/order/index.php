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
    <div class="product g-my-10 g-pa-15 g-bg-dark g-transition-0_2">
      <div class="main col-12 p-0 row align-items-center justify-content-between">
        <div class="align-items-center col-4 row">
          <div class="col-1 p-0 text-center g-cursor-pointer g-transition-0_3 g-color-primary--hover open-details" data-toggle="tooltip" title="Детали заказа">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
          <div class="col-11 p-0">
            <p class="m-0 g-font-size-22 text-uppercase g-color-primary g-font-weight-700"><?= $order->name ?></p>
            <p class="m-0 g-font-weight-100">
              <span class="g-font-weight-700 g-cursor-pointer g-mr-10" data-toggle="tooltip" title="Товаров в заказе">
                <i class="fa-solid fa-box g-mr-2"></i>  
                <span><?= count($order->products) ?></span>
              </span>
              <span class="g-font-weight-700 g-cursor-pointer g-mr-10" data-toggle="tooltip" title="Упаковано (дата)">
                <i class="fa-solid fa-boxes-packing g-mr-3"></i>
                <span class="g-color-vine g-font-size-15 g-font-weight-500 g-cursor-pointer">
                  <?= $order->packed ? (new DateTime($order->packed))->format('d.m.Y') : '-' ?>
                </span>
              </span>
              <i class="fa-bars-staggered fa-solid g-cursor-pointer g-mr-10" data-toggle="tooltip" title="Лог событий"></i>
              <span class="g-cursor-pointer g-mr-7" data-toggle="tooltip" title="Куда направляется">
                <i class="fa-solid g-mr-3 <?= $order->client ? 'fa-user' : 'fa-warehouse' ?>"></i>
                <span><?= $order->client ? $order->client->name : 'на склад' ?></span>
              </span>
            </p>
          </div>
        </div>
        <div class="buttons col-2 text-right">
          <a href="/<?= LinkBuilder::url('order', 'update', ['url' => ['id' => $order->ID]]) ?>" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" title="Редактировать">
            <i class="fa fa-pen"></i>
          </a>
          <a href="/<?= LinkBuilder::url('order', 'remove', ['url' => ['id' => $order->ID]]) ?>" data-delete class="btn btn-primary g-color-white g-color-black--hover" data-toggle="tooltip" title="Удалить">
            <i class="fa fa-trash"></i>
          </a>
        </div>
      </div>
      <div class="details col-12 g-px-10" style="display: none">
        <div class="col-6 g-pt-20">
          <div class="card g-bg-dark g-brd-primary g-color-gray-light-v2 rounded-0">
            <h3 class="card-header g-px-13 g-bg-primary g-brd-transparent g-color-black g-font-size-16 rounded-0 mb-0">
              <i class="fa-regular fa-clipboard g-mr-3"></i>
              Товаров в заказе:
              <span class="g-font-weight-700">
                <?= count($order->products) ?>
              </span>
              на общую сумму:
              <span class="g-color-pinterest g-font-weight-700">
                <?= number_format(array_reduce($order->products, fn($acc, $p) => $acc + $p->price * $p->quantity), 2) ?> руб.
              </span>
            </h3>
            <div class="table-responsive">
              <table class="table u-table--v1 mb-0">
                <thead class="g-color-gray-light-v3 g-font-weight-700">
                  <td>№</td>
                  <td>Наименование</td>
                  <td>Кол-во</td>
                  <td>Цена за ед.</td>
                </thead>
                <?php $n = 0; foreach ($order->products as $product): ?>
                  <tbody class="g-color-gray-light-v3 g-brd-top">
                    <td><?= ++$n ?></td>
                    <td><?= $product->name ?></td>
                    <td><?= $product->quantity ?></td>
                    <td><?= number_format($product->price, 2) ?></td>
                  </tbody>
                <?php endforeach; ?>
              </table>
            </div>
          </div>    
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>