<?php
declare(strict_types=1);
require_once(__DIR__ . '/../vendor/autoload.php');

echo '<pre>';
$printer = new \VerteXVaaR\Zenphory\Service\Printer();
$printer->all();
