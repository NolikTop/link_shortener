<?php

declare(strict_types=1);


namespace noliktop\linkShortener\auth;


use mysqli;
use noliktop\linkShortener\entity\User;
use noliktop\linkShortener\tip\Tip;

class Auth {

	public static function hashPassword(string $pass): string {
		return hash("sha256", $pass, true);
	}

	public static function hashEquals(string $pass, string $hash): bool {
		$passHash = self::hashPassword($pass);

		return hash_equals($passHash, $hash);
	}

	public static function tryLogin(string $login, string $password, mysqli $db, string $failureUrl): void {
		try {
			self::logIn($login, $password, $db);
		} catch (AuthException $e) {
			Tip::error($e->getMessage());
			header("Location: $failureUrl");
		}
	}

	/**
	 * @throws AuthException
	 */
	public static function logIn(string $login, string $password, mysqli $db): void {
		$q = $db->prepare(<<<QUERY
select * from users where login = ?
QUERY
		);

		$q->bind_param("s", $login);

		if (!$q->execute()) {
			throw new AuthException("Couldn't find user by login $login: $db->error");
		}

		$result = $q->get_result();
		if ($result->num_rows === 0) {
			throw new AuthException("No user with login $login");
		}

		$t = $result->fetch_assoc();
		$passHashFromDb = $t["password_hash"];

		if (!self::hashEquals($password, $passHashFromDb)) {
			throw new AuthException("Wrong password");
		}

		$userId = (int)$t["id"];
		if ($userId === 0) {
			throw new AuthException("Wrong id of user");
		}

		$_SESSION["user_id"] = $userId;
	}

	public static function tryRegister(string $login, string $password, mysqli $db, string $failureUrl): void {
		try {
			self::register($login, $password, $db);
		} catch (AuthException $e) {
			Tip::error($e->getMessage());
			header("Location: $failureUrl");
		}
	}

	public static function register(string $login, string $password, mysqli $db): void {
		$passLen = strlen($password);
		if ($passLen < 3 || $passLen > 64) {
			throw new AuthException("Wrong size for password");
		}

		$loginLen = strlen($login);
		if ($loginLen < 3 || $loginLen > 32) {
			throw new AuthException("Wrong size for login");
		}

		$user = User::create($login, $password, $db);

		$_SESSION["user_id"] = $user->getId();
	}

}