<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity;


use mysqli;

class User implements Entity {

	/** @var int */
	protected $id;

	/** @var string */
	protected $login;

	/** @var string */
	protected $passwordHash;

	public function load(array $row): void {
		$this->id = (int)$row["id"];
		$this->login = $row["login"];
		$this->passwordHash = $row["password_hash"];
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
	public function getLogin(): string {
		return $this->login;
	}

	/**
	 * @param string $login
	 */
	public function setLogin(string $login): void {
		$this->login = $login;
	}

	/**
	 * @return string
	 */
	public function getPasswordHash(): string {
		return $this->passwordHash;
	}

	/**
	 * @param string $passwordHash
	 */
	public function setPasswordHash(string $passwordHash): void {
		$this->passwordHash = $passwordHash;
	}
}