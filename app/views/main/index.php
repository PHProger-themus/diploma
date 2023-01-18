<?php

use system\classes\FormHelper;

?>
<div class="col-sm-8 col-md-6 text-center block-center g-mt-50">
  <h1 class="h2">My<strong class="text-primary">Stock</strong></h2>
    <form method="post">
      <input type='hidden' name='_formName' value='login_form' />
      <div class="text-left col-8 col-xl-6 block-center">
        <div class="input-group-m">
          <label for="login">Логин</label>
          <input type="text" class="form-control" id="login" name="login"
                 value="<?= FormHelper::getValue('login', 'login_form') ?>" />
        </div>
        <div class="input-group-m">
          <label for="password">Пароль</label>
          <input type="password" class="form-control" id="password" name="password" />
        </div>
      </div>
      <input type="submit" class="btn btn-primary mt-2" value="Войти" />
      <!--<a href="signup" class="btn btn-outline-primary mt-2">Регистрация</a>-->
    </form>
</div>