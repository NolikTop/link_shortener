<?php

declare(strict_types=1);


namespace noliktop\linkShortener\auth;


class Redirect {

	public static function redirectIfRegistered(): void {
		if (Auth::isLogged()) {
			header("Location: index.php");
		}
	}

	/**
	 * @throws AuthException
	 */
	public static function redirectIfNotRegistered(): void {
		if (Auth::isLogged()) return;

		header("Location: login.php");
		throw new AuthException("You are not registered");
	}

}