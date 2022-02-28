<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity\visit;


use mysqli;
use mysqli_stmt;
use noliktop\linkShortener\entity\Entity;
use noliktop\linkShortener\entity\EntityException;

class Visit extends Entity {

	/** @var int */
	protected $id;

	/** @var string */
	protected $ip;

	/** @var int */
	protected $linkId;

	/** @var string */
	protected $useragent;

	/** @var string */
	protected $createdAt;

	/**
	 * @throws EntityException
	 */
	public static function create(string $ip, int $linkId, string $useragent, mysqli $db): Visit {
		$visit = new Visit();
		$visit->ip = $ip;
		$visit->linkId = $linkId;
		$visit->useragent = $useragent;

		$visit->insert($db);

		return $visit;
	}

	public function getId(): int {
		return $this->id;
	}

	protected function loadFromRow(array $row): void {
		$this->id = (int)$row["id"];
		$this->ip = $row["ip"];
		$this->linkId = (int)$row["link_id"];
		$this->useragent = $row["useragent"];
		$this->createdAt = $row["created_at"];
	}

	public function prepareFetch(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
select * from visits where id = ?
QUERY
		);

		$q->bind_param("i", $this->id);

		return $q;
	}

	protected function prepareInsert(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
insert into visits (ip, link_id, useragent) values (?, ?, ?)
QUERY
		);
		$q->bind_param("sis", $this->ip, $this->linkId, $this->useragent);

		return $q;
	}

	protected function prepareUpdate(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
update visits set ip = ?, link_id = ?, useragent = ? where id = ?
QUERY
		);
		$q->bind_param("sisi", $this->ip, $this->linkId, $this->useragent, $this->id);

		return $q;
	}

	protected function prepareDelete(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
delete from visits where id = ?
QUERY
		);
		$q->bind_param("i", $this->id);

		return $q;
	}

	/**
	 * @return string
	 */
	public function getIp(): string {
		return $this->ip;
	}

	/**
	 * @param string $ip
	 */
	public function setIp(string $ip): void {
		$this->ip = $ip;
	}

	/**
	 * @return string
	 */
	public function getUseragent(): string {
		return $this->useragent;
	}

	/**
	 * @param string $useragent
	 */
	public function setUseragent(string $useragent): void {
		$this->useragent = $useragent;
	}

	/**
	 * @return string
	 */
	public function getCreatedAt(): string {
		return $this->createdAt;
	}

	/**
	 * @param string $createdAt
	 */
	public function setCreatedAt(string $createdAt): void {
		$this->createdAt = $createdAt;
	}

	/**
	 * @return int
	 */
	public function getLinkId(): int {
		return $this->linkId;
	}

	/**
	 * @param int $linkId
	 */
	public function setLinkId(int $linkId): void {
		$this->linkId = $linkId;
	}
}