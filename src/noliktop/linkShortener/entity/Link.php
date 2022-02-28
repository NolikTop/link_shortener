<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity;


use Exception;
use mysqli;

class Link implements Entity {

	/** @var int */
	protected $id;

	/** @var string */
	protected $shortUrl;

	/** @var string */
	protected $destinationUrl;

	/**
	 * todo отрефачить
	 * @throws Exception
	 */
	public static function fromShortLink(string $shortLink, mysqli $db): ?self{
		$q = $db->prepare("select * from links where short_link = ? limit 1");

		$q->bind_param("s", $shortLink);

		if(!$q->execute()){
			throw new Exception("db err: " . $db->error);
		}

		$r = $q->get_result();
		$t = $r->fetch_assoc();
		if($t === null){
			return null;
		}

		$link = new Link();
		$link->load($t);

		return $link;
	}

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