<?php

declare (strict_types=1);
$name = 'Ernesto';
$age = 45;
if (rand(0, 1)) {
    $version = '9.8.55';
} else {
    $version = '9.9.55';
}
$equality = true;
if (true === $equality) {
    $notUnused = 'some';
    if ('something' === ($notUnused . ($ending = 'thing'))) {
        echo 'Version: ' . $version . ': ' . $name . ' age ' . $age;
    }
}
