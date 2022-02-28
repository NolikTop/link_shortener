<?php

declare(strict_types=1);


namespace noliktop\linkShortener\route;


use Exception;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\entity\Link;

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
			echo "no such link";
			return;
		}

		$dstUrl = $link->getDestinationUrl();

		$ip = $_SERVER['REMOTE_ADDR'];
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		$link->addVisit($db, $ip, $useragent);

		header("Location: $dstUrl");
		die;
	}

}