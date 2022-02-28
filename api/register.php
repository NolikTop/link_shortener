<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Auth;
use noliktop\linkShortener\auth\Redirect;
use noliktop\linkShortener\db\Mysql;

require "../autoload.php";

$login = $_POST["login"] ?? "";
$password = $_POST["password"] ?? "";

$db = Mysql::get();
Auth::tryRegister($login, $password, $db, "../register.php");

Redirect::redirectIfRegistered();