<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


use mysqli;

class TablesCreator {

	/** @var Table[] */
	protected $tables = [];

	public function __construct() {
		$this->registerAll();
	}

	public function registerAll(): void{
		$this->register(new UsersTable());
		$this->register(new LinksTable());
		$this->register(new UseragentsTable());
		$this->register(new VisitsTable());
	}

	protected function register(Table $table){
		$this->tables[] = $table;
	}

	public function createTables(mysqli $db): void{
		foreach ($this->tables as $table){
			$table->createIfNotExists($db);
		}
	}

	public function dropTables(mysqli $db): void{
		foreach (array_reverse($this->tables) as $table){
			$table->drop($db);
		}
	}

}