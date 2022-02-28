<?php

declare(strict_types=1);


namespace noliktop\linkShortener\auth;


class Password {

	public static function hash(string $pass): string {
		return hash("sha256", $pass, true);
	}

	public static function check(string $pass, string $hash): bool {
		$passHash = self::hash($pass);

		return hash_equals($passHash, $hash);
	}

}