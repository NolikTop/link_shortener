<?php

declare(strict_types=1);


namespace noliktop\linkShortener\tip;


class Tip {

	public static function info(string $message): void {
		self::add("info", $message);
	}

	public static function success(string $message): void {
		self::add("success", $message);
	}

	public static function danger(string $message): void {
		self::add("danger", $message);
	}

	public static function error(string $message): void {
		self::add("error", $message);
	}

	private static function add(string $type, string $message): void {
		$_SESSION["tip"][] = [$type, $message];
	}

}