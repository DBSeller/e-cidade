<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20525AlteracaoDeNomesMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            update db_itensmenu set id_item = 228373 , descricao = 'Re-enviar Eventos para o eSocial' , help = 'Rotina de reenvio dos eventos do eSocial' , funcao = 'eso01_agendamentoenvio.php?reenvio=true' , itemativo = '1' , manutencao = '1' , desctec = 'Rotina de reenvio dos eventos do eSocial' , libcliente = 'true' where id_item = 228373;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
            update db_itensmenu set id_item = 228373 , descricao = 'Re-enviar Eventos para o eSocial' , help = 'Rotina de reenvio dos eventos do eSocial' , funcao = 'eso01_agendamentoenvioforcado.php' , itemativo = '1' , manutencao = '1' , desctec = 'Rotina de reenvio dos eventos do eSocial' , libcliente = 'true' where id_item = 228373;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
