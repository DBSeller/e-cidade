<?php
namespace ECidade\Console\Command\Extension;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ECidade\V3\Extension\Data as ExtensionData;
use ECidade\V3\Modification\Manager as ModificationManager;
use ECidade\V3\Modification\Data\Modification as ModificationData;
use ECidade\V3\Modification\Parse\Operation as ModificationOperation;

class Status extends Command
{
    protected function configure()
    {
        $this
            ->setName('extension:status')
            ->setDescription('Status of extensions')
            ->setHelp('Status of all installed extensions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = glob(ECIDADE_EXTENSION_DATA_PATH . 'extension/*.data');

        $output->writeln(sprintf("\n Extensões instaladas: %s\n", count($paths)));

        foreach ($paths as $path) {

            $id = preg_replace('/(.*)\/(.*).data/', '$2', $path);
            $data = ExtensionData::restore($id);
            $output->write(sprintf(" - %s <blue>%s</blue>", $data->getId(), $data->getVersion()));

            if ($data->isUserType()) {
                echo $output->writeln("");
                foreach($data->getUsersStatus() as $user => $status) {
                    $output->writeln(sprintf("   %s(%s)", $data->isEnabled($user) ? '<green>ativa</green>' : '<yellow>desativada</yellow>', $user));
                }
            } else {
                $output->write(sprintf(" %s", $data->isEnabled() ? '<green>ativa</green>' : '<yellow>desativada</yellow>'));
            }

            $output->writeln("");

            $modifications = $this->get_modifications($id);

            $output->writeln(sprintf("   modifications: %s", count(array_merge($modifications['user'], $modifications['global']))));

            foreach($modifications['global'] as $id => $modification) {
                $output->write(sprintf("   - %s: %s", $id, $modification['status'] ? '<green>ativa</green>' : '<yellow>desativada</yellow>'));
                if ($modification['errors']['warning'] > 0) {
                    $output->write(sprintf(" -- <yellow>%s</yellow> warnings", $modification['errors']['warning']));
                }
                if ($modification['errors']['error'] > 0) {
                    $output->write(sprintf(" | <red>%s</red> errors\n", $modification['errors']['error']));
                }
                $output->writeln("");
            }

            foreach($modifications['user'] as $id => $data) {
                foreach ($data as $user => $modification) {
                    $output->write(
                        sprintf(
                            "   - %s(%s): %s",
                            $id,
                            $user,
                            $modification['status'] ? '<green>ativa</green>' : '<yellow>desativada</yellow>'
                        )
                    );
                    if ($modification['errors']['warning'] > 0) {
                        $output->write(sprintf(" -- <yellow>%s</yellow> warnings", $modification['errors']['warning']));
                    }
                    if ($modification['errors']['error'] > 0) {
                        $output->write(sprintf(" | <red>%s</red> errors\n", $modification['errors']['error']));
                    }
                    $output->writeln("");
                }
            }

            $output->writeln("");
        }

        $output->writeln("");
    }

    private function get_modifications($id)
    {
        $data = array();
        $modifications = array();
        $files = array();
        $path = ECIDADE_EXTENSION_PACKAGE_PATH . $id;
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($iterator as $file) {
            if ($file->isDir() || $file->getExtension() != 'xml') {
                continue;
            }
            $files[] = $file->getPathname();
        }

        foreach ($files as $file) {
            $dom = new \DOMDocument('1.0');
            @$dom->load($file);
            if (!empty($dom->documentElement) && $dom->documentElement->tagName == 'modification') {
                $modifications[] = $file;
            }
        }

        return $this->modifications_instaled($modifications);
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

                if (!$modification->exists()) {
                    continue;
                }

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

}
