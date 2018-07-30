<?php
$files = [
    'wheee.txt',
    'keys.bak',
    'id_rsa.pub',
    'indexy' => 'TexHex',
];

foreach ($files as $file) {
    echo $file;
}

$keyFile = $files[2];
$keyFile .= 'asd';
