<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M20930SistemaControleFumam extends Migration
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

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            SELECT fc_remove_dicionario_tabela('fumam', 'associadosituacao');
            SELECT fc_remove_dicionario_tabela('fumam', 'associadotiposservicos');
            SELECT fc_remove_dicionario_tabela('fumam', 'associadoservicos');
            SELECT fc_remove_dicionario_tabela('fumam', 'associadovalorservico');

            DELETE FROM db_itensmenu WHERE id_item = 228703;
            DELETE FROM db_itensmenu WHERE id_item BETWEEN 228716 AND 228723;
            DELETE FROM db_itensmenu WHERE id_item BETWEEN 228749 AND 228752;
            DELETE FROM db_menu WHERE modulo = 228703;

            DELETE FROM atendcadareamod WHERE at26_sequencia = 84;
            DELETE FROM db_sysmodulo WHERE codmod = 89;
            DELETE FROM db_modulos WHERE id_item = 228703;

SQL
        );
    }
    
    public function downTabelas()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            drop table if exists fumam.associadovalorservico;
            drop table if exists fumam.associadoservicos;
            drop table if exists fumam.associadotiposservicos;
            drop table if exists fumam.associadosituacao;

SQL
        );
    }
    
    public function upTabelas()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;

            CREATE SCHEMA IF NOT EXISTS fumam;

            -- associadosituacao

            CREATE TABLE IF NOT EXISTS fumam.associadosituacao (
                fm02_situacao serial PRIMARY KEY,
                fm02_descricao varchar NOT NULL CHECK (fm02_descricao <> '')
            );

            COMMENT ON TABLE fumam.associadosituacao IS
                '{"descricao": "Tabela de cadastro das Situações do Associado",
                  "sigla": "fm02",
                  "dataincl": "2022-06-30",
                  "rotulo": "associadosituacao",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';

            COMMENT ON COLUMN fumam.associadosituacao.fm02_situacao IS
                '{ "descricao": "Código Situação do Associado",
                   "rotulo": "Código Situação do Associado",
                   "rotulorel": "Código Situação do Associado",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadosituacao.fm02_descricao IS
                '{ "descricao": "Descrição Situação",
                   "rotulo": "Descrição Situação",
                   "rotulorel": "Situação",
                   "maiusculo": true,
                   "autocompl": false,
                   "aceitatipo": 3,
                   "tamanho": 200,
                   "tipoobj": "text"
                 }' ;

            INSERT INTO fumam.associadosituacao (fm02_descricao) VALUES ('ATIVO');
            INSERT INTO fumam.associadosituacao (fm02_descricao) VALUES ('INATIVO');

            -- Tipos de Serviço

            CREATE TABLE IF NOT EXISTS fumam.associadotiposservicos (
                fm09_codigo serial PRIMARY KEY,
                fm09_descricao varchar NOT NULL CHECK (fm09_descricao <> ''),
                fm09_copart_percentual boolean NOT NULL CHECK (fm09_copart_percentual <> fm09_copart_financeiro),
                fm09_copart_financeiro boolean NOT NULL CHECK (fm09_copart_financeiro <> fm09_copart_percentual),
                fm09_valor numeric(15,2) NOT NULL
            );

            COMMENT ON TABLE fumam.associadotiposservicos IS
                '{"descricao": "Tabela de Tipos de Serviços",
                  "sigla": "fm09",
                  "dataincl": "2022-06-30",
                  "rotulo": "associadotiposservicos",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';

            COMMENT ON COLUMN fumam.associadotiposservicos.fm09_codigo IS
                '{ "descricao": "Código do Tipo de Serviço",
                   "rotulo": "Código do Tipo de Serviço",
                   "rotulorel": "Código do Tipo de Serviço",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadotiposservicos.fm09_descricao IS
                '{ "descricao": "Descrição do Tipo de Serviço",
                   "rotulo": "Descrição do Tipo de Serviço",
                   "rotulorel": "Descrição do Tipo de Serviço",
                   "maiusculo": true,
                   "autocompl": false,
                   "aceitatipo": 3,
                   "tamanho": 100,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadotiposservicos.fm09_copart_percentual IS
                '{ "descricao": "Percentual",
                   "rotulo": "Percentual",
                   "rotulorel": "Percentual",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 5,
                   "tamanho": 1,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadotiposservicos.fm09_copart_financeiro IS
                '{ "descricao": "Financeiro",
                   "rotulo": "Financeiro",
                   "rotulorel": "Financeiro",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 5,
                   "tamanho": 1,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadotiposservicos.fm09_valor IS
                '{ "descricao": "Valor do Serviço",
                   "rotulo": "Valor do Serviço",
                   "rotulorel": "Valor do Serviço",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 0,
                   "tamanho": 15,
                   "tipoobj": "text"
                 }' ;

            -- Cadastro de Serviço

            CREATE TABLE IF NOT EXISTS fumam.associadoservicos (
                fm12_codigo serial PRIMARY KEY,
                fm12_tpservico integer NOT NULL,
                fm12_descricao varchar NOT NULL CHECK (fm12_descricao <> ''),
                fm12_situacao integer NOT NULL,
                fm12_autorizacao boolean NOT NULL DEFAULT FALSE,
                fm12_odontograma boolean NOT NULL DEFAULT FALSE,
                fm12_idademin integer NOT NULL DEFAULT 0,
                fm12_idademax integer NOT NULL DEFAULT 0,
                fm12_validadeini date,
                fm12_validadefim date,
                fm12_masculino boolean NOT NULL,
                fm12_feminino boolean NOT NULL,
                CONSTRAINT associadoservicos_tpservico_fk FOREIGN KEY (fm12_tpservico)
                    REFERENCES fumam.associadotiposservicos(fm09_codigo),
                CONSTRAINT associadoservicos_situacao_fk FOREIGN KEY (fm12_situacao)
                    REFERENCES fumam.associadosituacao(fm02_situacao)
            );

            COMMENT ON TABLE fumam.associadoservicos IS
                '{"descricao": "Tabela de Serviços Prestados pelo FUMAM",
                  "sigla": "fm12",
                  "dataincl": "2022-06-30",
                  "rotulo": "associadoservicos",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';

            COMMENT ON COLUMN fumam.associadoservicos.fm12_codigo IS
                '{ "descricao": "Código do Serviço Prestado",
                   "rotulo": "Código do Serviço Prestado",
                   "rotulorel": "Código do Serviço Prestado",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_tpservico IS
                '{ "descricao": "Código do Tipo de Serviço",
                   "rotulo": "Código do Tipo de Serviço",
                   "rotulorel": "Código do Tipo de Serviço",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_descricao IS
                '{ "descricao": "Descrição do Serviço",
                   "rotulo": "Descrição do Serviço",
                   "rotulorel": "Descrição do Serviço",
                   "maiusculo": true,
                   "autocompl": false,
                   "aceitatipo": 3,
                   "tamanho": 100,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_situacao IS
                '{ "descricao": "Situação do Serviço",
                   "rotulo": "Situação do Serviço",
                   "rotulorel": "Situação do Serviço",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_autorizacao IS
                '{ "descricao": "Necessita Autorização do Serviço",
                   "rotulo": "Necessita Autorização do Serviço",
                   "rotulorel": "Necessita Autorização do Serviço",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 5,
                   "tamanho": 1,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_odontograma IS
                '{ "descricao": "Preencher Odontograma",
                   "rotulo": "Preencher Odontograma",
                   "rotulorel": "Preencher Odontograma",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 5,
                   "tamanho": 1,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_idademin IS
                '{ "descricao": "Idade Mínima em Dias",
                   "rotulo": "Idade Mínima",
                   "rotulorel": "Idade Mínima",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_idademax IS
                '{ "descricao": "Idade Máxima em Dias",
                   "rotulo": "Idade Máxima",
                   "rotulorel": "Idade Máxima",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_validadeini IS
                '{ "descricao": "Validade Inicial",
                   "rotulo": "Validade Inicial",
                   "rotulorel": "Validade Inicial",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_validadefim IS
                '{ "descricao": "Validade Final",
                   "rotulo": "Validade Final",
                   "rotulorel": "Validade Final",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_masculino IS
                '{ "descricao": "Masculino",
                   "rotulo": "Masculino",
                   "rotulorel": "Masculino",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 5,
                   "tamanho": 1,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadoservicos.fm12_feminino IS
                '{ "descricao": "Feminino",
                   "rotulo": "Feminino",
                   "rotulorel": "Feminino",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 5,
                   "tamanho": 1,
                   "tipoobj": "text"
                 }' ;

            -- Cadastro do Valor do Serviço

            CREATE TABLE IF NOT EXISTS fumam.associadovalorservico (
              fm13_codigo serial PRIMARY KEY,
              fm13_servico integer NOT NULL,
              fm13_valor numeric(15,2) NOT NULL,
              fm13_vigencia date,
              CONSTRAINT associadovalorservico_servico_fk FOREIGN KEY (fm13_servico)
                  REFERENCES fumam.associadoservicos(fm12_codigo)
            );

            COMMENT ON TABLE fumam.associadovalorservico IS
                '{"descricao": "Tabela de Valores dos Serviços Prestados pelo FUMAM",
                  "sigla": "fm13",
                  "dataincl": "2022-06-30",
                  "rotulo": "associadovalorservico",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';

            COMMENT ON COLUMN fumam.associadovalorservico.fm13_codigo IS
                '{ "descricao": "Código Valor do Serviço Prestado",
                   "rotulo": "Cód Vlr Serv Prestado",
                   "rotulorel": "Cód Vlr Serv Prestado",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadovalorservico.fm13_servico IS
                '{ "descricao": "Código do Cadastro Serviço Prestado",
                   "rotulo": "Cód Cad Serv Prestado",
                   "rotulorel": "Cód Cad Serv Prestado",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadovalorservico.fm13_valor IS
                '{ "descricao": "Valor Fundo Assistência Médica",
                   "rotulo": "Valor Fundo Assistência Médica",
                   "rotulorel": "Valor Fundo Assistência Médica",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 0,
                   "tamanho": 15,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.associadovalorservico.fm13_vigencia IS
                '{ "descricao": "Data da Vigência",
                   "rotulo": "Data da Vigência",
                   "rotulorel": "Data da Vigência",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            SELECT fc_set_pg_search_path();
            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'associadosituacao');
            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'associadotiposservicos');
            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'associadoservicos');
            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'associadovalorservico');

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

            UPDATE db_sysprikey SET camiden = (SELECT codcam FROM db_syscampo WHERE nomecam = 'fm13_servico')
              WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'associadovalorservico');

            UPDATE db_sysprikey SET camiden = (SELECT codcam FROM db_syscampo WHERE nomecam = 'fm12_descricao')
              WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'associadoservicos');

            UPDATE db_sysprikey SET camiden = (SELECT codcam FROM db_syscampo WHERE nomecam = 'fm02_descricao')
              WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'associadosituacao');

            UPDATE db_sysprikey SET camiden = (SELECT codcam FROM db_syscampo WHERE nomecam = 'fm09_descricao')
              WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'associadotiposservicos');

