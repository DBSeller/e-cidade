<?php
namespace ECidade\Console\Command\Plugin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use \ECidade\V3\Extension\ConsoleColor as Color;

class Unpack extends Command
{
    protected function configure()
    {
        $this
            ->setName('plugin:unpack')
            ->setDescription('Unpackage plugin')
            ->setHelp('Extract a package plugin');

        $this->addArgument('path', InputArgument::REQUIRED, 'Extension package path');
        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite package if exists');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');

        $path = realpath($input->getArgument('path'));
        $force = $input->getOption('force');

        if (!file_exists($path)) {
            throw new \Exception(sprintf('Arquivo inválido: %s', $this->getArgument('path')));
        }

        $packages = array();
        $temp = tempnam(sys_get_temp_dir(), 'plugin_repack_');
        $plugin_path_bkp = $temp . '/bkp/';
        unlink($temp);
        mkdir($temp);
        mkdir($plugin_path_bkp);

        $this->exec(sprintf( "cd %s && cp %s . && tar -zxf %s", $temp, $path, basename($path)));
        $this->exec(sprintf( "cd %s && rm %s", $temp, basename($path)));

        foreach (glob(sprintf("%s/*.tar.gz", $temp)) as $file) {

            $directory = $temp . '/plugins/' . basename($file, '.tar.gz');
            mkdir($directory, 0775, true);
            $this->exec(sprintf('cd %s && tar -zxf ../../%s', $directory, basename($file)));
            $packages[] = $directory;
        }

        echo "\n";

        if (empty($packages)) {

            echo " - nenhum pacote extraido\n";
            return;
        }

        echo " - Diretório temporario gerado: ", $temp,"\n";

        foreach ($packages as $directory) {

            $package = basename($directory);
            $plugin_path = ECIDADE_PATH . 'plugins/' . $package;
            $command = sprintf("cp -rf %s %s", $directory, $plugin_path);

            echo " - Extraindo plugin ", $package, "\n";

            if (file_exists($plugin_path) && !$force) {
                throw new \Exception("Plugin já extraido: " . $package);
            }
            if (file_exists($plugin_path)) {
                $output->writeln("   Diretório já existe, sobrescrevendo");
                $command = sprintf("mv %s %s && %s", $plugin_path, $plugin_path_bkp, $command);
            }

            $this->exec($command);
        }

        echo " - pacote extraido\n";
        echo "\n memory: " . round((memory_get_peak_usage(true)/1024)/1024, 2) . "mb\n\n";
    }

    public function exec($command)
    {
        @exec($command . " 2>&1 > /dev/null", $output, $code);

        if ($code > 0) {
            throw new \Exception("Erro ao executar $command");
        }
        return true;
    }
}
