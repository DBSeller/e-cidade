<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21707FumamAlteraEstruturaProfissional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upTabelas();
    }

    public function upTabelas()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;

            -- tabela de profissionais FUMAM

            CREATE TABLE IF NOT EXISTS fumam.profissionais (
                fm15_codigo serial CONSTRAINT profissionaisprestadores_codigo_pk PRIMARY KEY,
                fm15_nome varchar(255) NOT NULL CHECK (trim(fm15_nome) <> ''),
                fm15_cpf varchar(11) CONSTRAINT profissionais_cpf_uk UNIQUE,
                fm15_cbo integer,
                fm15_regprof varchar(10) NOT NULL CHECK (trim(fm15_regprof) <> ''),
                fm15_orgaoemissor integer NOT NULL,
                CONSTRAINT profissionais_orgaoemissor_fk FOREIGN KEY (fm15_orgaoemissor)
                    REFERENCES sau_orgaoemissor(sd51_i_codigo),
                CONSTRAINT profissionais_cbo_fk FOREIGN KEY (fm15_cbo)
                    REFERENCES rhcbo(rh70_sequencial),
                CONSTRAINT profissionais_regprof_orgaoemissor_uk UNIQUE (fm15_regprof, fm15_orgaoemissor)
            );

            COMMENT ON TABLE fumam.profissionais IS
                '{"descricao": "Tabela de cadastro de profissionais",
                  "sigla": "fm15",
                  "dataincl": "2022-10-01",
                  "rotulo": "profissionais",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';

            COMMENT ON COLUMN fumam.profissionais.fm15_codigo IS
                '{ "descricao": "Código do Profissional",
                   "rotulo": "Código do Profissional",
                   "rotulorel": "Código do Profissional",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionais.fm15_nome IS
                '{ "descricao": "Nome do profissional",
                   "rotulo": "Nome do profissional",
                   "rotulorel": "Nome do profissional",
                   "maiusculo": true,
                   "autocompl": false,
                   "aceitatipo": 3,
                   "tamanho": 255,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionais.fm15_cpf IS
                '{ "descricao": "CPF do Profissional",
                   "rotulo": "CPF do Profissional",
                   "rotulorel": "CPF do Profissional",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 0,
                   "tamanho": 11,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionais.fm15_cbo IS
                '{ "descricao": "CBO do Profissional",
                   "rotulo": "CBO do Profissional",
                   "rotulorel": "CBO do Profissional",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;                 

            COMMENT ON COLUMN fumam.profissionais.fm15_regprof IS
                '{ "descricao": "Registro Profissional",
                   "rotulo": "Registro Profissional",
                   "rotulorel": "Registro Profissional",
                   "maiusculo": true,
                   "autocompl": false,
                   "aceitatipo": 3,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionais.fm15_orgaoemissor IS
                '{ "descricao": "Órgão emissor do documento",
                   "rotulo": "Órgão emissor do documento",
                   "rotulorel": "Órgão emissor do documento",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            ALTER TABLE fumam.profissionaisprestadores DROP column IF EXISTS fm07_numcgm;
            ALTER TABLE fumam.profissionaisprestadores DROP column IF EXISTS fm07_orgaoemissor;
            ALTER TABLE fumam.profissionaisprestadores DROP column IF EXISTS fm07_regprof;
            ALTER TABLE fumam.profissionaisprestadores DROP column IF EXISTS fm07_cbo;
            ALTER TABLE fumam.profissionaisprestadores ADD column fm07_profissional integer;
            ALTER TABLE fumam.profissionaisprestadores ADD CONSTRAINT profissionaisprestadores_profissional_fk
                FOREIGN KEY (fm07_profissional) REFERENCES fumam.profissionais(fm15_codigo);
            CREATE UNIQUE INDEX  IF NOT EXISTS profissionaisprestadores_prestador_profissional_uk
                ON profissionaisprestadores(fm07_prestador, fm07_profissional);
            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.profissionaisprestadores.fm07_profissional',
                   '{ "descricao": "Profissional do Serviço",
                      "rotulo": "Profissional",
                      "rotulorel": "Profissional",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'profissionais');
            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'profissionaisprestadores');

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

            UPDATE db_sysprikey SET camiden = (SELECT codcam FROM db_syscampo WHERE nomecam = 'fm15_nome')
              WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'profissionais');

SQL
        );

    }

    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                             values ( 228768 ,'Profissionais de Saúde' ,'Profissionais de Saúde' ,'' ,'1' ,'1' ,
                                      'Manutenção dos Profissionais de Saúde' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 29 ,228768 ,311 ,228703 );

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                             values ( 228769 ,'Inclusão' ,'Inclusão de Profissional' ,'fum1_profissionais001.php' ,
                                      '1' , '1' ,'Inclusão de Profissional' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228768 ,228769 ,1 ,228703 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                             values ( 228770 ,'Alteração' ,'Alteração de Profissional' ,'fum1_profissionais002.php' ,
                                      '1' , '1' ,'Alteração de Profissional' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228768 ,228770 ,2 ,228703 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                             values ( 228771 ,'Exclusão' ,'Exclusão de Profissional' ,'fum1_profissionais003.php' ,
                                      '1' ,'1' ,'Exclusão de Profissional' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228768 ,228771 ,3 ,228703 );

SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downTabelas();
    }

    public function downTabelas()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;

            ALTER TABLE fumam.profissionaisprestadores ADD column fm07_numcgm integer;
            ALTER TABLE fumam.profissionaisprestadores ADD column fm07_orgaoemissor integer;
            ALTER TABLE fumam.profissionaisprestadores ADD column fm07_regprof varchar;
            ALTER TABLE fumam.profissionaisprestadores ADD column fm07_cbo integer;
            ALTER TABLE fumam.profissionaisprestadores DROP column IF EXISTS fm07_profissional;
            ALTER TABLE fumam.profissionaisprestadores ADD CONSTRAINT profissionaisprestadores_numcgm_fk
                FOREIGN KEY (fm07_numcgm) REFERENCES protocolo.cgm(z01_numcgm);
            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.associadoservicos.fm07_numcgm',
                   '{ "descricao": "CGM do profissional",
                      "rotulo": "CGM do profissional",
                      "rotulorel": "CGM do profissional",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.associadoservicos.fm07_orgaoemissor',
                   '{ "descricao": "Órgão emissor do documento",
                      "rotulo": "Órgão emissor do documento",
                      "rotulorel": "Órgão emissor do documento",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.associadoservicos.fm07_regprof',
                   '{ "descricao": "Registro Profissional",
                      "rotulo": "Registro Profissional",
                      "rotulorel": "Registro Profissional",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 3,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            SELECT fc_atualiza_dicionario_apartir_comentario('table column',
                   'fumam.associadoservicos.fm07_cbo',
                   '{ "descricao": "CBO do Profissional",
                      "rotulo": "CBO do Profissional",
                      "rotulorel": "CBO do Profissional",
                      "maiusculo": false,
                      "autocompl": false,
                      "aceitatipo": 1,
                      "tamanho": 10,
                      "tipoobj": "text"
                    }') ;

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

            DROP TABLE IF EXISTS fumam.profissionais;
            DROP INDEX IF EXISTS profissionaisprestadores_prestador_profissional_uk;

SQL
        );
    }

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            SELECT fc_remove_dicionario_tabela('fumam', 'profissionais');

            DELETE FROM db_menu WHERE id_item_filho between 228768 and 228771;
            DELETE FROM db_itensmenu WHERE id_item between 228768 and 228771;

SQL
        );
    }
}