SQL
        );
    
    }

    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                              VALUES (228703, 'Fundo Assistência Médica', 'Fundo Assistência Médica', '', '2', '1', 'Módulo de cadastro e manutenção do Fundo Assistência Médica', true);
            INSERT INTO db_modulos (id_item, nome_modulo, descr_modulo, imagem, temexerc, nome_manual)
                            VALUES (228703, 'fumam', 'Fundo Assistência Médica', '', true, '');
            INSERT INTO db_sysmodulo (codmod, nomemod, descricao, dataincl, ativo)
                              VALUES (89,'fumam','Cadastro e Manutenção dos Associados','2022-06-30',true);
            INSERT INTO atendcadareamod (at26_sequencia, at26_codarea, at26_id_item) VALUES (84, 9, 228703);

            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228703 ,29 ,1 ,228703 );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228703 ,30 ,2 ,228703 );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228703 ,31 ,3 ,228703 );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228703 ,32 ,4 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228716 ,'Tipos de Serviços' ,'Tipos de Serviços' ,'' ,'1' ,'1' ,
                                       'Cadastro dos Tipos de Serviços do Fumam' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 29 ,228716 ,307 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228717 ,'Inclusão' ,'Inclusão' ,'fum1_associadotiposservicos001.php' ,'1' ,
                                       '1' ,'Inclusão dos tipos de serviços do FUMAM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228716 ,228717 ,1 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228718 ,'Alteração' ,'Alteração' ,'fum1_associadotiposservicos002.php' ,'1' ,
                                       '1' ,'Alteração dos tipos de serviços do FUMAM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228716 ,228718 ,2 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228719 ,'Exclusão' ,'Exclusão' ,'fum1_associadotiposservicos003.php' ,'1' ,
                                       '1' ,'Exclusão dos tipos de serviços do FUMAM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228716 ,228719 ,3 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228720 ,'Serviços' ,'Serviços' ,'' ,'1' ,'1' ,'Cadastro de Serviços do FUMAM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 29 ,228720 ,308 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228721 ,'Inclusão' ,'Inclusão de Serviços' ,'fum4_associadoservicos001.php' ,
                                       '1' ,'1' ,'Inclusão de Serviços do FUMAM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228720 ,228721 ,1 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228722 ,'Alteração' ,'Alteração de Serviço' ,'fum4_associadoservicos002.php' ,
                                       '1' ,'1' ,'Alteração de Serviços do FUMAM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228720 ,228722 ,2 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228723 ,'Exclusão' ,'Exclusão de Serviço' ,'fum4_associadoservicos003.php' ,
                                       '1' ,'1' ,'Exclusão de Serviços do FUMAM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228720 ,228723 ,3 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228749 ,'Situação Associados' ,'Situação Associados' ,'' ,'1' ,'1' ,'Cadastro de situações' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 29 ,228749 ,310 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228750 ,'Inclusão' ,'Inclusão' ,'fum1_associadosituacao001.php' ,'1' ,'1' ,'Cadastro de inclusão de situações' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228749 ,228750 ,1 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228751 ,'Alteração' ,'Alteração' ,'fum1_associadosituacao002.php' ,'1' ,'1' ,'Altera as situações ' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228749 ,228751 ,2 ,228703 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              VALUES ( 228752 ,'Exclusão' ,'Exclusão' ,'fum1_associadosituacao003.php' ,'1' ,'1' ,'Exclui as situações cadastradas' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228749 ,228752 ,3 ,228703 );
SQL
        );
    }

}
