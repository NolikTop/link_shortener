<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity;


use mysqli;

interface Entity {

	public function load(array $row): void;
	public function insert(mysqli $db): void;
	public function update(mysqli $db): void;

}