<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20487 extends Migration
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
insert into db_syscampo values(1014009,'e69_outrosdados','text','Outros dados relativo a notas fiscais','', 'Outros Dados',1,'f','f','f',0,'text','Outros Dados');
insert into db_sysarqcamp values(971,1014009,13,0);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_sysarqcamp where codarq = 971 and codcam = 1014009;
delete from db_syscampo where codcam = 1014009;
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
alter table empenho.empnota add column e69_outrosdados jsonb default null;
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
alter table empenho.empnota drop column e69_outrosdados;
SQL
        );
    }
}
