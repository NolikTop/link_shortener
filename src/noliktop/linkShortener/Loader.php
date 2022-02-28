<?php

declare(strict_types=1);


namespace noliktop\linkShortener;


use noliktop\linkShortener\config\Config;
use noliktop\linkShortener\config\ConfigException;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\db\MysqlCredentials;
use noliktop\linkShortener\tip\TipRenderer;

final class Loader {

	/** @var Config */
	protected static $config;

	/**
	 * @throws ConfigException
	 */
	public static function init(): void {
		self::initConfig();
		self::initDb();
		self::initSession();

		self::afterInit();
	}

	private static function afterInit(): void {
		TipRenderer::render();

	}

	/**
	 * @throws ConfigException
	 */
	private static function initConfig(): void {
		$ds = DIRECTORY_SEPARATOR;

		$allConfigsPath = __DIR__ . "$ds..$ds..$ds..{$ds}config$ds";
		$configPath = $allConfigsPath . "config.json";

		self::$config = new Config($configPath);
	}

	private static function initDb(): void {
		$credentials = self::getDbCredentials();

		Mysql::init($credentials);
	}

	private static function getDbCredentials(): MysqlCredentials {
		$obj = new MysqlCredentials();
		self::$config->fillObject("db", $obj);

		return $obj;
	}

	private static function initSession(): void {
		session_start();
	}

	private function __construct() {
	}

}