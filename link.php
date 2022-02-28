<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Redirect;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\entity\link\Link;
use noliktop\linkShortener\entity\user\User;
use noliktop\linkShortener\render\LinkRenderer;

require "autoload.php";

Redirect::redirectIfNotRegistered();

$linkId = (int)$_GET["id"] ?? 0;
if ($linkId === 0) {
	die("Specify link id");
}

$db = Mysql::get();
$user = User::getCurrent($db);

$link = new Link();
$link->loadById($linkId, $db);

$visits = $link->getVisits($db);

echo LinkRenderer::renderLinkWithVisits($link, $visits);
