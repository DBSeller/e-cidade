<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19580AdicionandoCampoT64Valorresidual extends Migration
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
            insert into configuracoes.db_syscampo
            values (1013636, 't64_valorresidual', 'int4', 'Porcentagem do valor Residual do bem.', '0', 'Valor Residual(%)', 10, 't', 'f', 'f', 1,
                    'text', 'Valor Residual(%)');
            insert into configuracoes.db_sysarqcamp values(925,1013636,10,0);
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        alter table patrimonio.clabens add column t64_valorresidual integer default null;
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        alter table patrimonio.clabens drop column t64_valorresidual;
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            delete from configuracoes.db_sysarqcamp where codcam = 1013636;
            delete from configuracoes.db_syscampo where codcam = 1013636;
SQL
        );
    }
}
