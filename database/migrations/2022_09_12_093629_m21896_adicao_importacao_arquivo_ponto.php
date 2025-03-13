<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21896AdicaoImportacaoArquivoPonto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228755 ,'Importar Ponto Mensal' ,'Importação de movimentação do ponto mensal' ,'pes4_importacaoponto001.php' ,'1' ,'1' ,'Importação de movimentação do ponto mensal' ,'false' );
        insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4504 ,228755 ,10 ,952 );
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
        delete from configuracoes.db_itensmenu where id_item = 228755;
        delete from configuracoes.db_menu where id_item = 4504 and id_item_filho = 228755;    
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
