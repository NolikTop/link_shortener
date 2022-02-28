<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity\user;


use mysqli;
use mysqli_stmt;
use noliktop\linkShortener\auth\Auth;
use noliktop\linkShortener\auth\Password;
use noliktop\linkShortener\entity\Entity;
use noliktop\linkShortener\entity\EntityException;
use noliktop\linkShortener\entity\link\Link;
use noliktop\linkShortener\table\TableException;

class User extends Entity {

	/** @var string */
	protected $login;

	/** @var string */
	protected $passwordHash;

	/**
	 * @throws EntityException
	 */
	public static function create(string $login, string $password, mysqli $db): User {
		$user = new User();
		$user->login = $login;
		$user->passwordHash = Password::hash($password);

		$user->insert($db);

		return $user;
	}

	/**
	 * @throws UserException
	 * @throws EntityException
	 */
	public static function getCurrent(mysqli $db): User {
		if (!Auth::isLogged()) {
			throw new UserException("No user in this session");
		}

		$userId = $_SESSION["user_id"];

		$user = new User();
		$user->loadById($userId, $db);

		return $user;
	}

	/**
	 * @throws UserException
	 */
	public static function getByLogin(string $login, mysqli $db): User{
		$q = $db->prepare(<<<QUERY
select * from users where login = ?
QUERY
		);

		$q->bind_param("s", $login);

		if (!$q->execute()) {
			throw new UserException("Couldn't find user by login $login: $db->error");
		}

		$result = $q->get_result();
		if ($result->num_rows === 0) {
			throw new UserException("No user with login $login");
		}

		$user = new User();
		$t = $result->fetch_assoc();
		$user->loadFromRow($t);

		return $user;
	}

	protected function loadFromRow(array $row): void {
		$this->id = (int)$row["id"];
		$this->login = $row["login"];
		$this->passwordHash = $row["password_hash"];
	}

	public function prepareLoadById(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
select * from users where id = ?
QUERY
		);
		$q->bind_param("i", $this->id);

		return $q;
	}

	protected function prepareInsert(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
insert into users (login, password_hash) values (?, ?)
QUERY
		);

		$q->bind_param("ss", $this->login, $this->passwordHash);

		return $q;
	}

	protected function prepareUpdate(mysqli $db): mysqli_stmt {
		$q = $db->prepare(<<<QUERY
update users set login = ?, password_hash = ? where id = ?
QUERY
		);

		$q->bind_param("ssi", $this->login, $this->passwordHash, $this->id);

		return $q;
	}

	protected function prepareDelete(mysqli $db): mysqli_stmt {
		$q = $db->prepare("delete from users where id = ?");

		$q->bind_param("i", $this->id);

		return $q;
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
		/** @noinspection PhpAssignmentInConditionInspection */
		while ($t = $result->fetch_assoc()) {
			$links[] = $l = new Link();
			$l->loadFromRow($t);
		}

		return $links;
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