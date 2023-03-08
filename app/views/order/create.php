<?php
/**
 * @var $orders \app\dto\Order[]
 * @var $COMMON \system\classes\ArrayHolder
 */

use system\classes\FormHelper;

/**
 * @var $products \app\dto\Product[]
 * @var $getProduct string | null
 */

?>

<div class="header-line d-flex justify-content-between align-content-center g-px-10">
  <h2 class="m-0">
    <i class="fa-regular fa-file g-mr-4"></i>
    Новый заказ
  </h2>
</div>

<div class="col-12 g-mt-20">
  <form method="post">
    <input type='hidden' name='_formName' value='order_form' />
    <div class="row">
      <div class="text-left col-5 g-px-30">

        <h3 class="g-brd-2 g-brd-bottom g-brd-primary g-pb-7 g-width-50x g-mb-25 h5">
          <i class="fa-solid fa-star-of-life g-color-primary g-font-size-15 g-mr-3"></i>
          Основная информация
        </h3>

        <div class="input-group-m g-my-10">
          <label for="number" class="g-mb-8">Куда идет <span class="g-color-red">*</span></label>
          <!-- ТРЕТЬИМ ПАРАМЕТРОМ ПЕРЕДАТЬ ЗНАЧЕНИЕ НАСТРОЙКИ -->
          <?php $to_warehouse = FormHelper::getValue('to_warehouse', 'order_form', true); ?>
          <div class="align-items-center row">
            <div class="col-3">
              <label for="to_warehouse">На склад</label>
              <input type="checkbox" name="to_warehouse" class="g-ml-5" id="to_warehouse_checkbox" <?= $to_warehouse ? 'checked' : '' ?> />
            </div>
            <div class="row align-items-center col-9 g-pr-0 <?= $to_warehouse ? 'g-opacity-0_4' : '' ?>" id="to_client_block">
              <label for="to_warehouse" class="col-3">Заказчику</label>
              <input type="text" class="col-9 form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white" name="to_client" <?= $to_warehouse ? 'disabled' : '' ?> value="<?= FormHelper::getValue('to_client', 'order_form') ?>" />
            </div>
          </div>
        </div>
        <div class="input-group-m g-my-10">
          <label for="product">Товар <span class="g-color-red">*</span></label>
          <select class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-mt-6" name="product">
            <?php $selected = FormHelper::getValue('product', 'order_form', $getProduct); foreach ($products as $product): ?>
              <option value="<?= $product->ID ?>" <?= $selected === $product->ID ? 'selected' : '' ?>>
                <?= $product->name ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="input-group-m g-my-10">
          <label for="number">Количество <span class="g-color-red">*</span></label>
          <input type="number" class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-mt-6" name="quantity" value="<?= FormHelper::getValue('quantity', 'order_form') ?>" />
        </div>
        <div class="input-group-m g-my-10">
          <label for="packed">Упакован</label>
          <input type="date" class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-mt-6" name="packed" value="<?= FormHelper::getValue('packed', 'order_form') ?>" />
        </div>
        <input type="submit" class="btn btn-primary mt-2" value="Создать" />
      </div>
      <div class="text-left col-5 g-px-30">

        <h3 class="g-brd-2 g-brd-bottom g-brd-primary g-pb-7 g-width-50x g-mb-25 h5">
          <i class="fa-regular fa-file-lines g-color-primary g-font-size-15 g-mr-3"></i>
          Цены и документы
        </h3>

      </div>
    </div>
  </form>
</div>