<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M22643MenuReenvioEfdreinf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228812 ,'Re-enviar Eventos para o EFD-Reinf' ,'Rotina de reenvio dos eventos do EFD-Reinf' ,'eso01_agendamentoenvio.php?integracao=1&reenvio=true' ,'1' ,'1' ,'Rotina de reenvio dos eventos do EFD-Reinf' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228812 ,564 ,228077 );
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            delete from db_menu where id_item_filho = 228812 AND modulo = 228077;
            delete from db_itensmenu where id_item = 228812;
SQL
        );
    }
}
