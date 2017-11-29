<?php
declare(strict_types=1);
$candidates = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',
    getcwd() . '/../vendor/autoload.php',
    dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . '/vendor/autoload.php',
    dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))) . '/autoload.php'
];
foreach ($candidates as $candidate) {
    if (file_exists($candidate)) {
        require_once($candidate);
        break;
    }
}

require_once(__DIR__ . '/../settings.php');
$source = __DIR__ . '/../data/source/variables.php';
if (!file_exists(__DIR__ . '/../data/target/variables.php')) {
    mkdir(__DIR__ . '/../data/target/variables.php');
}
$target = __DIR__ . '/../data/target/variables.php';
$code = file_get_contents($source);
$codeBender = new \VerteXVaaR\Zenphory\Service\CodeBender();
$code = $codeBender->process($code);
file_put_contents($target, $code);
?>
Done
