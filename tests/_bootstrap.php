<?php

$projectPath = __DIR__ . '/../../../autoload.php';

if (file_exists($projectPath)) {
    require_once $projectPath;
} else {
    require_once __DIR__ . '/../vendor/autoload.php';
}

define('ROOT_DIR', __DIR__ . '/');
define('DATA_DIR', __DIR__ . '/_data');
define('TEMP_DIR', DATA_DIR . '/tmp');
define('DS', DIRECTORY_SEPARATOR);