<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Auth;
use noliktop\linkShortener\entity\user\User;
use noliktop\linkShortener\Loader;

spl_autoload_register(function (string $className): void {
	$className = str_replace("\\", DIRECTORY_SEPARATOR, $className);

	include __DIR__ . "/src/$className.php";
});

Loader::init();
?>

<?php if (Auth::isLogged()): ?>
	<a href="index.php">На главную</a> <a href="logout.php">Выйти из аккаунта</a>
	<br/>
	<br/>
<?php endif; ?>