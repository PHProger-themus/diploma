<?php
	use system\classes\FormHelper;
	View::getPopupMessage();
?>
<div class="col-sm-8 col-md-6 text-center block-center mt-10">
	<h2>Регистрация в АЗС <strong class="text-primary">ЗаправычЪ</strong></h2>
	<form method="post">
		<input type='hidden' name='_formName' value='signup_form' />
		<div class="text-left col-8 col-xl-6 block-center">
			<div class="input-group-m">
				<label for="name">Имя</label>
				<input type="text" class="form-control" id="name" name="name" value="<?= FormHelper::getValue('name', 'signup_form') ?>" />
			</div>
			<div class="input-group-m">
				<label for="lastname">Фамилия</label>
				<input type="text" class="form-control" id="lastname" name="lastname" value="<?= FormHelper::getValue('lastname', 'signup_form') ?>" />
			</div>
			<div class="input-group-m">
				<label for="birthdate">Дата рождения</label>
				<input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= FormHelper::getValue('birthdate', 'signup_form') ?>" />
			</div>
			<div class="input-group-m">
				<label for="phone_number">Номер телефона</label>
				<input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= FormHelper::getValue('phone_number', 'signup_form') ?>" />
			</div>
			<div class="input-group-m">
				<label for="login">Логин</label>
				<input type="text" class="form-control" id="login" name="login" value="<?= FormHelper::getValue('login', 'signup_form') ?>" />
			</div>
			<div class="input-group-m">
				<label for="password">Пароль</label>
				<input type="password" class="form-control" id="password" name="password" />
			</div>
			<div class="input-group-m">
				<label for="repeat_password">Повторите пароль</label>
				<input type="password" class="form-control" id="repeat_password" name="repeat_password" />
			</div>
		</div>
		<input type="submit" class="btn btn-primary mt-2" value="Зарегистрироваться" />
		<a href="/coursach" class="btn btn-outline-primary mt-2">Вернуться</a>
	</form>
</div>