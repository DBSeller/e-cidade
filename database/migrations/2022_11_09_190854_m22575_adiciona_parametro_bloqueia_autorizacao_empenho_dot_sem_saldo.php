<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22575AdicionaParametroBloqueiaAutorizacaoEmpenhoDotSemSaldo extends Migration
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
insert into configuracoes.db_syscampo values(1014585,'pc30_bloqueiaautemp','bool','Parâmetro para ativar/desativar o bloqueio na Autorização de empenho, quando não houver reserva de dotação na Solicitação de Compras.','f', 'Verifica se existe reserva de saldo',1,'f','f','f',5,'text','Verifica se existe reserva de saldo');
insert into configuracoes.db_sysarqcamp values(1058,1014585,49,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
    public function downDicionario()
    {
        $sql = <<<SQL
            delete from configuracoes.db_sysarqcamp where codcam = 1014585;
            delete from configuracoes.db_syscampo where codcam = 1014585;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upEstrutura()
    {
        $sql = <<<SQL
            alter table compras.pcparam add column pc30_bloqueiaautemp boolean;
            update compras.pcparam set pc30_bloqueiaautemp = 'f';
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
    public function downEstrutura()
    {
        $sql = <<<SQL
            alter table compras.pcparam drop column pc30_bloqueiaautemp;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
