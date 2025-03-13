<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22943ItemMenuPermissoesMenuSaude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228824 ,'Duplica permissão de menu (Saúde)' ,'Duplica permissão de menu da saúde' ,'con4_duplicapermissaosaude.php' ,'1' ,'1' ,'Duplica permissão dos menu da saúde.' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228824 ,566 ,1 );
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
        delete from db_menu where id_item_filho = 228824;
        delete from db_itensmenu where id_item = 228824;
SQL
        );
    }
}
