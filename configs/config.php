<?php



define('DB_PATH', $_SERVER['DOCUMENT_ROOT'] . '/data/scores.sqlite');
define('TODAY', (new DateTime('now', new DateTimeZone('Europe/Brussels')))->format('M jS, Y'));
