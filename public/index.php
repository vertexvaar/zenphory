<?php
declare(strict_types=1);
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../settings.php');
$interpolator = new \VerteXVaaR\Zenphory\Service\Interpolator(__DIR__ . '/../data/source', __DIR__ . '/../data/target');
$interpolator->run();
