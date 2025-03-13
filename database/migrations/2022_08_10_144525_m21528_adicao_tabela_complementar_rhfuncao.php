<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21528AdicaoTabelaComplementarRhfuncao extends Migration
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
            insert into configuracoes.db_sysarquivo values (1010980, 'rhfuncaooutrosdados', 'Guarda informações adicionais da tabela rhfuncao', 'rh267', '2022-08-10', 'Informações Complementares', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (28,1010980);
            insert into configuracoes.db_syscampo values(1014435,'rh267_dados','text','Guarda a informação complementares referentes a tabela rhfuncao','', 'json',1,'f','f','f',0,'text','json');
            insert into configuracoes.db_syscampo values(1014436,'rh267_codigo','int8','Sequencial da tabela','0', 'Sequencial da tabela',8,'f','f','f',1,'text','Sequencial da tabela');
            insert into configuracoes.db_syscampo values(1014437,'rh267_rhfuncao','int8','Chave estrangeira que vincula a tabela a rhfuncao','0', 'Chave Estrangeira',8,'f','f','f',1,'text','Chave Estrangeira');
            insert into configuracoes.db_syscampo values(1014438,'rh267_instit','int4','Chave Estrangeira','0', 'Chave Estrangeira',2,'f','f','f',1,'text','Chave Estrangeira');
            insert into configuracoes.db_sysarqcamp values(1010980,1014438,1,0);
            insert into configuracoes.db_sysarqcamp values(1010980,1014437,2,0);
            insert into configuracoes.db_sysarqcamp values(1010980,1014436,3,0);
            insert into configuracoes.db_sysarqcamp values(1010980,1014435,4,0);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010980,1014436,1,1014436);
            insert into configuracoes.db_sysforkey values(1010980,1014438,1,1174,0);
            insert into configuracoes.db_sysforkey values(1010980,1014437,2,1174,0);
            insert into configuracoes.db_syssequencia values(1001086, 'rhfuncaooutrosdados_rh267_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001086 where codarq = 1010980 and codcam = 1014436;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario()
    {
        $sql = <<<SQL
            delete from configuracoes.db_syssequencia where codsequencia = 1001086;
            delete from configuracoes.db_sysforkey where codarq = 1010980 and codcam in ('1014438', '1014437');
            delete from configuracoes.db_sysprikey where codarq = 1010980 and codcam = 1014436;
            delete from configuracoes.db_sysarqcamp where codarq = 1010980 and codcam in ('1014435', '1014436', '1014437', '1014438');
            delete from configuracoes.db_syscampo where codcam in ('1014435', '1014436', '1014437', '1014438');
            delete from configuracoes.db_sysarqmod where codmod = 28 and codarq = 1010980;
            delete from configuracoes.db_sysarquivo where codarq = 1010980; 
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura()
    {
        $sql = <<<SQL
        create table pessoal.rhfuncaooutrosdados(
                rh267_codigo int PRIMARY KEY,
                rh267_dados jsonb,
                rh267_rhfuncao int,
                rh267_instit int,
                CONSTRAINT fk_rhfuncao
                    FOREIGN KEY(rh267_rhfuncao, rh267_instit) REFERENCES pessoal.rhfuncao(rh37_funcao, rh37_instit)
        );
        create sequence pessoal.rhfuncaooutrosdados_rh267_codigo_seq START 1;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
            drop sequence pessoal.rhfuncaooutrosdados_rh267_codigo_seq;
            drop table pessoal.rhfuncaooutrosdados;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
