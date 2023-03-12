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
              <div class="col-9 d-inline-block" bgchar-dropdown data-dropdown-id="clients" data-dropdown-async>
                <input type="text" class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white" name="to_client" <?= $to_warehouse ? 'disabled' : '' ?> value="<?= FormHelper::getValue('to_client', 'order_form') ?>" />
                <ul class="g-bg-gray-dark-v4 g-pos-abs p-0 g-z-index-1"></ul>
              </div>
            </div>
          </div>
        </div>
        <div class="input-group-m g-my-10">
          <label for="name">Название <span class="g-color-red">*</span></label>
          <input type="text" class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-mt-6" name="name" value="<?= FormHelper::getValue('name', 'order_form') ?>" />
        </div>
        <div class="input-group-m g-my-10">
          <label for="product">Товары <span class="g-color-red">*</span></label>
          <div class="col-12 g-mt-6" bgchar-dropdown data-dropdown-id="products" data-dropdown-async>
            <input type="text" class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white" placeholder="Добавить товары" />
            <ul class="g-bg-gray-dark-v4 g-pos-abs p-0 g-z-index-1"></ul>
          </div>
          <ul class="products-list g-bg-dark g-list-style-none g-max-height-70vh g-rounded-4 p-0"></ul>
        </div>
        <div class="input-group-m g-my-10">
          <label for="packed">Упакован</label>
          <input type="date" class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-mt-6" name="packed" data-format="dd/MM/yyyy hh:mm:ss" value="<?= FormHelper::getValue('packed', 'order_form') ?>" />
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