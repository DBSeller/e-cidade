<?php
namespace ECidade\Console\Command\Extension;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use \ECidade\V3\Extension\ConsoleColor as Color;

class Pack extends Command
{
    protected function configure()
    {
        $this
            ->setName('extension:pack')
            ->setDescription('Package extension')
            ->setHelp('Generate a package from extension');

        $this->addArgument('name', InputArgument::REQUIRED, 'Extension name');
        $this->addArgument('output', InputArgument::OPTIONAL, 'Output path');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output_name = $input->getArgument('output');
        $output_directory = getcwd();

        $path = realpath($name);

        if (!is_dir($path)) {
            $path = realpath(ECIDADE_EXTENSION_PACKAGE_PATH . $name);
        }

        if (!is_dir($path)) {
            throw new \Exception("Diretorio de extensao nao existe: $name");
        }

        $executar = function($sComando) {

            $sArquivoLog = '/tmp/executar.output';
            exec($sComando . ' 2> ' . $sArquivoLog, $aRetorno, $iStatus);

            if ($iStatus > 0) {
                throw new Exception(trim(file_get_contents($sArquivoLog)));
            }

        };

        $name = basename($path);
        $base = dirname($path);
        $output = empty($output_name) ? mb_strtolower($name) . '-package.tar.gz' : $output_name;

        echo " - Compactando extensão: $path\n";
        $executar("cd '$base' && tar -zcf '$output' '$name' && mv -f '$output' '$output_directory'");
        echo " - Pacote gerado: $output_directory/$output\n";

        $processUser = posix_getpwuid(posix_geteuid());
        $group = posix_getgrgid($processUser['uid']);
        if ($group['name'] != 'www-data') {
            echo Color::set(
                "\n\nUsuário atual não está no grupo www-data\n".
                " - Apos rodar comando atualize as permissões para o grupo www-data\n".
                " - Ou execute o commando com 'sudo -H -u www-data COMMAND\n",
                'brown'
            );
        }

        echo "\n memory: " . round((memory_get_peak_usage(true)/1024)/1024, 2) . "mb\n\n";
        }

    }
