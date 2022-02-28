<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


use mysqli;

class TablesInstaller {

	/** @var Table[] */
	protected static $tables = [];

	public static function init() {
		self::registerAll();
	}

	public static function registerAll(): void {
		self::register(new UsersTable());
		self::register(new LinksTable());
		self::register(new VisitsTable());
	}

	protected static function register(Table $table) {
		self::$tables[] = $table;
	}

	/**
	 * @throws TableException
	 */
	public static function recreateTables(mysqli $db): void {
		self::dropTables($db);
		self::createTables($db);
	}

	/**
	 * @throws TableException
	 */
	public static function createTables(mysqli $db): void {
		foreach (self::$tables as $table) {
			$queryText = $table->getQueryForCreateIfNotExists();
			$q = $db->query($queryText);

			if ($q !== false) continue;

			throw new TableException("Database error on create: $db->error. Query: $queryText");
		}
	}

	/**
	 * @throws TableException
	 */
	public static function dropTables(mysqli $db): void {
		foreach (array_reverse(self::$tables) as $table) {
			$queryText = $table->getQueryForDropIfExists();
			$q = $db->query($queryText);

			if ($q !== false) continue;

			throw new TableException("Database error on drop: $db->error. Query: $queryText");
		}
	}

	public static function getTables(): array {
		return self::$tables;
	}

}