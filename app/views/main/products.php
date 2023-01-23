<?php
/**
 * @var $products \app\dto\Product[]
 */
?>

<h2>Список товаров</h2>

<div class="col-12 g-mt-20 g-px-20">
  <?php foreach ($products as $product): ?>
    <div class="product row align-items-center g-my-10 g-pa-15 justify-content-between g-bg-dark g-transition-0_2">
      <div class="col-5">
        <p class="m-0 g-font-size-22 text-uppercase g-color-primary g-font-weight-500"><?= $product->name ?></p>
        <p class="m-0 g-font-weight-100">Закупочная цена:
          <span class="g-color-gray-dark-v5 g-font-size-16 g-font-weight-500"><?= $product->price ?> руб.</span>
        </p>
      </div>
      <div class="col-4 row align-items-center">
        <span class="g-font-size-45 flex-basis-0 p-0 d-block <?= $product->quantityColor ?>">
          <?= $product->quantity - $product->reserveTotal ?>
        </span>
        <div class="col-3">
          <p class="g-font-weight-100 m-0 text-uppercase">на складе</p>
          <p class="g-font-weight-500 m-0 text-uppercase">шт.</p>
        </div>
        <?php if($product->reserveTotal): ?>
        <div class="col-3 position-relative reserve-block">
          <p class="g-font-weight-100 m-0 text-uppercase">в резерве:</p>
          <p class="g-font-weight-500 m-0 text-uppercase">
            <span class="g-color-blue"><?= $product->reserveTotal ?></span> шт.
            <i class="fa-circle-info fa-solid g-color-primary g-cursor-pointer g-ml-4"></i>
          </p>
          <div class="g-bg-gray-dark-v3 position-absolute g-left-0 g-right-0 g-mt-5" data-product="<?= $product->ID ?>" style="display:none">
            <?php foreach ($product->reserve as $reserveRow): ?>
              <div class="g-bg-gray-dark-v1--hover g-py-3 g-px-15 g-transition-0_2 g-cursor-pointer">
                <span><?= $reserveRow->quantity ?> шт. для <?= $reserveRow->company_name ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <div class="buttons col-3 text-right">
        <a href="#" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" data-bs-original-title="Создать новый заказ с этим товаром" data-delay='{"show":"5000", "hide":"3000"}'
          <span>Создать заказ</span>
        </a>
        <a href="#" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" data-bs-original-title="Зарезервировать">
          <i class="fa-solid fa-lock"></i>
        </a>
        <a href="#" class="btn btn-outline-primary g-color-white g-color-black--hover" data-toggle="tooltip" data-bs-original-title="Редактировать">
          <i class="fa fa-pen"></i>
        </a>
        <a href="#" class="btn btn-primary g-color-white g-color-black--hover" data-toggle="tooltip" data-bs-original-title="Удалить">
          <i class="fa fa-trash"></i>
        </a>
      </div>
    </div>
  <?php endforeach; ?>
</div>