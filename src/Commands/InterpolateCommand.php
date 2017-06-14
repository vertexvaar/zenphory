<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Commands;

use PhpParser\ParserFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use VerteXVaaR\Zenphory\Service\Interpolator;

class InterpolateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('interpolate')
            ->setDescription('Inspect and reduce code')
            ->addArgument('source', InputArgument::REQUIRED, 'Source directory to run through')
            ->addArgument('target', InputArgument::REQUIRED, 'Directory to write to');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');
        $target = $input->getArgument('target');
        $output->writeln('Interpolation of ' . $source);
        $interpolator = new Interpolator($source, $target);
        $interpolator->run();
    }
}
