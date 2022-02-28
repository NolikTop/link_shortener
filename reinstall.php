<?php

declare(strict_types=1);

use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\table\TablesInstaller;

require 'autoload.php';

$db = Mysql::get();

TablesInstaller::init();
TablesInstaller::recreateTables($db);

session_destroy();

header("Location: register.php");