<?php

declare(strict_types=1);


namespace noliktop\linkShortener\auth;


use noliktop\linkShortener\entity\User;

class Redirect {

	public static function redirectIfRegistered(): void {
		if (User::isLogged()) {
			header("Location: index.php");
		}
	}

	/**
	 * @throws AuthException
	 */
	public static function redirectIfNotRegistered(): void {
		if (User::isLogged()) return;

		header("Location: login.php");
		throw new AuthException("You are not registered");
	}

}