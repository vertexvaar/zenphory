<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Service;

use PhpParser\ParserFactory;
use VerteXVaaR\Zenphory\Updated\Scanner;

class Printer
{
    /**
     *
     */
    public function all()
    {
        $scanner = new Scanner();
        $files = $scanner->scanDirectoryRecursive(__DIR__ . '/../../data/fixtures/');

        foreach ($files as $file) {
            $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
            $node = $parser->parse(file_get_contents($file));

            echo $file . PHP_EOL;

            var_dump($node);
        }
    }
}
