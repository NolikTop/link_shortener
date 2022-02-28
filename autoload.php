<?php

declare(strict_types=1);

use noliktop\linkShortener\entity\User;
use noliktop\linkShortener\Loader;

spl_autoload_register(function (string $className): void {
	$className = str_replace("\\", "/", $className);

	include __DIR__ . "/src/$className.php";
});

Loader::init();
?>

<?php if (User::isLogged()): ?>
	<a href="index.php">На главную</a> <a href="logout.php">Выйти из аккаунта</a>
	<br/>
	<br/>
<?php endif; ?>