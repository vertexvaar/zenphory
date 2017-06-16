<?php
declare(strict_types=1);
namespace VerteXVaaR\Zenphory\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $source = __DIR__ . '/../../data/source/variables.php';
        if (!file_exists(__DIR__ . '/../../data/target/')) {
            mkdir(__DIR__ . '/../../data/target/');
        }
        $target = __DIR__ . '/../../data/target/variables.php';
        $code = file_get_contents($source);
        $codeBender = new \VerteXVaaR\Zenphory\Service\CodeBender($source);
        $code = $codeBender->process($code);
        file_put_contents($target, $code);
    }
}
