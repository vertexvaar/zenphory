<?php
declare(strict_types=1);
require_once(__DIR__ . '/../vendor/autoload.php');

error_reporting(E_ALL);

ini_set('display_errors', '1');

ini_set('xdebug.var_display_max_depth', '-1');
ini_set('xdebug.var_display_max_children', '-1');
ini_set('xdebug.var_display_max_data', '-1');

echo '<pre>';
$printer = new \VerteXVaaR\Zenphory\Service\Printer();
$printer->all();
