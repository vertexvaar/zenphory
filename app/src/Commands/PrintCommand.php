<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Commands;

use PhpParser\ParserFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VerteXVaaR\Zenphory\Service\Printer;
use VerteXVaaR\Zenphory\Updated\Scanner;

class PrintCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('print')
            ->setDescription('Print the tokenized structure');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $printer = new Printer();
        $printer->all();
    }
}
