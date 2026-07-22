<?php
namespace ECidade\Console\Command\Plugin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Install extends Command
{
    protected function configure()
    {
        $this
            ->setName('plugin:install')
            ->setDescription('Install plugin')
            ->setHelp('Install plugin');

        $this->addArgument('path', InputArgument::REQUIRED, 'Plugin package path');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("not yet");
    }
}
