<?php

declare(strict_types=1);


namespace noliktop\linkShortener\route;


use Exception;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\entity\link\Link;

class LinkRouter {

	public static function getPath(): string {
		return $_GET["path"] ?? "";
	}

	public static function isIndex(): bool {
		return self::getPath() === "";
	}

	/**
	 * @throws Exception
	 */
	public static function handle(): void {
		if (self::isIndex()) {
			return;
		}

		$db = Mysql::get();

		$link = Link::fromShortLink(self::getPath(), $db);
		if (!isset($link)) {
			http_response_code(404);
			die;
		}

		$dstUrl = $link->getDestinationUrl();

		$ip = self::getIp();
		$useragent = self::getUserAgent();

		$link->addVisit($ip, $useragent, $db);

		header("Location: $dstUrl");
		die;
	}

	public static function getIp(): string {
		return $_SERVER['REMOTE_ADDR'];
	}

	public static function getUserAgent(): string{
		return $_SERVER['HTTP_USER_AGENT'];
	}

}