<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity;


use mysqli;

class Visit implements Entity {

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

	public function getId(): int {
		return $this->id;
	}

	public function load(array $row): void {
		$this->id = (int)$row["id"];
		$this->ip = $row["ip"];
		$this->linkId = (int)$row["link_id"];
		$this->useragent = $row["useragent"];
		$this->createdAt = $row["created_at"];
	}

	/**
	 * @throws VisitException
	 */
	public function loadById(mysqli $db): void {
		$q = $db->prepare(<<<QUERY
select * from visits where id = ?
QUERY
		);

		$q->bind_param("i", $this->id);

		if (!$q->execute()) {
			throw new VisitException("Coudln't load by id $this->id: $db->error");
		}

		$result = $q->get_result();
		if ($result->num_rows === 0) {
			throw new VisitException("No visit with id $this->id");
		}

		$this->load($result->fetch_assoc());
	}

	/**
	 * @throws VisitException
	 */
	public function insert(mysqli $db): void {
		$q = $db->prepare(<<<QUERY
insert into visits (ip, link_id, useragent) values (?, ?, ?)
QUERY
		);
		$q->bind_param("sis", $this->ip, $this->linkId, $this->useragent);

		if (!$q->execute()) {
			throw new VisitException("Db error: $db->error");
		}
	}

	public function update(mysqli $db): void {
		$q = $db->prepare(<<<QUERY
update visits set ip = ?, link_id = ?, useragent = ? where id = ?
QUERY
		);
		$q->bind_param("sisi", $this->ip, $this->linkId, $this->useragent, $this->id);

		if (!$q->execute()) {
			throw new VisitException("Db error: $db->error");
		}
	}

	public function delete(mysqli $db): void {
		$q = $db->prepare(<<<QUERY
delete from visits where id = ?
QUERY
		);
		$q->bind_param("i", $this->id);

		if (!$q->execute()) {
			throw new VisitException("Db error: $db->error");
		}
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