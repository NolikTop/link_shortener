<?php

declare(strict_types=1);


namespace noliktop\linkShortener\db;


class MysqlCredentials {

	/** @var string */
	public $host;

	/** @var string */
	public $user;

	/** @var string */
	public $password;

	/** @var string */
	public $db;

	/** @var int */
	public $port;

}