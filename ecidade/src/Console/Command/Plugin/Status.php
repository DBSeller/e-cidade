<?php
namespace ECidade\Console\Command\Plugin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use \ECidade\V3\Extension\ConsoleColor as Color;
use \ECidade\V3\Modification\Manager as ModificationManager;
use \ECidade\V3\Modification\Data\Modification as ModificationData;
use \ECidade\V3\Modification\Parse\Operation as ModificationOperation;

class Status extends Command
{
    protected function configure()
    {
        $this
            ->setName('plugin:status')
            ->setDescription('Status of installed plugins')
            ->setHelp('Show current status of installed plugins');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');

        $this->connect_db();

        $manifests = array();
        $service = new \PluginService();
        $plugins = $service->getPlugins();
        $plugins_instalados = array();

        echo "\n Plugins instaladados: ", count($plugins), "\n\n";

        foreach ($plugins as $data) {
            $manifests[$data->sNome] = $service->loadManifest("plugins/". $data->sNome ."/Manifest.xml");
        }

        foreach ($plugins as $data) {

            $manifest_files = $this->manifest_extract_files(array($manifests[$data->sNome]));
            $modications = $this->manifest_extract_modifications($manifest_files);
            $modications_instaled = $this->modifications_instaled($modications);

            $versao = $this->color('v' . ltrim($data->nVersao, 'v'), 'cyan');
            $situacao = $data->lSituacao ? $this->color('ativado', 'light_green') : $this->color('desativado', 'brown');
            $log_path = "tmp/" .$data->sNome . '.log';

            printf(" - %s %s %s\n", $this->color($data->sLabel, 'white'), $versao, $situacao);

            if (file_exists(ECIDADE_PATH . $log_path)) {
                printf("   log %s\n", $log_path);
            }
            if (count($modications) > 0) {

                printf("   modifications: %s", count($modications));

                if ($data->lSituacao) {
                    printf(" -- %s warnings | %s errors", $this->color($data->oErrosModificacoes->warning, 'brown'), $this->color($data->oErrosModificacoes->error, 'red'));
                }

                echo "\n";

                foreach($modications_instaled['global'] as $id => $modification) {
                    printf("   - %s: %s", $id, $modification['status'] ? $this->color('ativa', 'light_green') : $this->color('desativada', 'brown'));
                    if ($modification['errors']['warning'] > 0) {
                        printf(" -- %s warnings", $this->color($modification['errors']['warning'], 'brown'));
                    }
                    if ($modification['errors']['error'] > 0) {
                        printf(" | %s errors\n", $this->color($modification['errors']['error'], 'red'));
                    }
                    printf("\n");
                }

                foreach($modications_instaled['user'] as $id => $data) {
                    foreach ($data as $user => $modification) {
                        printf(" - %s(%s): %s\n", $id, $this->color($user, 'white'), $modification['status'] ? $this->color('ativa', 'light_green') : $this->color('desativada', 'brown'));
                        if ($modification['errors']['warning'] > 0) {
                            printf(" -- %s warnings", $this->color($modification['errors']['warning'], 'brown'));
                        }
                        if ($modification['errors']['error'] > 0) {
                            printf(" | %s errors\n", $this->color($modification['errors']['error'], 'red'));
                        }
                        printf("\n");
                    }
                }
            }
            echo "\n";
        }

        $manifest_files = $this->manifest_extract_files($manifests);
        $modications = $this->manifest_extract_modifications($manifest_files);
        $modications_without_plugin = $this->modification_without_plugin($modications);

        printf(" Modificações sem plugins: %s\n", count($modications_without_plugin));

        foreach ($modications_without_plugin as $path) {
            printf(" - %s\n", $path);
        }

        echo "\n\n memory: " . round((memory_get_peak_usage(true)/1024)/1024, 2) . "mb\n\n";
    }

    private function color($text, $color)
    {
        return Color::set($text, $color);
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

    private function modification_parse($path)
    {
        $manager = new ModificationManager();
        $parse = $manager->parse($path);
        return ModificationData::restore($parse->getId());
    }

    private function modifications_instaled($modications)
    {
        $data = array(
            'global' => array(),
            'user' => array(),
            'parse_error' => array(),
        );

        foreach ($modications as $path) {

            try {

                if (!file_exists($path)) {
                    continue;
                }

                $modification = $this->modification_parse($path);
                $errors = $this->modification_extract_errros($modification);

                if ($modification->isUserType()) {
                    foreach ($modification->getUsersStatus() as $user => $status) {
                        $data['user'][$modification->getId()][$user] = array(
                            'status' => $modification->isEnabled($user),
                            'errors' => $errors,
                        );
                    }
                    continue;
                }

                $data['global'][$modification->getId()] = array(
                    'status' => $modification->isEnabled(),
                    'errors' => $errors,
                );

            } catch (\Exception $e) {
                $data['parse_error'][$path] = $e->getMessage();
            }
        }

        return $data;
    }

    private function modification_extract_errros($modification)
    {
        $data = array(
            'error' => 0,
            'warning' => 0,
        );
        foreach($modification->getFilesErrors() as $file => $errors) {
            foreach($errors as $error) {
                if ($error['type'] === ModificationOperation::ERROR_ABORT) {
                    $data['error']++;
                }
                if ($error['type'] === ModificationOperation::ERROR_SKIP) {
                    $data['warning']++;
                }
            }
        }
        return $data;
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

    private function connect_db()
    {
        $HTTP_SERVER_VARS['HTTP_HOST'] = '';
        $HTTP_SERVER_VARS['PHP_SELF'] = '';
        require_once ECIDADE_PATH . 'libs/db_conn.php';
        require_once ECIDADE_PATH . 'libs/db_stdlib.php';

        if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
            throw new Exception('Erro ao conectar com banco');
        }
        return true;
    }

}
