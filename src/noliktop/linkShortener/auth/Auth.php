<?php

declare(strict_types=1);


namespace noliktop\linkShortener\auth;


use mysqli;
use noliktop\linkShortener\entity\EntityException;
use noliktop\linkShortener\entity\user\User;
use noliktop\linkShortener\entity\user\UserException;
use noliktop\linkShortener\table\TableException;
use noliktop\linkShortener\tip\Tip;
use Throwable;

class Auth {


	public static function tryLogin(string $login, string $password, string $failureUrl, mysqli $db): void {
		try {
			self::logIn($login, $password, $db);
		} catch (Throwable $e) {
			Tip::error($e->getMessage());
			header("Location: $failureUrl");
		}
	}

	/**
	 * @throws AuthException
	 * @throws UserException
	 */
	public static function logIn(string $login, string $password, mysqli $db): void {
		$user = User::getByLogin($login, $db);

		$userPasswordHash = $user->getPasswordHash();
		if (!Password::check($password, $userPasswordHash)) {
			throw new AuthException("Wrong password");
		}

		$_SESSION["user_id"] = $user->getId();
	}

	public static function tryRegister(string $login, string $password, string $failureUrl, mysqli $db): void {
		try {
			self::register($login, $password, $db);
		} catch (Throwable $e) {
			Tip::error($e->getMessage());
			header("Location: $failureUrl");
		}
	}

	/**
	 * @throws AuthException
	 * @throws EntityException
	 */
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

	public static function isLogged(): bool {
		return session_status() === PHP_SESSION_ACTIVE && isset($_SESSION["user_id"]);
	}

}