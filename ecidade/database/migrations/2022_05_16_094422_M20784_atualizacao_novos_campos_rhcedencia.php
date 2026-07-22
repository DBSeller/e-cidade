<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20784AtualizacaoNovosCamposRhcedencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upDeletaDadosRepetidos();
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
        $this->downDeletaDadosRepetidos();
        $this->downEstrutura();
    }

    private function upDicionario() {
        $sql=<<<SQL
            insert into configuracoes.db_syscampo values(1014119,'rh261_indicadoconselho','bool','Servidor Indicado para Conselho (Cód Categoria 305).','f', 'Servidor Indicado para Conselho',1,'t','f','f',5,'text','Servidor Indicado para Conselho');
            insert into configuracoes.db_syscampo values(1014120,'rh261_sequencial','int4','Codigo de Refência da Tabela, Chave Primaria','0', 'Codigo de Refência da Tabela',11,'f','f','f',1,'text','Codigo de Refência da Tabela');
            
            -- novos campos da tabela
            insert into configuracoes.db_syscampo values(1014125,'rh261_codcategoriaorigem','int4','Código de Categoria no Orgão de Origem.','0', 'Código de Categoria',10,'t','f','f',1,'text','Código de Categoria');
            insert into configuracoes.db_syscampo values(1014126,'rh261_dtorigemadmissao','date','Data de admissão origem','null', 'Data de admissão origem',10,'t','f','f',1,'text','Data de admissão origem');
            insert into configuracoes.db_syscampo values(1014127,'rh261_tiporegimeorigem','int4','Código referente ao tipo de Regime Trab. Origem','0', 'Tipo de Regime Trab. Origem',1,'t','f','f',1,'text','Tipo de Regime Trab. Origem');
            insert into configuracoes.db_syscampo values(1014128,'rh261_tiporegimeprev','int4','Tipo de Regime Previdenciário','0', 'Tipo de Regime Previdenciário',1,'t','f','f',1,'text','Tipo de Regime Previdenciário');

            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010896,1014120,1,1014120);
            insert into configuracoes.db_syssequencia values(1001063, 'rhcedencia_rh261_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            delete from configuracoes.db_sysarqcamp where codarq = 1010896;
            insert into configuracoes.db_sysarqcamp values(1010896,1014120,1,1001063);
            insert into configuracoes.db_sysarqcamp values(1010896,1013999,2,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013968,3,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013969,4,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013970,5,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013971,6,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013972,7,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013973,8,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013974,9,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013975,10,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1014119,11,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1014128,12,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1014127,13,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1014126,14,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1014125,15,0);
            insert into configuracoes.db_sysforkey values(1010896,1013999,1,1153,0);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
            delete from configuracoes.db_syssequencia where codsequencia = 1001063;
            delete from configuracoes.db_sysprikey where codarq = 1010896 and codcam = 1014120;
            delete from configuracoes.db_sysforkey where codarq = 1010896 and codcam = 1013999;
            delete from configuracoes.db_sysarqcamp where codarq = 1010896 and codcam in (1014120, 1014119, 1014125, 1014126, 1014127, 1014128);
            delete from configuracoes.db_syscampo where codcam in (1014119, 1014120, 1014125, 1014126, 1014127, 1014128);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * O campo seqpes, foi depreciado e nao eh mais obrigatorio seu uso para criacao da cedencia,
     * ele foi mantido afim de permanecer o historico de dados anteriores.
     * porem ele nao deve mais ser utilizado!!!
     */

    private function upEstrutura() {
        $sql = <<<SQL
            CREATE SEQUENCE pessoal.rhcedencia_rh261_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            ALTER TABLE pessoal.rhcedencia ADD column rh261_indicadoconselho BOOLEAN DEFAULT false;
            ALTER TABLE pessoal.rhcedencia ADD column rh261_sequencial int4 NOT NULL DEFAULT nextval('rhcedencia_rh261_sequencial_seq');
            ALTER TABLE pessoal.rhcedencia DROP CONSTRAINT IF EXISTS rhcedencia_pkey;
            ALTER TABLE pessoal.rhcedencia ADD PRIMARY KEY (rh261_sequencial);
            ALTER TABLE pessoal.rhcedencia ADD CONSTRAINT rhcedencia_rh261_regist_fkey FOREIGN KEY (rh261_regist) REFERENCES pessoal.rhpessoal (rh01_regist);
            -- Campo depreciado na tabela: rh261_seqp (fazia referencia a rhpessoalmov).
            ALTER TABLE pessoal.rhcedencia ALTER column rh261_seqpes drop not null;

            ALTER TABLE pessoal.rhcedencia ADD column rh261_codcategoriaorigem int4 NULL;
            ALTER TABLE pessoal.rhcedencia ADD column rh261_dtorigemadmissao date NULL;
            ALTER TABLE pessoal.rhcedencia ADD column rh261_tiporegimeorigem int4 NULL;
            ALTER TABLE pessoal.rhcedencia ADD column rh261_tiporegimeprev int4 NULL;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

        private function downEstrutura() {
        $sql = <<<SQL
            -- Comentado, pois a adicao da constraint vai falhar pq os novos registros nao possuem seqpes.
            -- ALTER TABLE pessoal.rhcedencia ALTER column rh261_seqpes SET NOT NULL;
            ALTER TABLE pessoal.rhcedencia DROP CONSTRAINT  rhcedencia_rh261_regist_fkey;
            ALTER TABLE pessoal.rhcedencia DROP column rh261_sequencial;
            ALTER TABLE pessoal.rhcedencia DROP column rh261_indicadoconselho;
            DROP SEQUENCE pessoal.rhcedencia_rh261_sequencial_seq;
            -- Comentado pois a partir do momento que novos registros foram inseridos, os novos registros
            -- possuiram seqpes nulls, por tanto nao sera possivel setar primarykey.
            -- ALTER TABLE pessoal.rhcedencia ADD PRIMARY KEY (rh261_seqpes);
            ALTER TABLE pessoal.rhcedencia DROP column rh261_codcategoriaorigem;
            ALTER TABLE pessoal.rhcedencia DROP column rh261_dtorigemadmissao;
            ALTER TABLE pessoal.rhcedencia DROP column rh261_tiporegimeorigem;
            ALTER TABLE pessoal.rhcedencia DROP column rh261_tiporegimeprev;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDeletaDadosRepetidos() {
        $sql = <<<SQL
        create temp table w_rhcedencia as
        select distinct on (r.rh261_regist,rh261_devolucao,rh261_datamovimentacao) * from pessoal.rhcedencia r;
        delete from pessoal.rhcedencia;
        insert into pessoal.rhcedencia select * from w_rhcedencia;
        drop table if exists w_rhcedencia;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDeletaDadosRepetidos() {
        $sql = <<<SQL
        insert into pessoal.rhcedencia
        select
            rpv.rh02_seqpes as rh261_seqpes,
            rpv.rh02_cedencia as rh261_credencial,
            rpv.rh02_onus as rh261_onus,
            rpv.rh02_ressarcimento as rh261_ressarcimento,
            rpv.rh02_datacedencia as rh261_datamovimentacao,
            null::date as rh261_devolucao,
            max(cg.z01_numcgm) as rh261_numcgm,
            null as rh261_matorigemcedente,
            'N' as rh261_servidorcedido,
            rpv.rh02_regist as rh261_regist
        from rhpessoalmov rpv
            left join cgm cg on cg.z01_cgccpf = rpv.rh02_cnpjcedencia
        where rpv.rh02_cedencia is not null and length(trim(rpv.rh02_cnpjcedencia)) > 11
        group by
            1,
            2,
            3,
            4,
            5,
            6,
            8,
            9,
            10
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
