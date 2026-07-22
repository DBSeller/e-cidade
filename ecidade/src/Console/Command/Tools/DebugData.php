<?php
namespace ECidade\Console\Command\Tools;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DebugData extends Command
{
    protected function configure()
    {
        $this
            ->setName('tools:debug-data')
            ->setDescription('Debug metadata')
            ->setHelp('Debug metadata');

        $this->addArgument('path', InputArgument::REQUIRED, 'Metadata path');
        $this->addOption('raw', null, InputOption::VALUE_NONE, 'Raw output format');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');

        $file = realpath($input->getArgument('path'));
        $raw = $input->getOption('raw');

        $raw_outputs = array(
            'extension/modification/data/file/file-type-modification.data' => 'rawFileTypeModification',
        );

        if (empty($file)) {
            throw new \Exception("Arquivo não informado.");
        }

        $file = realpath($file);
        $content = file_get_contents($file);
        $data = unserialize($content);

        if ($raw) {
            foreach ($raw_outputs as $curr_path => $handler) {
                if (strpos($file, $curr_path) !== false) {
                    return call_user_func(array($this, $handler), $data);
                }
            }
        }

        echo PHP_EOL . print_r($data, true) . PHP_EOL;

        echo "\n memory: " . round((memory_get_peak_usage(true)/1024)/1024, 2) . "mb\n\n";
    }

    private function rawFileTypeModification($data)
    {
        echo PHP_EOL;

        foreach ($data as $path => $profiles) {

            echo sprintf(" %s\n", $path);

            foreach ($profiles as $type => $modifications) {
                foreach ($modifications as $id) {
                    echo sprintf("   - %s\n", $id);
                }
            }

            echo PHP_EOL;
        }

        echo PHP_EOL;
    }
}
