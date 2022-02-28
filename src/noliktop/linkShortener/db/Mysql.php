<?php

declare(strict_types=1);


namespace noliktop\linkShortener\db;


use mysqli;

class Mysql {

	/** @var mysqli */
	protected static $db;

	public static function init(MysqlCredentials $credentials): void {
		self::$db = new mysqli(
			$credentials->host,
			$credentials->user, $credentials->password,
			$credentials->database,
			$credentials->port
		);
	}

	public static function get(): mysqli {
		return self::$db;
	}

}