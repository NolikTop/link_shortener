<?php

declare(strict_types=1);


namespace noliktop\linkShortener\table;


interface Table {

	public function getQueryForCreateIfNotExists(): string;
	public function getQueryForDropIfExists(): string;

}