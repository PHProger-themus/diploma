<?php
/**
 * @var $productId int
 */
?>
<p class='g-mb-8'>Зарезервируйте товар, указав наименование физ. лица или юр. лица и количество товара.</p>
<form data-reserveFor='<?= $productId ?>'>
  <label for='name'>Наименование <span class='g-color-red'>*</span></label>
  <input type='text' class='form-control g-mb-8' name='name' />
  <label for='quantity'>Количество <span class='g-color-red'>*</span></label>
  <input type='text' class='form-control' name='quantity' />
</form>

<script>
  function reserve() {
    if (true) {
      alert(123);
    }
  }
</script>