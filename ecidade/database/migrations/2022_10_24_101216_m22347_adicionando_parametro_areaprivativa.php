<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22347AdicionandoParametroAreaprivativa extends Migration
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
        $sql = <<<SQL
                insert into configuracoes.db_syscampo values(1014558,'j18_utilizaareaprivativa','bool','Utiliza Área Privativa','f', 'Utiliza Área Privativa',1,'f','f','f',5,'text','Utiliza Área Privativa');
                insert into configuracoes.db_sysarqcamp values(153,1014558,39,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario()
    {
        $sql = <<<SQL
            delete from configuracoes.db_sysarqcamp where codcam = 1014558;
            delete from configuracoes.db_syscampo where codcam = 1014558;

SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function upEstrutura()
    {
        $sql = <<<SQL
            alter table cadastro.cfiptu add column j18_utilizaareaprivativa boolean default false;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function downEstrutura()
    {
        $sql = <<<SQL
            alter table cadastro.cfiptu drop column j18_utilizaareaprivativa;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
