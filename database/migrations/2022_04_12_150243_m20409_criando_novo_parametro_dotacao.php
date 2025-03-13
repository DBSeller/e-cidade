<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20409CriandoNovoParametroDotacao extends Migration
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

    public function upDicionario()
    {
        $sql = <<<SQL
                insert into configuracoes.db_syscampo values(1013982,'pc30_mostrasaldo','bool','Mostrar o saldo da dotação','f', 'Mostrar o saldo da dotação',1,'f','f','f',5,'text','Mostrar o saldo da dotação');
                insert into configuracoes.db_sysarqcamp values(1058,1013982,47,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upEstrutura()
    {
        $sql = <<<SQL
                alter table compras.pcparam add column pc30_mostrasaldo boolean;
                update compras.pcparam set pc30_mostrasaldo = 'f';
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downDicionario()
    {
        $sql = <<<SQL
            delete from configuracoes.db_sysarqcamp where codcam = 1013982;
            delete from configuracoes.db_syscampo where codcam = 1013982;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downEstrutura()
    {
        $sql = <<<SQL
            alter table compras.pcparam drop column pc30_mostrasaldo;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
