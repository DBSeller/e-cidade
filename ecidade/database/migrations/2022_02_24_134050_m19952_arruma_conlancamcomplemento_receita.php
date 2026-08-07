<?php

use ECidade\V3\Extension\Registry;
use ECidade\V3\Extension\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19952ArrumaConlancamcomplementoReceita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $fakeRequest = new Request();
        Registry::set('app.request', $fakeRequest);
        $_SESSION["DB_acessado"] = 228592;
        $_SESSION["DB_datausu"] = time();
        $_SESSION["DB_login"] = "dbseller";
        $_SESSION["DB_id_usuario"] = "1";
        $_SESSION["DB_coddepto"] = 1;
        $_SESSION["DB_instit"] = 1;
        $_SESSION["DB_desativar_account"] = true;
        $_SERVER['REQUEST_URI'] = 'localhost';

        require_once __DIR__ . "/../../libs/db_stdlib.php";
        require_once __DIR__ . "/../../libs/db_utils.php";
        require_once __DIR__ . "/../../libs/db_app.utils.php";
        require_once __DIR__ . "/../../libs/db_conecta_cli.php";
        require_once __DIR__ . "/../../dbforms/db_funcoes.php";

        db_query("select public.fc_putsession('DB_instit', '1')");

        $lancamentos = DB::select("
select c70_codlan, c70_anousu, c53_tipo, c71_coddoc, c53_descr
  from contabilidade.conlancam
  join conlancamrec on conlancamrec.c74_codlan = conlancam.c70_codlan
  join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
  join conhistdoc on conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
 where c70_anousu in (2021, 2022)
   and exists (select 1 from contabilidade.conlancamrecurso where c130_conlancam = c70_codlan)
   and not exists (select 1 from conlancamcomplementorecurso where o201_codlan = c70_codlan)
 order by c70_codlan
        ");

        foreach ($lancamentos as $lancamento) {
            $_SESSION['DB_anousu'] = $lancamento->c70_anousu;
            $complementoRecurso = new \ECidade\Financeiro\Contabilidade\LancamentoContabil\ComplementoRecurso();
            $complementoRecurso->processar($lancamento->c70_codlan, $lancamento->c70_anousu);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
