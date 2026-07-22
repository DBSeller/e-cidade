<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20480EstagioVinculo extends Migration
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

    public function upEstrutura()
    {
        $sql=<<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhestagiovinculo_rh260_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        -- Módulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhestagiovinculo(
            rh260_sequencial		int4 NOT NULL default nextval('recursoshumanos.rhestagiovinculo_rh260_sequencial_seq'),
            rh260_matricula		int4 NOT NULL default 0,
            rh260_naturezaestagio		    char(1),
            rh260_nivelestagio		        char(1),
            rh260_dataterminoestagio		date NULL,
            rh260_cnpjinstensino		    char(14),
            rh260_cnpjagentintegracao		char(14),
        CONSTRAINT rhestagiovinculo_sequ_pk PRIMARY KEY (rh260_sequencial));

        -- CHAVE ESTRANGEIRA
        ALTER TABLE recursoshumanos.rhestagiovinculo
        ADD CONSTRAINT rhestagiovinculo_matricula_fk FOREIGN KEY (rh260_matricula)
        REFERENCES pessoal.rhpessoal;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

public function downEstrutura()
{
    $sql= <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhestagiovinculo;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhestagiovinculo_rh260_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
}
    public function upDicionario()
    {
        $sql= <<<SQL
        insert into configuracoes.db_sysarquivo values (1010893, 'rhestagiovinculo', 'Tabela para o vínculo do estágio do servidor', 'rh260', '2022-04-05', 'Vínculo estágio do servidor', 0, 'f', 'f', 'f', 'f' );
        insert into configuracoes.db_sysarqmod values (29,1010893);

        insert into configuracoes.db_syscampo values(1013956,'rh260_sequencial','int4','Codigo de Refência da Tabela, Chave Primaria','0', 'Codigo de Refência da Tabela',11,'f','f','f',1,'text','Codigo de Refência da Tabela');
        insert into configuracoes.db_syscampo values(1013957,'rh260_matricula','int4','Matrícula do Servidor Referente ao Estágio','0', 'Matrícula',11,'f','f','f',1,'text','Matrícula');
        insert into configuracoes.db_syscampo values(1013958,'rh260_naturezaestagio','varchar(255)','Natureza do Estágio do Servidor','', 'Natureza do Estágio',255,'t','t','f',0,'text','Natureza do Estágio');
        insert into configuracoes.db_syscampo values(1013959,'rh260_nivelestagio','varchar(255)','Nível do Estagio do Servidor','', 'Nivel',255,'t','t','f',0,'text','Nivel');
        insert into configuracoes.db_syscampo values(1013960,'rh260_previsaoestagio','date','Previsão do Termino Estágio do Servidor','null', 'Previsão do Termino',10,'f','f','f',1,'text','Previsão do Termino');
        insert into configuracoes.db_syscampo values(1013961,'rh260_instensino','int4','Instituição de Ensino do Servidor','0', 'Instituição de Ensino',14,'t','f','f',1,'text','Instituição de Ensino');
        insert into configuracoes.db_syscampo values(1013962,'rh260_agentintegracao','int4','Agente de Integração do Servidor','0', 'Agente de Integração',14,'t','f','f',1,'text','Agente de Integração');

        update configuracoes.db_syscampo set nomecam = 'rh260_naturezaestagio', conteudo = 'char(1)', descricao = 'Natureza do Estágio do Servidor', valorinicial = '', rotulo = 'Natureza do Estágio', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Natureza do Estágio' where codcam = 1013958;
        update configuracoes.db_syscampo set nomecam = 'rh260_nivelestagio', conteudo = 'char(1)', descricao = 'Nível do Estagio do Servidor', valorinicial = '', rotulo = 'Nivel', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Nivel' where codcam = 1013959;
        update configuracoes.db_syscampo set nomecam = 'rh260_cnpjinstensino', conteudo = 'char(14)', descricao = 'CNPJ da Instituição de Ensino do Servidor', valorinicial = '0', rotulo = 'CNPJ Insti. Ensino', nulo = 't', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CNPJ Insti. Ensino' where codcam = 1013961;
        update configuracoes.db_syscampo set nomecam = 'rh260_cnpjagentintegracao', conteudo = 'char(14)', descricao = 'CNPJ do Agente de Integração do Servidor', valorinicial = '0', rotulo = 'CNPJ Agente Inte.', nulo = 't', tamanho = 14, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'CNPJ Agente Inte.' where codcam = 1013962;
        update configuracoes.db_syscampo set nomecam = 'rh260_dataterminoestagio', conteudo = 'date', descricao = 'Previsão do Termino Estágio do Servidor', valorinicial = 'null', rotulo = 'Previsão do Termino', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Previsão do Termino' where codcam = 1013960;

        insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010893,1013956,1,1013956);
        insert into configuracoes.db_sysforkey values(1010893,1013957,1,1153,0);
        insert into configuracoes.db_syssequencia values(1001043, 'rhestagiovinculo_rh260_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update configuracoes.db_sysarqcamp set codsequencia = 1001043 where codarq = 1010893 and codcam = 1013956;
        delete from configuracoes.db_sysarqcamp where codarq = 1010893;
        insert into configuracoes.db_sysarqcamp values(1010893,1013956,1,1001043);
        insert into configuracoes.db_sysarqcamp values(1010893,1013957,2,0);
        insert into configuracoes.db_sysarqcamp values(1010893,1013958,3,0);
        insert into configuracoes.db_sysarqcamp values(1010893,1013959,4,0);
        insert into configuracoes.db_sysarqcamp values(1010893,1013960,5,0);
        insert into configuracoes.db_sysarqcamp values(1010893,1013961,6,0);
        insert into configuracoes.db_sysarqcamp values(1010893,1013962,7,0);
        update configuracoes.db_syscampo set nomecam = 'rh260_dataterminoestagio', conteudo = 'date', descricao = 'Previsão do Termino Estágio do Servidor', valorinicial = 'null', rotulo = 'Previsão do Termino', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 3, tipoobj = 'text', rotulorel = 'Previsão do Termino' where codcam = 1013960;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downDicionario()
    {
        $sql= <<<SQL
            delete from configuracoes.db_syssequencia where codsequencia = 1001043;
            delete from configuracoes.db_sysforkey where codarq = 1010893 and codcam = 1013957;
            delete from configuracoes.db_sysprikey where codarq = 1010893 and codcam = 1013956;
            delete from configuracoes.db_sysarqcamp where codarq = 1010893 and codcam in (1013956, 1013957, 1013958, 1013959, 1013960, 1013961, 1013962);
            delete from configuracoes.db_syscampo where codcam in (1013956, 1013957, 1013958, 1013959, 1013960, 1013961, 1013962);
            delete from configuracoes.db_sysarqmod where codarq = 1010893 and codmod = 29;
            delete from configuracoes.db_sysarquivo where codarq = 1010893;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
