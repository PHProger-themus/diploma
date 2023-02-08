<?php
/**
 * @var $products \app\dto\Product[]
 * @var $COMMON \app\dto\Common
 */

use system\classes\LinkBuilder;

?>

<div class="header-line d-flex align-content-center g-px-10">
  <h2 class="m-0">Список товаров</h2>
</div>

<div class="col-12 g-mt-20 g-px-20">
  <?php foreach ($products as $product): ?>
    <div class="product row align-items-center g-my-10 g-pa-15 justify-content-between g-bg-dark g-transition-0_2">
      <div class="col-4">
        <p class="m-0 g-font-size-22 text-uppercase g-color-primary g-font-weight-500"><?= $product->name ?></p>
        <p class="m-0 g-font-weight-100">Закупочная цена:
          <span class="g-color-gray-dark-v5 g-font-size-16 g-font-weight-500"><?= $product->price ?> руб.</span>
        </p>
      </div>
      <div class="col-3 row align-items-center">
        <span class="g-font-size-45 flex-basis-0 p-0 d-block <?= $product->quantityColor ?>">
          <?= $product->quantity - $product->reserveTotal ?>
        </span>
        <div class="col-4">
          <p class="g-font-weight-100 m-0 text-uppercase">на складе</p>
          <p class="g-font-weight-500 m-0 text-uppercase">шт.</p>
        </div>
        <?php if($product->reserveTotal): ?>
        <div class="col-4 position-relative reserve-block">
          <p class="g-font-weight-100 m-0 text-uppercase">в резерве:</p>
          <p class="g-font-weight-500 m-0 text-uppercase">
            <span class="g-color-blue"><?= $product->reserveTotal ?></span> шт.
            <i class="fa-circle-info fa-solid g-color-primary g-cursor-pointer g-ml-4"></i>
          </p>
          <div class="g-bg-gray-dark-v3 position-absolute g-left-0 g-right-0 g-mt-5 g-z-index-99" data-product="<?= $product->ID ?>" style="display:none">
            <?php foreach ($product->reserve as $reserveRow): ?>
              <div class="g-bg-gray-dark-v1--hover g-cursor-pointer g-pos-rel g-pr-25 g-px-15 g-py-3 g-transition-0_2 g-word-break">
                <span><?= $reserveRow->quantity ?> шт. для <?= $reserveRow->company_name ?></span>
                <a href="/<?= LinkBuilder::url('reserve', 'remove', ['url' => ['id' => $reserveRow->ID]]) ?>" class="g-top-3 g-pos-abs g-right-7" data-delete data-toggle="tooltip" title="Удалить из резерва">
                  <i class="fa fa-trash-alt g-color-primary g-font-size-13"></i>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-3">
        <?php if($product->ordersTotal): ?>
          <div class="align-items-center flex-nowrap justify-content-center row">
            <i class="fa-circle-notch fa-solid fa-spin flex-basis-0 g-color-primary g-font-size-30 p-0"></i>
            <div>
              <p class="g-font-weight-100 m-0 text-uppercase">в пути</p>
              <p class="g-font-weight-500 m-0 text-uppercase">
                <span class="g-color-purple g-font-weight-500"><?= $product->ordersTotal ?></span> шт.
                <a href="#">
                  <i class="fa-circle-info fa-solid g-color-primary g-cursor-pointer g-ml-4" data-toggle="tooltip" title="Заказы с этим товаром"></i>
                </a>
              </p>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="buttons col-2 text-right">
        <a href="/<?= LinkBuilder::url('order', 'create') ?>?product=<?= $product->ID ?>" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" title="Создать новый заказ с этим товаром"
          <span>Создать</span>
        </a>
        <a href="#" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" title="Зарезервировать"
           onclick="<?= getModal('reserve', ['productId' => $product->ID]) ?>">
          <i class="fa-solid fa-lock"></i>
        </a>
        <a href="#" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" title="Редактировать">
          <i class="fa fa-pen"></i>
        </a>
        <a href="#" class="btn btn-primary g-color-white g-color-black--hover" data-delete data-toggle="tooltip" title="Удалить">
          <i class="fa fa-trash"></i>
        </a>
      </div>
    </div>
  <?php endforeach; ?>
</div>