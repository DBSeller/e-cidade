<?php
namespace ECidade\Console\Command\Tools;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DiffCache extends Command
{
    protected function configure()
    {
        $this
            ->setName('tools:diff-cache')
            ->setDescription('Diff modification cache')
            ->setHelp('Diff modification cache');

        $this->addArgument('paths', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'paths to diff with modification cache');
        $this->addOption('raw', null, InputOption::VALUE_NONE, 'Raw output format');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');

        $files = $input->getArgument('paths');
        $raw = $input->getOption('raw');

        try {

            foreach($files as $file) {
                if ($raw) {
                    $this->diff($file);
                    continue;
                }
                $this->vimdiff($file);
            }

        } catch (Exception $error) {

            echo "\n message: ". $error->getMessage(). "\n";
            echo "\n trace: \n". $error->getTraceAsString(). "\n";
        }

        echo "\n memory: " . round((memory_get_peak_usage(true)/1024)/1024, 2) . "mb\n\n";
    }

    private function diff($file)
    {
        echo " - not yet\n";
    }

    private function vimdiff($file)
    {
        echo PHP_EOL, $file, PHP_EOL;

        $relative = str_replace(ECIDADE_PATH, null, $file);
        $file = ECIDADE_PATH . $relative;

        if (!file_exists($file)) {
            printf(" - file not exists %s", $relative);
            return false;
        }

        $args = glob(ECIDADE_MODIFICATION_CACHE_PATH . '**/' . $relative);
        array_unshift($args, $file);
        array_unshift($args, '-d');

        if (count($args) < 3) {
            printf(" - file without caches %s\n", $relative);
            return false;
        }

        $pid = pcntl_fork();
        switch ($pid) {
        case 0 :
            pcntl_exec('/usr/bin/vim', $args);
            break;
        default :
            pcntl_waitpid($pid, $status);
            break;
        }
    }
}
