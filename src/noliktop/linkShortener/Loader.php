<?php

declare(strict_types=1);


namespace noliktop\linkShortener;


use noliktop\linkShortener\config\Config;
use noliktop\linkShortener\config\ConfigException;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\db\MysqlCredentials;

final class Loader {

	/** @var Config */
	protected static $config;

	/**
	 * @throws ConfigException
	 */
	public static function init(){
		self::initConfig();
		self::initDb();
	}

	/**
	 * @throws ConfigException
	 */
	private static function initConfig(): void{
		$allConfigsPath = __DIR__ . "/../../../config/";
		$configPath = $allConfigsPath . "config.json";

		self::$config = new Config($configPath);
	}

	private static function initDb(): void{
		$credentials = self::getDbCredentials();

		Mysql::init($credentials);
	}

	private static function getDbCredentials(): MysqlCredentials{
		$obj = new MysqlCredentials();
		self::$config->fillObject("db", $obj);

		return $obj;
	}

	private function __construct(){}

}