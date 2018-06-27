<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VerteXVaaR\Zenphory\Updated\Scanner;

class InterpolateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('interpolate')
            ->setDescription('Inspect and reduce code');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanner = new Scanner();
        $files = $scanner->scanDirectoryRecursive(__DIR__ . '/../../data/source/');
        $codeBender = new \VerteXVaaR\Zenphory\Service\CodeBender();

        foreach ($files as $file) {
            $target = __DIR__ . '/../../data/target/' . substr($file, strlen(__DIR__ . '/../../data/source/'));
            $folder = dirname($target);
            if (!file_exists($folder)) {
                mkdir($folder, 0775, true);
            }
            $code = file_get_contents($file);
            $code = $codeBender->process($code);
            file_put_contents($target, $code);
        }
    }
}
