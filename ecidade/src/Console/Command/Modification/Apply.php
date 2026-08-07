<?php
namespace ECidade\Console\Command\Modification;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use \ECidade\V3\Extension\ConsoleColor as Color;
use \ECidade\V3\Modification\Manager;
use \ECidade\V3\Extension\Logger;
use \ECidade\V3\Modification\Data\Modification as ModificationDataModification;


class Apply extends Command
{
    protected function configure()
    {
        $this
            ->setName('modification:apply')
            ->setDescription('Apply modification')
            ->setHelp('Apply modification parses on original files');

        $this->addArgument('path', InputArgument::REQUIRED, 'Modification path');
        $this->addOption('check-syntax', null, InputOption::VALUE_NONE, 'Check file syntax');
        $this->addOption('debug', null, InputOption::VALUE_NONE, 'Debug mode, will not persist');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');

        $checkSintax = $input->getOption('check-syntax');
        $debug = $input->getOption('debug');

        $file = realpath($input->getArgument('path'));
        $user = 'apply_' . mt_rand();
        $startTime = microtime(true);

        $manager = new Manager();
        $logger = $manager->getLogger();
        $logger->setVerbosity(Logger::DEBUG);
        $logger->addHandler(function($output, $level) {

            switch($level) {

            case Logger::DEBUG:
                $output = Color::set($output, 'light_gray');
                break;

            case Logger::WARNING:
                $output = Color::set($output, 'brown');
                break;

            case Logger::ERROR:
                $output = Color::set($output, 'red');
                break;

            }

            return $output;
        });
        $files = array();
        $filesPath = ECIDADE_MODIFICATION_CACHE_PATH . 'user' . DS . $user . DS;
        $backupPath = ECIDADE_PATH . 'tmp' . DS . 'bkp_' . $user . DS;
        $data = $manager->unpack($file, true);
        if (false === $data->isUserType()) {
            $data->setType(ModificationDataModification::TYPE_USER);
            $data->save();
        }
        $manager->apply($data->getId(), $user);

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($filesPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $path => $fileObj) {

            $from = $path;
            $to = ECIDADE_PATH . str_replace($filesPath, '', $path);

            if (!is_writeable($to) || !is_writeable(dirname($to))) {
                throw new \Exception("Sem permissão para escrever arquivo/diretorio: $to");
            }

            $files[$from] = $to;
        }

        $logger->debug('salvando backup em ' . $backupPath);

        foreach ($files as $from => $to) {

            $backup = str_replace($filesPath, $backupPath, $from);

            if (!is_dir(dirname($backup))) {
                mkdir(dirname($backup), 0775, true);
            }

            if (!$debug && !copy($to, $backup) ) {
                throw new \Exception("Erro ao salvar backup do arquivo: '$backup'");
            }
        }

        foreach ($files as $from => $to) {

            // check file syntax
            if ($checkSintax) {

                $toSyntax = $this->check_syntax($to);
                if (!$toSyntax) {
                    $logger->error("arquivo com erro de syntax: " . str_replace($filesPath, '', $from));
                    continue;
                }
            }

            $logger->debug("copiando arquivo " . str_replace($filesPath, '', $from));
            if (!$debug && !copy($from, $to)) {
                throw new \Exception("Erro ao copiar arquivo '$from' para '$to'");
            }
        }

        $manager->uninstall($data->getId(), $user);
        $logger->debug('removendo arquivo de cache da modificação: ' . basename($data->getPath()));
        $data->remove();

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

        echo "\n time: " .  round((microtime(true) - $startTime), 2);
        echo "\n memory: " . round((memory_get_peak_usage(true)/1024)/1024, 2) . "mb\n\n";
    }

    private function check_syntax($filepath)
    {
        if (pathinfo($filepath, PATHINFO_EXTENSION) != 'php') {
            return true;
        }

        $code = 0;
        $output = null;
        exec("php -l '$filepath' 2> /dev/null", $output, $code);

        return $code === 0;
    }
}
