<?php

if (!file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    echo 'Install dependencies (dev) by running `composer update --dev`';
    exit(1);
}
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/Themes/TestCase.php';

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', __DIR__);

// configure environment
Tester\Environment::setup();
class_alias('Tester\Assert', 'Assert');

// create temporary directory
define('TEMP_DIR', __DIR__ . '/../temp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
Tester\Helpers::purge(TEMP_DIR);

function id($val)
{
    return $val;
}

function run(Tester\TestCase $testCase)
{
    $testCase->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : null);
}