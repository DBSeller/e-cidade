<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20751AdicionandoCampoTipoDebitoTabelaConfissqnretidopublica extends Migration
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
               insert into db_syscampo values(1014070,'j170_tipo','int4','Tipo de débito para retenção de empresa pública','0', 'Tipo de Débito',11,'t','f','f',1,'text','Tipo de Débito');
               insert into db_sysarqcamp values(1010650,1014070,4,0);
               insert into db_sysforkey values(1010650,1014070,1,82,0);

               insert into db_syscampo values(1014071,'j170_hist','int4','Histórico para retenção de empresa pública','0', 'Histórico',11,'f','f','f',1,'text','Histórico');
               insert into db_sysarqcamp values(1010650,1014071,5,0);
               insert into db_sysforkey values(1010650,1014071,1,73,0);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario()
    {
        $sql = <<<SQL

        delete from db_sysforkey where codarq = 1010650 and referen = 82 and codcam = 1014070;
        delete from db_sysarqcamp where codarq = 1010650 and codcam = 1014070;
        delete from db_syscampo where codcam = 1014070;

        delete from db_sysforkey where codarq = 1010650 and referen = 73 and codcam = 1014071;
        delete from db_sysarqcamp where codarq = 1010650 and codcam = 1014071;
        delete from db_syscampo where codcam = 1014071;


SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura()
    {
        $sql = <<<SQL

        alter table issqn.confissqnretidopublica add column j170_hist integer;
        alter table issqn.confissqnretidopublica add column j170_tipo integer;  

        update issqn.confissqnretidopublica set j170_hist = (select w10_hist from db_confplan limit 1) where j170_hist is null;
        update issqn.confissqnretidopublica set j170_tipo = (select w10_tipo from db_confplan limit 1) where j170_tipo is null;

        alter table issqn.confissqnretidopublica add constraint confissqnretidopublica_histcalc_fk FOREIGN KEY (j170_hist)
references caixa.histcalc;

        alter table issqn.confissqnretidopublica add constraint confissqnretidopublica_arretipo_fk FOREIGN KEY (j170_tipo)
references caixa.arretipo;

        alter table issqn.confissqnretidopublica alter column j170_hist set not null;
        alter table issqn.confissqnretidopublica alter column j170_tipo set not null;  

SQL;
            DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura()
    {
        $sql = <<<SQL

        alter table issqn.confissqnretidopublica drop constraint confissqnretidopublica_histcalc_fk;
        alter table issqn.confissqnretidopublica drop constraint confissqnretidopublica_arretipo_fk;

        alter table issqn.confissqnretidopublica drop column j170_hist;
        alter table issqn.confissqnretidopublica drop column j170_tipo;  

   
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
