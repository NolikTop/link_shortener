<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


use mysqli;

class TablesInstaller {

	/** @var Table[] */
	protected $tables = [];

	public function __construct() {
		$this->registerAll();
	}

	public function registerAll(): void {
		$this->register(new UsersTable());
		$this->register(new LinksTable());
		$this->register(new VisitsTable());
	}

	protected function register(Table $table) {
		$this->tables[] = $table;
	}

	/**
	 * @throws TableException
	 */
	public function recreateTables(mysqli $db): void {
		$this->dropTables($db);
		$this->createTables($db);
	}

	/**
	 * @throws TableException
	 */
	public function createTables(mysqli $db): void {
		foreach ($this->tables as $table) {
			$queryText = $table->getQueryForCreateIfNotExists();
			$q = $db->query($queryText);

			if ($q !== false) continue;

			throw new TableException("Database error on create: $db->error. Query: $queryText");
		}
	}

	/**
	 * @throws TableException
	 */
	public function dropTables(mysqli $db): void {
		foreach (array_reverse($this->tables) as $table) {
			$queryText = $table->getQueryForDropIfExists();
			$q = $db->query($queryText);

			if ($q !== false) continue;

			throw new TableException("Database error on drop: $db->error. Query: $queryText");
		}
	}

	public function getTables(): array {
		return $this->tables;
	}

}