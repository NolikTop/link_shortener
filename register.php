<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Redirect;

require 'autoload.php';

Redirect::redirectIfRegistered();

?>
<h1>Регистрация</h1>
<form action="api/register.php" method="post">
	<label for="login">Логин</label>
	<input type="text" minlength="3" maxlength="32" id="login" name="login">
	<br/>
	<br/>
	<label for="password">Пароль</label>
	<input type="password" minlength="3" maxlength="64" id="password" name="password">
	<br/>
	<br/>
	<input type="submit" value="Зарегистрироваться">
</form>
<br/>
<a href="login.php">Авторизация</a>