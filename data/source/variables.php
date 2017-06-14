<?php
declare(strict_types=1);

$name = 'Ernesto';
$age = 45;
$version = '9.8.55';
$equality = true;

if (true === $equality) {
    $notUnused = 'some';
    if ('something' === ($notUnused . ($ending = 'thing'))) {
        echo 'Version: ' . $version . ':' . $name . ' age ' . $age;
    }
}
