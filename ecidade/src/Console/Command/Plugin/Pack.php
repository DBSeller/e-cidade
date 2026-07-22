<?php
namespace ECidade\Console\Command\Plugin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use \ECidade\V3\Extension\ConsoleColor as Color;

class Pack extends Command
{
    protected function configure()
    {
        $this
            ->setName('plugin:pack')
            ->setDescription('Package installed plugins')
            ->setHelp('Package installed plugins');

        $this->addOption('all', 'a', InputOption::VALUE_NONE, 'All plugin');
        $this->addOption('disabled', null, InputOption::VALUE_NONE, 'Only disabled plugins');
        $this->addOption('modification-without-plugin', null, InputOption::VALUE_NONE, 'Extract modifications without plugin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');

        $this->connect_db();

        $all = $input->getOption('all');
        $disabled = $input->getOption('disabled');
        $modificationWithoutPlugin = $input->getOption('modification-without-plugin');
        $packageName = 'plugins-package.tar.gz';
        $manifests = array();
        $repack = array();
        $files = array();
        $service = new \PluginService();
        $plugins = $service->getPlugins();

        echo "\n";

        foreach ($plugins as $data) {

            $manifests[$data->sNome] = $service->loadManifest("plugins/". $data->sNome ."/Manifest.xml");

            if ( !$all && ((!$disabled && !$data->lSituacao) || ($disabled && $data->lSituacao)) ) {
                continue;
            }

            $versao = Color::set('v' . ltrim($data->nVersao, 'v'), 'cyan');
            $situacao = $data->lSituacao ? Color::set('ativado', 'light_green') : Color::set('desativado', 'brown');
            $log_path = "tmp/" .$data->sNome . '.log';
            echo sprintf(" - %s %s %s\n", Color::set($data->sLabel, 'white'), $versao, $situacao);
            echo sprintf("   %s warnings | %s errors\n", Color::set($data->oErrosModificacoes->warning, 'brown'), Color::set($data->oErrosModificacoes->error, 'red'));
            if (file_exists(ECIDADE_PATH . $log_path)) {
                echo sprintf("   log %s\n", $log_path);
            }
            echo "\n";

            $repack[] =  $data->sNome;
        }

        if ($modificationWithoutPlugin) {

            $manifest_files = $this->manifest_extract_files($manifests);
            $modications = $this->manifest_extract_modifications($manifest_files);
            $modications_without_plugin = $this->modification_without_plugin($modications);

            printf(" Modificações sem plugins: %s\n", count($modications_without_plugin));

            foreach ($modications_without_plugin as $path) {
                printf(" - %s\n", $path);
            }

            echo "\n";
        }

        if (empty($repack) && ($modificationWithoutPlugin && empty($modications_without_plugin))) {
            echo " Nada para gerar pacote\n\n";
            return true;
        }

        echo " Criando pacote: ", count($repack), "\n";

        $workdir = tempnam(sys_get_temp_dir(), 'plugin_repack_');
        unlink($workdir);
        mkdir($workdir);

        foreach ($repack as $name) {
            $command = "cd plugins/$name && tar -zcf $name.tar.gz * && mv $name.tar.gz $workdir/";
            $this->exec($command);
        }

        if ($modificationWithoutPlugin && count($modications_without_plugin)) {
            $modications_without_plugin_package_name = 'modification-without-plugin.tar.gz';
            $command = sprintf(
                "tar -zcf %s %s && mv %s %s",
                $modications_without_plugin_package_name,
                implode(' ', $modications_without_plugin),
                $modications_without_plugin_package_name,
                $workdir
            );
            $this->exec($command);
        }

        $command = "cd $workdir && tar -zcf $packageName * && cp $packageName " . getcwd();
        $this->exec($command);

        echo " - pacote gerado: $packageName\n";
        echo "\n memory: " . round((memory_get_peak_usage(true)/1024)/1024, 2) . "mb\n\n";
    }

    private function files_extract_modifications($files)
    {
        return array_filter(
            $files,
            function($path)
            {
                return strpos($path, 'modification/xml/') === 0;
            }
        );
    }

    public function connect_db()
    {
        $HTTP_SERVER_VARS['HTTP_HOST'] = '';
        $HTTP_SERVER_VARS['PHP_SELF'] = '';
        require_once ECIDADE_PATH . 'libs/db_conn.php';
        require_once ECIDADE_PATH . 'libs/db_stdlib.php';

        if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
            throw new \Exception('Erro ao conectar com banco');
        }
        return true;
    }

    public function exec($command)
    {
        @exec($command . " 2>&1 > /dev/null", $output, $code);

        if ($code > 0) {
            throw new \Exception("Erro ao executar $command");
        }
        return true;
    }

    private function manifest_extract_files($manifests)
    {
        $files = array();

        foreach ($manifests as $manifest) {

            $name = (string) $manifest->plugin['name'];
            $files[$name] = array();

            foreach ($manifest->plugin->files->file as $file) {
                $files[$name][] = ltrim((string) $file['path'], '/');
            }
        }

        return $files;
    }

    private function manifest_extract_modifications($manifest_files)
    {
        $plugins_modifications = array();
        foreach($manifest_files as $manifest => $files) {
            foreach ($this->files_extract_modifications($files) as $file) {
                $plugins_modifications[] = $file;
            }
        }

        return $plugins_modifications;
    }

    private function modification_without_plugin($plugins_modifications)
    {
        $data = array();
        $modications = glob(ECIDADE_PATH . 'modification/xml/*.xml', GLOB_BRACE);

        foreach ($modications as $modication) {

            $path = str_replace(ECIDADE_PATH, null, $modication);
            if (!in_array($path, $plugins_modifications)) {
                $data[] = $path;
            }
        }

        return $data;
    }
}
