<?php

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
	echo 'Install dependencies (dev) by running `composer update --dev`';
	exit(1);
}
require __DIR__ . '/../vendor/autoload.php';

// configure environment
Tester\Environment::setup();
Tester\Dumper::$dumpDir = __DIR__ . '/output';
Tracy\Debugger::$maxLen = 999;

function d($var) {
	$var = Tracy\Dumper::toTerminal($var, [
		Tracy\Dumper::DEPTH => Tracy\Debugger::$maxDepth,
		Tracy\Dumper::TRUNCATE => Tracy\Debugger::$maxLen,
		Tracy\Dumper::LOCATION => Tracy\Debugger::$showLocation,
	]);
	echo $var;
	return $var;
}
