<?php

declare(strict_types=1);

spl_autoload_register(function (string $className): void{
	include __DIR__ . "/src/$className.php";
});