<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


use mysqli;

interface Table {

	public function createIfNotExists(mysqli $db): void;
	public function drop(mysqli $db): void;

}