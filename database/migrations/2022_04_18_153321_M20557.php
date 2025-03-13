<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20557 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228645 ,'Implantação MSC' ,'Implantação MSC' ,'con4_implantacaomsc001.php' ,'1' ,'1' ,'Implantação MSC' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10510 ,228645 ,3 ,209 );
SQL
        );
    }

    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228645 AND modulo = 209;
delete from db_itensmenu where id_item = 228645;
SQL
        );
    }
}
