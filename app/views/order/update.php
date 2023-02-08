<?php
/**
 * @var $orders \app\dto\Order[]
 * @var $COMMON \system\classes\ArrayHolder
 */

use system\classes\FormHelper;

/**
 * @var $products \app\dto\Product[]
 * @var $order \app\dto\Order
 */

?>

<div class="header-line d-flex justify-content-between align-content-center g-px-10">
  <h2 class="m-0">Новый заказ</h2>
</div>

<div class="col-12 g-mt-20">
  <form method="post">
    <input type='hidden' name='_formName' value='order_form' />
    <div class="text-left col-6 g-px-20">
      <div class="input-group-m g-my-10">
        <label for="product">Товар <span class="g-color-red">*</span></label>
        <select class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-mt-6" name="product">
          <?php $selected = FormHelper::getValue('product', 'order_form', (string)($order->product->ID));
          foreach ($products as $product): ?>
            <option value="<?= $product->ID ?>" <?= $selected === $product->ID ? 'selected' : '' ?>>
              <?= $product->name ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="input-group-m g-my-10">
        <label for="number">Количество <span class="g-color-red">*</span></label>
        <input type="number" class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-mt-6" name="quantity" value="<?= FormHelper::getValue('quantity', 'order_form', $order->quantity) ?>" />
      </div>
      <div class="input-group-m g-my-10">
        <label for="packed">Упакован</label>
        <input type="date" class="form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-mt-6" name="packed" data-format="dd/MM/yyyy hh:mm:ss" value="<?= FormHelper::getValue('packed', 'order_form', $order->packed) ?>" />
      </div>
      <input type="submit" class="btn btn-primary mt-2" value="Сохранить" />
    </div>
  </form>
</div>