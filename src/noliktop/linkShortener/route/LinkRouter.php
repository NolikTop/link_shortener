<?php

declare(strict_types=1);


namespace noliktop\linkShortener\route;


use Exception;
use noliktop\linkShortener\db\Mysql;
use noliktop\linkShortener\entity\Link;

class LinkRouter {

	public function __construct(){

	}

	public function getPath(): string{
		return $_GET["path"] ?? "";
	}

	/**
	 * @throws Exception
	 */
	public function handle(): void{
		$db = Mysql::get();

		$link = Link::fromShortLink($this->getPath(), $db);
		if(!isset($link)){
			echo "no link";
			return;
		}

		$dstUrl = $link->getDestinationUrl();
		header("Location: $dstUrl");
	}

}