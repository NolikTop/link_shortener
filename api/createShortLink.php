<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Redirect;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\entity\Link;
use noliktop\linkShortener\entity\User;

require "../autoload.php";

Redirect::redirectIfNotRegistered();

$url = $_POST["url"] ?? "";

$db = Mysql::get();
$user = User::getCurrent($db);

$link = Link::tryCreate($url, 6, $user, $db, "../links.php");

header("Location: ../link.php?id=" . $link->getId());