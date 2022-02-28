<?php

declare(strict_types=1);


namespace noliktop\linkShortener\tip;


class TipRenderer {

	public static function render(): void {
		if (!isset($_SESSION["tip"])) return;

		foreach ($_SESSION["tip"] as [$type, $message]) {
			unset($_SESSION["tip"]);

			echo "<h3>$type: $message</h3>";
		}
	}

}