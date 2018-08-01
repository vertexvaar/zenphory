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
            echo "<a href=\"?file=$file\">$file</a><br>";
        }

        if (isset($_GET['file'])) {
            if (in_array($_GET['file'], $files)) {

                $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
                $node = $parser->parse(file_get_contents($_GET['file']));

                echo $_GET['file'] . PHP_EOL;

                var_dump($node);

                return $node;
            }
        }

        return null;
    }
}
