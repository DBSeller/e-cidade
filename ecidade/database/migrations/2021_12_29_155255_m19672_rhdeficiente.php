<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19672Rhdeficiente extends Migration
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

    private function upDicionario() {
        $sql = <<<SQL
        insert into configuracoes.db_sysarquivo values (1010843, 'rhdeficiente', 'Tabela deficiente', 'rh55', '2021-12-29', 'Tabela dificiente', 0, 'f', 'f', 'f', 'f' );
        insert into configuracoes.db_sysarqmod values (29,1010843);
        insert into configuracoes.db_syscampo values(1013557,'rh55_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        insert into configuracoes.db_syscampo values(1013558,'rh55_matricula','int4','Matrícula do servidor','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
        update configuracoes.db_sysarquivo set nomearq = 'rhdeficiente', descricao = 'Tabela deficiente', sigla = 'rh253', dataincl = '2021-12-29', rotulo = 'Tabela dificiente', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010843;
        UPDATE configuracoes.db_sysarqmod SET codmod = 29 WHERE codarq = 1010843;
        delete from configuracoes.db_sysarqarq where codarq = 1010843;
        insert into configuracoes.db_sysarqarq values(0,1010843);
        update configuracoes.db_syscampo set nomecam = 'rh253_sequencial', conteudo = 'int4', descricao = 'Sequencial', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 1013557;
        delete from configuracoes.db_syscampodep where codcam = 1013557;
        delete from configuracoes.db_syscampodef where codcam = 1013557;
        update configuracoes.db_syscampo set nomecam = 'rh253_matricula', conteudo = 'int4', descricao = 'Matrícula do servidor', valorinicial = '0', rotulo = 'Matrícula', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Matrícula' where codcam = 1013558;
        delete from configuracoes.db_syscampodep where codcam = 1013558;
        delete from configuracoes.db_syscampodef where codcam = 1013558;
        insert into configuracoes.db_syscampo values(1013560,'rh253_fisica','bool','Deficiência física','f', 'Física',1,'t','f','f',5,'text','Física');
        insert into configuracoes.db_syscampo values(1013561,'rh253_instit','int4','Código da instituição.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
        insert into configuracoes.db_syscampo values(1013562,'rh253_visual','bool','Deficiência visual','f', 'Visual',1,'t','f','f',5,'text','Visual');
        insert into configuracoes.db_syscampo values(1013563,'rh253_auditiva','bool','Deficiência auditiva','f', 'auditiva',1,'t','f','f',5,'text','auditiva');
        insert into configuracoes.db_syscampo values(1013564,'rh253_mental','bool','Deficiência mental.','f', 'Mental',1,'t','f','f',5,'text','Mental');
        insert into configuracoes.db_syscampo values(1013565,'rh253_intelectual','bool','Deficiência intelectual','f', 'Intelectual',1,'t','f','f',5,'text','Intelectual');
        insert into configuracoes.db_syscampo values(1013566,'rh253_reabilitado','bool','Reabilitado','f', 'Reabilitado',1,'t','f','f',5,'text','Reabilitado');
        insert into configuracoes.db_syscampo values(1013567,'rh253_cota','bool','Cota','f', 'Cota',1,'t','f','f',5,'text','Cota');
        insert into configuracoes.db_syscampo values(1013569,'rh253_observacao','varchar(255)','Observação','', 'Observação',255,'t','t','f',0,'text','Observação');
        delete from configuracoes.db_sysarqcamp where codarq = 1010843;
        insert into configuracoes.db_sysarqcamp values(1010843,1013557,1,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013558,2,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013560,3,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013561,4,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013562,5,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013563,6,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013564,7,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013565,8,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013566,9,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013567,10,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013569,11,0);
        delete from configuracoes.db_sysprikey where codarq = 1010843;
        insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010843,1013557,1,1013557);
        delete from configuracoes.db_sysforkey where codarq = 1010843 and referen = 0;
        insert into configuracoes.db_sysforkey values(1010843,1013558,1,1153,0);
        insert into configuracoes.db_syssequencia values(1001024, 'rhdeficiente_rh253_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update configuracoes.db_sysarqcamp set codsequencia = 1001024 where codarq = 1010843 and codcam = 1013557;
        delete from configuracoes.db_sysarqcamp where codarq = 1010843;
        insert into configuracoes.db_sysarqcamp values(1010843,1013557,1,1001024);
        insert into configuracoes.db_sysarqcamp values(1010843,1013558,2,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013560,3,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013561,4,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013562,5,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013563,6,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013564,7,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013565,8,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013566,9,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013567,10,0);
        insert into configuracoes.db_sysarqcamp values(1010843,1013569,11,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura() {
        $sql = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhdeficiente;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS rhdeficiente_rh253_sequencial_seq;
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhdeficiente_rh253_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Modulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhdeficiente(
        rh253_sequencial	int4 NOT NULL default nextval('rhdeficiente_rh253_sequencial_seq'),
        rh253_matricula		int4 NOT NULL default 0,
        rh253_fisica		bool  default 'f',
        rh253_instit		int4 NOT NULL default 0,
        rh253_visual		bool  default 'f',
        rh253_auditiva		bool  default 'f',
        rh253_mental		bool  default 'f',
        rh253_intelectual	bool  default 'f',
        rh253_reabilitado	bool  default 'f',
        rh253_cota		    bool  default 'f',
        rh253_observacao	varchar(255)  default '',
        CONSTRAINT rhdeficiente_sequ_pk PRIMARY KEY (rh253_sequencial));
        -- CHAVE ESTRANGEIRA
        ALTER TABLE recursoshumanos.rhdeficiente
        ADD CONSTRAINT rhdeficiente_matricula_fk FOREIGN KEY (rh253_matricula)
        REFERENCES pessoal.rhpessoal;
        -- INDICES
        CREATE UNIQUE INDEX rh253_sequencial_in ON recursoshumanos.rhdeficiente(rh253_sequencial);
SQL;
        DB::connection()->getPdo()->exec($sql);                
    }

    private function downDicionario() {
        $sql = <<<SQL
        delete from configuracoes.db_sysprikey where codarq = 1010843;
        delete from configuracoes.db_sysforkey where codarq = 1010843;
        delete from configuracoes.db_syssequencia where codsequencia = 1001024;
        delete from configuracoes.db_sysarqcamp where codarq = 1010843;
        delete from configuracoes.db_syscampo where codcam between 1013560 and 1013569;
        delete from configuracoes.db_syscampo where codcam in (1013557,1013558);
        delete from configuracoes.db_sysarqarq where codarq = 1010843;
        delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1010843;
        delete from configuracoes.db_sysarquivo where codarq = 1010843;
SQL;
        DB::connection()->getPdo()->exec($sql);    
    }            
    private function downEstrutura() {
        $sql = <<<SQL
        DROP TABLE IF EXISTS recursoshumanos.rhdeficiente;
        DROP SEQUENCE IF EXISTS recursoshumanos.rhdeficiente_rh253_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);  
    }

}
