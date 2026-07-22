<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ECidade\V3\Extension\Registry;
use ECidade\V3\Extension\Request;

class InativarVinculoProfessor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agendamento:inativarvinculoprofessor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execução do processamento na qual desvincula o professor de todas '.
    'os registros na data de seu desligamento da Função Exercida';

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
        require_once(modification("edu4_inativarvinculoprofessor001.php"));
    }

    private function requireDependencies()
    {
        session_start();
        $fakeRequest = new Request();
        Registry::set('app.request', $fakeRequest);

        $_SERVER['REQUEST_URI'] = 'localhost';

        $_SESSION["DB_login"] = "dbseller";
        $_SESSION["DB_id_usuario"] = "1";
        
        require_once(modification("libs/db_stdlib.php"));
        require_once(modification("libs/db_utils.php"));
        require_once(modification("libs/db_conecta.php"));
        require_once(modification("dbforms/db_funcoes.php"));
        require_once(modification("std/DBFtp.model.php"));


        $_SESSION["DB_usuario"] = $DB_USUARIO;
        $_SESSION["DB_senha"] = $DB_SENHA;
        $_SESSION["DB_servidor"] = $DB_SERVIDOR;
        $_SESSION["DB_base"] = $DB_BASE;
        $_SESSION["DB_porta"] = $DB_PORTA;
    }
}
