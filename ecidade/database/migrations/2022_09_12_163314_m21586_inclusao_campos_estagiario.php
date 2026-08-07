<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21586InclusaoCamposEstagiario extends Migration
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
        insert into configuracoes.db_syscampo values(1014486,'rh260_areaatuacao','varchar(100)','Área de atuação do estagiário ou, no caso de prestação de serviço civil voluntário, jornada semanal do desempenho de atividades em formato decimal.','', 'Área de Atuação:',100,'t','t','f',0,'text','Área de Atuação:');
        insert into configuracoes.db_syscampo values(1014487,'rh260_apoliceseguro','varchar(30)','Número da apólice de seguro.','', 'Nº Apólice Seguro',30,'t','t','f',0,'text','Nº Apólice Seguro');
        insert into configuracoes.db_syscampo values(1014488,'rh260_cpfsupervisor','varchar(11)','CPF do responsável pela supervisão do estagiário.','', 'CPF Supervisor Estagiário',11,'t','t','f',0,'text','CPF Supervisor Estagiário');
        
        insert into configuracoes.db_sysarqcamp values(1010893,1014488,8,0);
        insert into configuracoes.db_sysarqcamp values(1010893,1014487,9,0);
        insert into configuracoes.db_sysarqcamp values(1010893,1014486,10,0);
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }

    private function downDicionario()
    {
        $sql = <<<SQL
        delete from configuracoes.db_sysarqcamp where codarq = 1010893 and codcam in (1014486, 1014487, 1014488);
        delete from configuracoes.db_syscampo where codcam in (1014486, 1014487, 1014488);
        
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }

    private function upEstrutura()
    {
        $sql = <<<SQL
        ALTER TABLE recursoshumanos.rhestagiovinculo ADD rh260_areaatuacao varchar(100) NULL;
        ALTER TABLE recursoshumanos.rhestagiovinculo ADD rh260_apoliceseguro varchar(30) NULL;
        ALTER TABLE recursoshumanos.rhestagiovinculo ADD rh260_cpfsupervisor varchar(11) NULL;

SQL;
        DB::connection()->getPdo()->exec($sql);       
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
        ALTER TABLE recursoshumanos.rhestagiovinculo DROP rh260_areaatuacao;
        ALTER TABLE recursoshumanos.rhestagiovinculo DROP rh260_apoliceseguro;
        ALTER TABLE recursoshumanos.rhestagiovinculo DROP rh260_cpfsupervisor;
       
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }
}
