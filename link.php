<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Redirect;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\entity\Link;
use noliktop\linkShortener\entity\User;

require "autoload.php";

Redirect::redirectIfNotRegistered();

$linkId = (int)$_GET["id"] ?? 0;
if ($linkId === 0) {
	die("Specify link id");
}

$db = Mysql::get();
$user = User::getCurrent($db);

$link = new Link($linkId);
$link->loadById($db);

$visits = $link->getVisits($db);
$destinationUrl = $link->getDestinationUrl();
?>

<h1>Ссылка #<?= $link->getId() ?></h1>
<p>
	Короткая ссылка:
	<a href="<?= $link->getFullShortLink() ?>" target="_blank">
		<?= $link->getFullShortLink() ?>
	</a>
</p>
<p>Исходная ссылка:
	<a href="<?= $destinationUrl ?>" target="_blank">
		<?= $destinationUrl ?>
	</a>
</p>
<h2>Посещения (<?= count($visits) ?>)</h2>
<?php foreach ($visits as $visit): ?>
	<h3>Посещение #<?= $visit->getId() ?> в <?= $visit->getCreatedAt() ?></h3>
	<p>IP: <?= $visit->getIp() ?></p>
	<p>Useragent: <?= $visit->getUseragent() ?></p>
	<br/>
<?php endforeach; ?>
