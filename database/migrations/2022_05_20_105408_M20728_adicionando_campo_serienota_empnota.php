<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20728AdicionandoCampoSerienotaEmpnota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_syscampo values(1014135,'e69_serienota','varchar(15)','Série do campo da Nota Fiscal','','Série da nota fiscal',15,'t','t','f',0,'text','Série da nota fiscal');
            insert into db_sysarqcamp values(971,1014135,14,0);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            delete from db_sysarqcamp where codarq = 971 and codcam = 1014135;
            delete from db_syscampo where codcam = 1014135;
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            alter table empenho.empnota add column e69_serienota varchar(15) default null;
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            alter table empenho.empnota drop column e69_serienota;
SQL
        );
    }

}
