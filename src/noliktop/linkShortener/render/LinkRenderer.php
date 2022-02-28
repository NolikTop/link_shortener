<?php

declare(strict_types=1);


namespace noliktop\linkShortener\render;


use noliktop\linkShortener\entity\link\Link;
use noliktop\linkShortener\entity\visit\Visit;

class LinkRenderer {

	public static function renderLinkList(array $links): string{
		$amountOfLinks = count($links);

		$result = "<h1>Ваши короткие ссылки ($amountOfLinks)</h1>";
		foreach ($links as $link){
			$result .= LinkRenderer::renderLinkInList($link);
		}

		return $result;
	}

	public static function renderLinkInList(Link $link): string {
		$fullShortUrl = $link->getFullShortLink();
		$destinationUrl = $link->getDestinationUrl();
		$linkId = $link->getId();

		return <<<HTML
	<h3>Ссылка #$linkId</h3>
	<p>
		<a href="$fullShortUrl" target="_blank">
			$fullShortUrl
		</a>
		->
		<a href="$destinationUrl" target="_blank">
			$destinationUrl
		</a>
	</p>
	<a href="link.php?id=$linkId">Подробнее</a>
	<br/>
HTML;
	}

	public static function renderLinkWithVisits(Link $link, array $visits): string {
		$t = self::renderLinkBaseInformation($link);
		$t .= self::renderVisits($visits);

		return $t;
	}

	protected static function renderLinkBaseInformation(Link $link): string{
		$linkId = $link->getId();
		$fullShortLink = $link->getFullShortLink();
		$destinationUrl = $link->getDestinationUrl();

		return <<<HTML
<h1>Ссылка #$linkId</h1>
<p>
	Короткая ссылка:
	<a href="$fullShortLink" target="_blank">
		$fullShortLink
	</a>
</p>
<p>Исходная ссылка:
	<a href="$destinationUrl" target="_blank">
		$destinationUrl
	</a>
</p>
HTML;
	}

	/**
	 * @param Visit[] $visits
	 * @return string
	 */
	protected static function renderVisits(array $visits): string{
		$t = self::renderVisitsHeader($visits);

		foreach ($visits as $visit) {
			$t .= self::renderVisit($visit);
		}

		return $t;
	}

	protected static function renderVisitsHeader(array $visits): string{
		$amountOfVisits = count($visits);

		return <<<HTML
<h2>Посещения ($amountOfVisits)</h2>
HTML;
	}

	protected static function renderVisit(Visit $visit): string{
		$visitId = $visit->getId();
		$createdAt = $visit->getCreatedAt();
		$ip = $visit->getIp();
		$useragent = $visit->getUseragent();
		return <<<HTML
<h3>Посещение #$visitId в $createdAt</h3>
	<p>IP: $ip</p>
	<p>UserAgent: $useragent</p>
	<br/>
HTML;

	}

}