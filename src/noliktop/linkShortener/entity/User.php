<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity;


use mysqli;
use noliktop\linkShortener\auth\Auth;
use noliktop\linkShortener\table\TableException;

class User implements Entity {

	/** @var int */
	protected $id;

	/** @var string */
	protected $login;

	/** @var string */
	protected $passwordHash;

	public function __construct(int $id = 0) {
		$this->id = $id;
	}

	/**
	 * @throws TableException
	 */
	public static function create(string $login, string $password, mysqli $db): User {
		$user = new User();
		$user->login = $login;
		$user->passwordHash = Auth::hashPassword($password);

		$user->insert($db);

		return $user;
	}

	/**
	 * @throws UserException
	 */
	public static function getCurrent(mysqli $db): User {
		if (!self::isLogged()) {
			throw new UserException("No user in this session");
		}

		$userId = $_SESSION["user_id"];

		$user = new User($userId);
		$user->loadById($db);

		return $user;
	}

	public static function isLogged(): bool {
		return session_status() === PHP_SESSION_ACTIVE && isset($_SESSION["user_id"]);
	}

	public function load(array $row): void {
		$this->id = (int)$row["id"];
		$this->login = $row["login"];
		$this->passwordHash = $row["password_hash"];
	}

	/**
	 * @throws UserException
	 */
	public function loadById(mysqli $db): void {
		$q = $db->prepare(<<<QUERY
select * from users where id = ?
QUERY
		);
		$q->bind_param("i", $this->id);

		if (!$q->execute()) {
			throw new UserException("Cant load: $db->error");
		}

		$result = $q->get_result();

		if ($result->num_rows === 0) {
			throw new UserException("No user with id $this->id");
		}

		$this->load($result->fetch_assoc());
	}

	/**
	 * @throws TableException
	 */
	public function insert(mysqli $db): void {
		$q = $db->prepare(<<<QUERY
insert into users (login, password_hash) values (?, ?)
QUERY
		);

		$q->bind_param("ss", $this->login, $this->passwordHash);

		if (!$q->execute()) {
			throw new TableException("Cant insert: $db->error");
		}

		$this->id = $db->insert_id;
	}

	/**
	 * @throws TableException
	 */
	public function update(mysqli $db): void {
		$q = $db->prepare(<<<QUERY
update users set login = ?, password_hash = ? where id = ?
QUERY
		);

		$q->bind_param("ssi", $this->login, $this->passwordHash, $this->id);

		if (!$q->execute()) {
			throw new TableException("Cant update: $db->error");
		}
	}

	/**
	 * @throws TableException
	 */
	public function delete(mysqli $db): void {
		$q = $db->prepare("delete from users where id = ?");

		$q->bind_param("i", $this->id);

		if (!$q->execute()) {
			throw new TableException("Cant delete: $db->error");
		}
	}

	/**
	 * @return Link[]
	 * @throws TableException
	 */
	public function getLinks(mysqli $db): array {
		$q = $db->prepare(<<<QUERY
select l.* from links l 
    inner join users u on l.owner_id = u.id
where u.id = ?
QUERY
		);

		$q->bind_param("i", $this->id);

		if (!$q->execute()) {
			throw new TableException("Cant insert: $db->error");

		}

		$result = $q->get_result();

		$links = [];
		while ($t = $result->fetch_assoc()) {
			$links[] = $l = new Link();
			$l->load($t);
		}

		return $links;
	}

	/**
	 * @return int
	 * @throws UserException
	 */
	public function getId(): int {
		if ($this->id === 0) {
			throw new UserException("No id");
		}
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