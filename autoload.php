<?php

declare(strict_types=1);

spl_autoload_register(function (string $className): void{
	$className = str_replace("\\", "/", $className);

	include __DIR__ . "/src/$className.php";
});