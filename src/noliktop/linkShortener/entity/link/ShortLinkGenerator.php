<?php

declare(strict_types=1);


namespace noliktop\linkShortener\entity\link;


class ShortLinkGenerator {

	private const CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	public static function newShortLink(int $length = 6): string {
		$charsMaxIndex = strlen(self::CHARS) - 1;

		$result = "";
		for ($i = 0; $i < $length; ++$i) {
			$index = mt_rand(0, $charsMaxIndex);

			$result .= self::CHARS[$index];
		}

		return $result;
	}

}