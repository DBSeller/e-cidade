<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20727CriacaoDaTabelaEmptiposervicoobra extends Migration
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
     * Estrutura da tabela
     *
     * @return void
     */
    public function upEstrutura()
    {
        $sql = <<<SQL

        -- cria tabela
        CREATE TABLE IF NOT EXISTS empenho.emptiposervicoobra (
	        e154_sequencial serial4 NOT NULL,
	        e154_numemp int4 NOT NULL,
	        e154_tipo int4 NOT NULL,
	        e154_label varchar(100) NOT NULL,
	        e154_cno varchar(50) NULL,
	        CONSTRAINT emptiposervicoobra_pk PRIMARY KEY (e154_sequencial),
	        CONSTRAINT emptiposervicoobra_fk FOREIGN KEY (e154_numemp) REFERENCES empenho.empempenho(e60_numemp) ON DELETE CASCADE
        );

        -- cria auditoria
        SELECT configuracoes.fc_auditoria_cria_funcao('empenho.emptiposervicoobra');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Estrutura do dicionario de dados
     *
     * @return void
     */
    public function upDicionario()
    {
        $sql = <<<SQL

        insert into db_sysarquivo values (1010946, 'emptiposervicoobra', 'Tabela responsável por manter os tipo de serviço em obra de construção civil dos empenhos.', 'e154', '2022-06-27', 'Indicativo de tipo de serviço de obra', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (38,1010946);
        insert into db_syscampo values(1014223,'e154_sequencial','int4','Sequencial da tabela emptiposervicoobra','0', 'Sequencial',4,'f','f','f',1,'text','Sequencial');
        insert into db_syscampo values(1014224,'e154_numemp','int4','Sequencial da tabela empempenho','0', 'Sequencial do empenho',4,'f','f','f',1,'text','Sequencial do empenho');
        insert into db_syscampo values(1014225,'e154_tipo','int4','Indicativo de tipo de serviço de obra','0', 'tipo de serviço de obra',2,'f','f','f',3,'text','tipo de serviço de obra');
        insert into db_syscampo values(1014226,'e154_label','varchar(100)','Descrição do indicativo de serviço de obra.','', 'Descrição do tipo de serviço de obra',100,'f','t','f',0,'text','Descrição do tipo de serviço de obra');
        insert into db_syscampo values(1014227,'e154_cno','varchar(50)','CNO do prestador ou contribuinte de acordo com o indicativo do tipo de serviço.','', 'CNO do tipo de serviço ',50,'f','t','f',0,'text','CNO do tipo de serviço ');
        insert into db_sysarqcamp values(1010946,1014223,1,0);
        insert into db_sysarqcamp values(1010946,1014224,2,0);
        insert into db_sysarqcamp values(1010946,1014225,3,0);
        insert into db_sysarqcamp values(1010946,1014226,4,0);
        insert into db_sysarqcamp values(1010946,1014227,5,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010946,1014223,1,1014223);
        insert into db_sysforkey values(1010946,1014224,1,889,0);
        insert into db_syssequencia values(1001072, 'emptiposervicoobra_e154_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1001072 where codarq = 1010946 and codcam = 1014223;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    /**
     * Rollback da estrutura da tabela
     *
     * @return void
     */
    public function downEstrutura()
    {
        $sql = <<<SQL

        -- drop tabela e sequencia
        DROP TABLE IF EXISTS empenho.emptiposervicoobra;
        DROP SEQUENCE IF EXISTS empenho.emptiposervicoobra_e154_sequencial_seq;

        -- desabilita auditoria
        SELECT configuracoes.fc_auditoria_remove_funcao('empenho.emptiposervicoobra');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Rollback do dicionario de dados
     *
     * @return void
     */
    public function downDicionario()
    {
        $sql = <<<SQL

        delete from db_syssequencia where codsequencia = 1001072;
        delete from db_sysforkey where codarq = 1010946;
        delete from db_sysprikey where codarq = 1010946;
        delete from db_sysarqcamp where codarq = 1010946;
        delete from db_syscampo where codcam between 1014223 and 1014227;
        delete from db_sysarqmod where codmod = 38 and codarq = 1010946;
        delete from db_sysarquivo where codarq = 1010946;
SQL;
        DB::connection()->getPdo()->exec($sql);
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
}
