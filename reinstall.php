<?php

declare(strict_types=1);

use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\Loader;
use noliktop\linkShortener\table\TablesInstaller;

require 'autoload.php';

Loader::init();

$db = Mysql::get();

$creator = new TablesInstaller();
$creator->recreateTables($db);

//var_dump($creator->getTables());

var_dump($db->get_server_info());
var_dump($db->ping());

echo "alol";