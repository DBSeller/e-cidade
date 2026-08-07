<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20528CriandoNovoParametroValidaDept extends Migration
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
                    insert into configuracoes.db_syscampo values(1014035,'pc30_validadept','bool','Validar departamento na manutenção de reserva ','f', 'Validar dept na manutenção de reserva',1,'t','f','f',5,'text','Validar dept na manutenção de reserva');
                    insert into configuracoes.db_sysarqcamp values(1058,1014035,48,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario()
    {
        $sql = <<<SQL
            delete from configuracoes.db_sysarqcamp where codcam = 1014035;
            delete from configuracoes.db_syscampo where codcam = 1014035;

SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function upEstrutura()
    {
        $sql = <<<SQL
            alter table compras.pcparam add column pc30_validadept boolean;
            update compras.pcparam set pc30_validadept = 't';
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function downEstrutura()
    {
        $sql = <<<SQL
            alter table compras.pcparam drop column pc30_validadept;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
