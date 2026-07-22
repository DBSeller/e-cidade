<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class T10108812ConferenciaRecadastramento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sSql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228632 ,'Conferência Recadastramento' ,'Conferência Recadastramento' ,'rec2_consultarecadastramento.php' ,'1' ,'1' ,'Consultas > Conferência Recadastramento' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,228632 ,194 ,2323 );
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sSql = <<<SQL
        delete from db_menu where id_item_filho = 228632;
        delete from db_itensmenu where id_item = 228632;
SQL;
    DB::connection()->getPdo()->exec($sSql);
    }
}
