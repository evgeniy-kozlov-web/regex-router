<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/routing/routes.php';

if ($router->check()) {
	echo "<h1>Page exists</h1>";
} else {
	echo "<h1>404 Error</h1>";
}
