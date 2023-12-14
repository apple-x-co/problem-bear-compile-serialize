<?php

declare(strict_types=1);

use MyVendor\MyProject\Bootstrap;

session_name('bear');

require dirname(__DIR__) . '/autoload.php';
// exit((new Bootstrap())(PHP_SAPI === 'cli-server' ? 'hal-app' : 'prod-hal-app', $GLOBALS, $_SERVER));
exit((new Bootstrap())(((PHP_SAPI === 'cli-server' || getenv('APP_ENV') === 'local') ? '' : 'prod-') . (str_contains($_SERVER['HTTP_ACCEPT'], 'application/json') ? 'hal-api-app' : 'html-app'), $GLOBALS, $_SERVER));
