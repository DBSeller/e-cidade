<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use ECidade\V3\Extension\Request;
use ECidade\V3\Extension\Registry;
use Illuminate\Support\Facades\DB;
use ECidade\V3\Datasource\Database;
use Illuminate\Support\Facades\Config;

class ControleParcelamentoVencidoTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tributario:parcelamentovencido';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para alterar data de vencimento de parcelas vencidas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->requireDependencies();

        try {
            $service = new \App\Domain\Tributario\Arrecadacao\Services\ControleParcelamentoVencidoService();
            $service->execute();
            file_put_contents('tmp/controleparcelamentovencidotas.log', '', FILE_APPEND);
        } catch (Exception $e) {
            $time = date('d/m/Y H:i:s');
            $log = "[{$time}] {$e->getMessage()} " . PHP_EOL;
            file_put_contents('tmp/controleparcelamentovencidotas.log', $log, FILE_APPEND);
        }
        
        return true;
    }

    private function requireDependencies()
    {
        session_start();
        $fakeRequest = new Request();
        Registry::set('app.request', $fakeRequest);

        $_SESSION["DB_acessado"] = 228592;
        $_SESSION["DB_datausu"] = time();
        $_SESSION["DB_login"] = "dbseller";
        $_SESSION["DB_id_usuario"] = "1";
        $_SESSION["DB_coddepto"] = 1;
        $_SESSION["DB_instit"] = 1;

        $_SERVER['REQUEST_URI'] = 'localhost';

        require_once modification('libs/db_stdlib.php');
        require_once modification('libs/db_utils.php');
        require_once modification('libs/db_app.utils.php');
        require_once modification('libs/db_conecta_cli.php');
        require_once modification('dbforms/db_funcoes.php');

        db_query("select public.fc_putsession('DB_instit', '1')");
    }
}
