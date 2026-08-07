<?php

use Illuminate\Database\Migrations\Migration;

class M21440EstruturaEventoR2055 extends Migration
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
     * Estrutura
     *
     * @return void
     */
    public function upEstrutura()
    {
        $sql = <<<SQL

        -- emptipoaquisicaoproducaorural
        CREATE TABLE IF NOT EXISTS empenho.emptipoaquisicaoproducaorural (
            e159_sequencial serial4 NOT NULL,
            e159_tipo int2 NULL,
            e159_label varchar(255) NULL,
            e159_empempenho int4 NULL,
            CONSTRAINT emptipoaquisicaoproducaorural_pk PRIMARY KEY (e159_sequencial)
        );
        CREATE INDEX emptipoaquisicaoproducaorural_e159_empempenho_idx ON empenho.emptipoaquisicaoproducaorural USING btree (e159_empempenho);

        -- retencaoreceitasprodutorrural
        CREATE TABLE IF NOT EXISTS empenho.retencaoreceitasprodutorrural (
	        e158_sequencial serial4 NOT NULL,
	        e158_vlrcp numeric(8, 2) NULL,
	        e158_vlrsenar numeric(8, 2) NULL,
	        e158_vlrrat numeric(8, 2) NULL,
	        e158_retencaoreceitas int4 NULL,
	        e158_empnota int4 NOT NULL,
	        CONSTRAINT retencaoreceitasprodutorrural_pk PRIMARY KEY (e158_sequencial),
	        CONSTRAINT retencaoreceitasprodutorrural_fk FOREIGN KEY (e158_empnota) REFERENCES empenho.empnota(e69_codnota) ON DELETE CASCADE
        );

        -- aquisicaoproducaoruralprocessos
        CREATE TABLE IF NOT EXISTS empenho.aquisicaoproducaoruralprocessos (
	        e157_retencaoreceitasprodutorrural int4 NOT NULL,
	        e157_nrprocjud varchar(100) NOT NULL,
	        e157_vlrcpnret numeric(8, 2) NULL,
	        e157_vlrratnret numeric(8, 2) NULL,
	        e157_vlrsenarnret numeric(8, 2) NULL,
	        e157_sequencial serial4 NOT NULL,
	        CONSTRAINT aquisicaoproducaoruralprocessos_pk PRIMARY KEY (e157_sequencial),
	        CONSTRAINT aquisicaoproducaoruralprocessos_fk FOREIGN KEY (e157_retencaoreceitasprodutorrural) REFERENCES empenho.retencaoreceitasprodutorrural(e158_sequencial) ON DELETE CASCADE
        );

        -- Auditorias
        SELECT configuracoes.fc_auditoria_cria_funcao('empenho.emptipoaquisicaoproducaorural');
        SELECT configuracoes.fc_auditoria_cria_funcao('empenho.retencaoreceitasprodutorrural');
        SELECT configuracoes.fc_auditoria_cria_funcao('empenho.aquisicaoproducaoruralprocessos');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Dicionario de dados
     *
     * @return void
     */
    public function upDicionario()
    {
        $sql = <<<SQL

        -- emptipoaquisicaoprodutorrural
        insert into db_sysarquivo values (1010973, 'emptipoaquisicaoproducaorural', 'Relaciona os empenhos de aquisição de produção rural e seu indicativo.', 'e159', '2022-07-21', 'Empenhos de aquisição de produção rural', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (38,1010973);
        insert into db_syscampo values(1014390,'e159_sequencial','int4','Tipo de aquisição em produção rural.','0', 'Tipo de aquisição em produção rural',10,'f','f','f',1,'text','Tipo de aquisição em produção rural');
        insert into db_syscampo values(1014386,'e159_tipo','int4','Tipo de aquisição em produção rural','0', 'Tipo de aquisição em produção rural',4,'f','f','f',1,'text','Tipo de aquisição em produção rural');
        insert into db_syscampo values(1014396,'e159_label','varchar(255)','Descrição da aquisição em produção rural','', 'Descrição da aquisição em produção rural',255,'f','f','f',0,'text','Descrição da aquisição em produção rural');
        insert into db_syscampo values(1014388,'e159_empempenho','int4','Sequencial do empenho relacionado.','0', 'Número do empenho',10,'t','f','f',1,'text','Número do empenho');
        insert into db_sysarqcamp values(1010973,1014390,1,0);
        insert into db_sysarqcamp values(1010973,1014396,2,0);
        insert into db_sysarqcamp values(1010973,1014386,3,0);
        insert into db_sysarqcamp values(1010973,1014388,4,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010973,1014390,1,1014390);
        insert into db_syssequencia values(1001082, 'emptipoaquisicaoproducaorural_e159_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1001082 where codarq = 1010973 and codcam = 1014390;

        -- retencaoreceitasprodutorrural
        insert into db_sysarquivo values (1010972, 'retencaoreceitasprodutorrural', 'Tabela responsável pelas retenções de aquisição de produção rural a serem prestadas para o evento R2055 do EFD-REINF.', 'e158', '2022-07-21', 'Retenção das aquisições de produção rural', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (38,1010972);
        insert into db_syscampo values(1014397,'e158_sequencial','int4','Sequencial retenção produtor rural','0', 'Sequencial retenção produtor rural',10,'f','f','f',1,'text','Sequencial retenção produtor rural');
        insert into db_syscampo values(1014398,'e158_vlrcp','float4','Valor CP ','0', 'Valor CP',8,'t','f','f',4,'text','Valor CP');
        insert into db_syscampo values(1014399,'e158_vlrsenar','float4','Valor Senar','0', 'Valor Senar',8,'t','f','f',4,'text','Valor Senar');
        insert into db_syscampo values(1014400,'e158_vlrrat','float4','Valor Rat','0', 'Valor Rat',8,'t','f','f',4,'text','Valor Rat');
        insert into db_syscampo values(1014401,'e158_retencaoreceitas','int4','Sequencial da retenção','0', 'Retenção',10,'t','f','f',1,'text','Retenção');
        insert into db_syscampo values(1014402,'e158_empnota','int4','Sequencial da nota de empenho','0', 'Nota de empenho',10,'f','f','f',1,'text','Nota de empenho');
        insert into db_sysarqcamp values(1010972,1014397,1,0);
        insert into db_sysarqcamp values(1010972,1014398,2,0);
        insert into db_sysarqcamp values(1010972,1014399,3,0);
        insert into db_sysarqcamp values(1010972,1014400,4,0);
        insert into db_sysarqcamp values(1010972,1014401,5,0);
        insert into db_sysarqcamp values(1010972,1014402,6,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010972,1014397,1,1014397);
        insert into db_sysforkey values(1010972,1014402,1,971,0);
        insert into db_sysforkey values(1010972,1014401,1,2116,0);
        insert into db_syssequencia values(1001083, 'retencaoreceitasprodutorrural_e158_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1001083 where codarq = 1010972 and codcam = 1014397;

        -- aquisicaoproducaoruralprocessos
        insert into db_sysarquivo values (1010974, 'aquisicaoproducaoruralprocessos', 'Processos das aquisições de produção rural', 'e157', '2022-07-21', 'Processos das aquisições de produção rural', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (38,1010974);
        insert into db_syscampo values(1014403,'e157_sequencial','int4','Sequencial de processo de aquisição de produção rural','0', 'Sequencial Processo ',10,'f','f','f',1,'text','Sequencial Processo');
        insert into db_syscampo values(1014404,'e157_retencaoreceitasprodutorrural','int4','Retenção de produção rural','0', 'Retenção de produção rural',10,'f','f','f',1,'text','Retenção de produção rurall');
        insert into db_syscampo values(1014405,'e157_nrprocjud','varchar(100)','Número do processo','', 'Número do processo',100,'f','t','f',0,'text','Número do processo');
        insert into db_syscampo values(1014406,'e157_vlrcpnret','float4','Valor CP não retido','0', 'Valor CP não retido',8,'t','f','f',4,'text','Valor CP não retido');
        insert into db_syscampo values(1014407,'e157_vlrratnret','float4','Valor Rat não retido','0', 'Valor Rat não retido',8,'t','f','f',4,'text','Valor Rat não retido');
        insert into db_syscampo values(1014408,'e157_vlrsenarnret','float4','Valor Senar não retido','0', 'Valor Senar não retido',8,'t','f','f',4,'text','Valor Senar não retido');
        insert into db_sysarqcamp values(1010974,1014403,1,0);
        insert into db_sysarqcamp values(1010974,1014404,2,0);
        insert into db_sysarqcamp values(1010974,1014405,3,0);
        insert into db_sysarqcamp values(1010974,1014406,4,0);
        insert into db_sysarqcamp values(1010974,1014407,5,0);
        insert into db_sysarqcamp values(1010974,1014408,6,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010974,1014403,1,1014403);
        insert into db_sysforkey values(1010974,1014404,1,1010972,0);
        insert into db_syssequencia values(1001084, 'aquisicaoproducaoruralprocessos_e157_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1001084 where codarq = 1010974 and codcam = 1014403;
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

    /**
     * Rollback estrutura
     *
     * @return void
     */
    public function downEstrutura()
    {
        $sql = <<<SQL

        -- emptipoaquisicaoproducaorural
        DROP TABLE IF EXISTS empenho.emptipoaquisicaoproducaorural;
        DROP SEQUENCE IF EXISTS empenho.emptipoaquisicaoproducaorural_e159_sequencial_seq;

        -- aquisicaoproducaoruralprocessos
        DROP TABLE IF EXISTS empenho.aquisicaoproducaoruralprocessos;
        DROP SEQUENCE IF EXISTS empenho.aquisicaoproducaoruralprocessos_e157_sequencial_seq;

        -- retencaoreceitasprodutorrural
        DROP TABLE IF EXISTS empenho.retencaoreceitasprodutorrural;
        DROP SEQUENCE IF EXISTS empenho.retencaoreceitasprodutorrural_e158_sequencial_seq;

        -- desabilita auditoria
        SELECT configuracoes.fc_auditoria_remove_funcao('empenho.emptipoaquisicaoproducaorural');
        SELECT configuracoes.fc_auditoria_remove_funcao('empenho.aquisicaoproducaoruralprocessos');
        SELECT configuracoes.fc_auditoria_remove_funcao('empenho.retencaoreceitasprodutorrural');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Rollback dicianario
     *
     * @return void
     */
    public function downDicionario()
    {
        $sql = <<<SQL

        -- emptipoaquisicaoprodutorrural
        delete from db_syssequencia where codsequencia = 1001082;
        delete from db_sysprikey where codarq = 1010973;
        delete from db_sysarqcamp where codarq = 1010973;
        delete from db_syscampo where codcam in (1014386, 1014388, 1014390, 1014396);
        delete from db_sysarqmod where codmod = 38 and codarq = 1010973;
        delete from db_sysarquivo where codarq = 1010973;

        -- retencaoreceitasprodutorrural
        delete from db_syssequencia where codsequencia = 1001083;
        delete from db_sysforkey where codarq = 1010972;
        delete from db_sysprikey where codarq = 1010972;
        delete from db_sysarqcamp where codarq = 1010972;
        delete from db_syscampo where codcam between 1014397 and 1014402;
        delete from db_sysarqmod where codmod = 38 and codarq = 1010972;
        delete from db_sysarquivo where codarq = 1010972;

        -- aquisicaoproducaoruralprocessos down
        delete from db_syssequencia where codsequencia = 1001084;
        delete from db_sysforkey where codarq = 1010974;
        delete from db_sysprikey where codarq = 1010974;
        delete from db_sysarqcamp where codarq = 1010974;
        delete from db_syscampo where codcam between 1014403 and 1014408;
        delete from db_sysarqmod where codmod = 38 and codarq = 1010974;
        delete from db_sysarquivo where codarq = 1010974;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
