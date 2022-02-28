<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Redirect;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\entity\user\User;
use noliktop\linkShortener\route\LinkRouter;

require 'autoload.php';

Redirect::redirectIfNotRegistered();

LinkRouter::handle();

$db = Mysql::get();
$user = User::getCurrent($db);

$links = $user->getLinks($db);
?>

<h1>Ваши короткие ссылки (<?= count($links) ?>)</h1>

<?php
foreach ($links as $link):
	$fullShortUrl = $link->getFullShortLink();
	$destinationUrl = $link->getDestinationUrl();
	?>
	<h3>Ссылка #<?= $link->getId() ?></h3>
	<p>
		<a href="<?= $fullShortUrl ?>" target="_blank">
			<?= $fullShortUrl ?>
		</a>
		->
		<a href="<?= $destinationUrl ?>" target="_blank">
			<?= $destinationUrl ?>
		</a>
	</p>
	<a href="link.php?id=<?= $link->getId() ?>">Подробнее</a>
	<br/>
<?php endforeach; ?>

<h2>Создать короткую ссылку</h2>
<form action="api/createShortLink.php" method="post">
	<label for="url">URL</label>
	<input type="url" maxlength="255" id="url" name="url">
	<br/>
	<br/>
	<input type="submit" value="Создать ссылку">
</form>
