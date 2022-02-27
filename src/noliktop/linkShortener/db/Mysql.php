<?php

declare(strict_types=1);


namespace noliktop\linkShortener\db;


use mysqli;

class Mysql {

	/** @var mysqli */
	protected static $db;

	public static function init(string $host, string $user, string $password, string $db, int $port): void{
		self::$db = new mysqli($host, $user, $password, $db, $port);
	}

	public static function get(): mysqli{
		return self::$db;
	}

}