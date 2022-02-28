<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Redirect;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\entity\user\User;
use noliktop\linkShortener\render\LinkRenderer;
use noliktop\linkShortener\route\LinkRouter;

require 'autoload.php';

Redirect::redirectIfNotRegistered();

LinkRouter::handle();

$db = Mysql::get();
$user = User::getCurrent($db);

$links = $user->getLinks($db);

echo LinkRenderer::renderLinkList($links);
?>

<h2>Создать короткую ссылку</h2>
<form action="api/createShortLink.php" method="post">
	<label for="url">URL</label>
	<input type="url" maxlength="255" id="url" name="url">
	<br/>
	<br/>
	<input type="submit" value="Создать ссылку">
</form>
