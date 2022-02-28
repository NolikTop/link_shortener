<?php

declare(strict_types=1);


namespace noliktop\linkShortener\utils;


use noliktop\linkShortener\route\LinkRouter;

class Url {

	public static function get(): string {
		$host = $_SERVER['HTTP_HOST'];
		$requestUri = $_SERVER['PHP_SELF'];

		$path = LinkRouter::getPath();

		if ($path === "" && !self::endsWith($requestUri, ".php")) {
			$requestUri .= "/."; // костыль чтобы dirname эту часть снес
		}


		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
			$protocol = "https";
		} else {
			$protocol = "http";
		}

		return dirname("$protocol://{$host}{$requestUri}") . "/";
	}

	private static function endsWith(string $haystack, string $needle): bool {
		return substr($haystack, -strlen($needle)) === $needle;
	}

}