<?php

declare(strict_types=1);

use noliktop\linkShortener\auth\Redirect;

require "autoload.php";

session_destroy();

Redirect::redirectIfNotRegistered();