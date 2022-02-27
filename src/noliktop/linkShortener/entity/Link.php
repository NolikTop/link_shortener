<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity;


use mysqli;

class Link implements Entity {

	/** @var int */
	protected $id;

	/** @var string */
	protected $shortUrl;

	/** @var string */
	protected $destinationUrl;

	public function load(array $row): void {
		$this->id = (int)$row["id"];
		$this->shortUrl = $row["short_url"];
		$this->destinationUrl = $row["destination_url"];
	}

	public function insert(mysqli $db): void {
		$db->query(<<<QUERY

QUERY
		);
	}

	public function update(mysqli $db): void {
		$db->query(<<<QUERY

QUERY
		);
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getShortUrl(): string {
		return $this->shortUrl;
	}

	/**
	 * @param string $shortUrl
	 */
	public function setShortUrl(string $shortUrl): void {
		$this->shortUrl = $shortUrl;
	}

	/**
	 * @return string
	 */
	public function getDestinationUrl(): string {
		return $this->destinationUrl;
	}

	/**
	 * @param string $destinationUrl
	 */
	public function setDestinationUrl(string $destinationUrl): void {
		$this->destinationUrl = $destinationUrl;
	}
}