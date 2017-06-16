<?php
declare(strict_types=1);
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../settings.php');
$source = __DIR__ . '/../data/source/variables.php';
if (!file_exists(__DIR__ . '/../data/target/variables.php')) {
    mkdir(__DIR__ . '/../data/target/variables.php');
}
$target = __DIR__ . '/../data/target/variables.php';
$code = file_get_contents($source);
$codeBender = new \VerteXVaaR\Zenphory\Service\CodeBender($source);
$code = $codeBender->process($code);
file_put_contents($target, $code);
?>
Done
