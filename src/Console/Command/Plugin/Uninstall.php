<?php
namespace ECidade\Console\Command\Plugin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Uninstall extends Command
{
    protected function configure()
    {
        $this
            ->setName('plugin:uninstall')
            ->setDescription('Uninstall plugin')
            ->setHelp('Uninstall plugin');

        $this->addArgument('name', InputArgument::REQUIRED, 'Plugin name');
        $this->addOption('disable', null, InputOption::VALUE_NONE, 'Disable plugin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->connect_db();

        $name = $this->find_by_name($input->getArgument('name'));
        $plugin = new \Plugin(null, $name);

        $service = new \PluginService();
        if ($input->getOption('disable')) {
            $service->desativar($plugin);
            $output->writeln("Plugin desativado");
        } else {
            $service->desinstalar($plugin);
            $output->writeln("Plugin desinstalado");
        }
    }

    private function find_by_name($name)
    {
        if (is_dir(ECIDADE_PATH . 'plugins/' . $name)) {
            return $name;
        }
        throw new \Exception("Plugin não encontrado: ". $name);
    }

    public function connect_db()
    {
        $HTTP_SERVER_VARS['HTTP_HOST'] = '';
        $HTTP_SERVER_VARS['PHP_SELF'] = '';
        require_once ECIDADE_PATH . 'libs/db_conn.php';
        require_once ECIDADE_PATH . 'libs/db_stdlib.php';

        global $_SESSION;
        if (!isset($_SESSION)) {
            $_SESSION = array();
        }
        $_SESSION['DB_desativar_account'] = true;
        $_SESSION['DB_id_usuario'] = 1;
        $_SESSION['DB_login'] = 'dbseller';
        $_SESSION["DB_base"] = $DB_BASE;
        $_SESSION["DB_NBASE"] = $DB_BASE;
        $_SESSION["DB_servidor"] = $DB_SERVIDOR;
        $_SESSION["DB_porta"] = $DB_PORTA;
        $_SESSION["DB_senha"] = $DB_SENHA;
        $_SESSION["DB_user"] = $DB_USUARIO;

        if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
            throw new \Exception('Erro ao conectar com banco');
        }
        return true;
    }

    private function emullate_session()
    {
    }
}
