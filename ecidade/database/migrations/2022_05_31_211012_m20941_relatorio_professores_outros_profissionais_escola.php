<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20941RelatorioProfessoresOutrosProfissionaisEscola extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upMenu();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downMenu();
    }

    public function upMenu()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228674 ,'Professores e outros profissionais da escola' ,'Professores e outros profissionais da escola' ,'edu2_professoresoutrosprofissionais001.php' ,'1' ,'1' ,'Professores e outros profissionais da escola' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 6871 ,228674 ,3 ,1100747 );
SQL
        );
    }

    public function downMenu()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228674 AND modulo = 1100747;
delete from db_itensmenu where id_item = 228674;
SQL
        );
    }
}
