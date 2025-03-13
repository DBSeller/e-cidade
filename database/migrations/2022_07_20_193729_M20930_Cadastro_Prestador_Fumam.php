<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M20930CadastroPrestadorFumam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upTabelas();
        $this->upMenuDicionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downTabelas();
        $this->downMenuDicionario();
    }

    private function upTabelas()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl ENABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop ENABLE;

            -- Criação das tabelas básicas e sequencial
            -- tabela de prestador FUMAM

            CREATE TABLE IF NOT EXISTS fumam.prestadores (
                fm06_codigo serial PRIMARY KEY,
                fm06_depart integer NOT NULL,
                fm06_numcgm integer NOT NULL UNIQUE,
                CONSTRAINT prestadores_depart_fk FOREIGN KEY (fm06_depart) 
                    REFERENCES db_depart(coddepto),
                CONSTRAINT prestadores_cgm_fk FOREIGN KEY (fm06_numcgm) 
                    REFERENCES cgm(z01_numcgm)
            );

            COMMENT ON TABLE fumam.prestadores IS
                '{"descricao": "Tabela que armazena os parâmetros dos prestadores da FUMAM",
                  "sigla": "fm06",
                  "dataincl": "2022-06-30",
                  "rotulo": "prestadores",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';

            COMMENT ON COLUMN fumam.prestadores.fm06_codigo IS
                '{ "descricao": "Código do Prestador",
                   "rotulo": "Código do Prestador",
                   "rotulorel": "Código do Prestador",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.prestadores.fm06_depart IS
                '{ "descricao": "Departamento do Prestador",
                   "rotulo": "Departamento do Prestador",
                   "rotulorel": "Departamento do Prestador",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.prestadores.fm06_numcgm IS
                '{ "descricao": "CGM do Prestador",
                   "rotulo": "CGM do Prestador",
                   "rotulorel": "CGM do Prestador",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            -- tabela de profissionais/prestador FUMAM

            CREATE TABLE IF NOT EXISTS fumam.profissionaisprestadores (
                fm07_codigo serial PRIMARY KEY,
                fm07_prestador integer NOT NULL,
                fm07_numcgm integer NOT NULL,
                fm07_orgaoemissor integer NOT NULL,
                fm07_regprof varchar NOT NULL,
                fm07_situacao integer NOT NULL,
                fm07_cbo integer NOT NULL,
                CONSTRAINT profissionaisprestadores_prestador_fk FOREIGN KEY (fm07_prestador)
                    REFERENCES fumam.prestadores(fm06_codigo),
                CONSTRAINT profissionaisprestadores_cgm_fk FOREIGN KEY (fm07_numcgm)
                    REFERENCES cgm(z01_numcgm),
                CONSTRAINT profissionaisprestadores_orgaoemissor_fk FOREIGN KEY (fm07_orgaoemissor)
                    REFERENCES sau_orgaoemissor(sd51_i_codigo),
                CONSTRAINT profissionaisprestadores_situacao_fk FOREIGN KEY (fm07_situacao)
                    REFERENCES fumam.associadosituacao(fm02_situacao),
                CONSTRAINT profissionaisprestadores_cbo_fk FOREIGN KEY (fm07_cbo)
                    REFERENCES rhcbo(rh70_sequencial),
                    CONSTRAINT profissionaisprestadores_cbo_uk UNIQUE (fm07_prestador, fm07_numcgm)
            );

            COMMENT ON TABLE fumam.profissionaisprestadores IS
                '{"descricao": "Tabela que armazena os parâmetros Profissionais dos prestadores da FUMAM",
                  "sigla": "fm07",
                  "dataincl": "2022-06-30",
                  "rotulo": "profissionaisprestadores",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';

            COMMENT ON COLUMN fumam.profissionaisprestadores.fm07_codigo IS
                '{ "descricao": "Código do Profissional",
                   "rotulo": "Código do Profissional",
                   "rotulorel": "Código do Profissional",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionaisprestadores.fm07_prestador IS
                '{ "descricao": "Código do Prestador",
                   "rotulo": "Código do Prestador",
                   "rotulorel": "Código do Prestador",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionaisprestadores.fm07_numcgm IS
                '{ "descricao": "CGM do Profissional",
                   "rotulo": "CGM do Profissional",
                   "rotulorel": "CGM do Profissional",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionaisprestadores.fm07_orgaoemissor IS
                '{ "descricao": "Órgão emissor do documento",
                   "rotulo": "Órgão emissor do documento",
                   "rotulorel": "Órgão emissor do documento",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionaisprestadores.fm07_regprof IS
                '{ "descricao": "Registro Profissional",
                   "rotulo": "Registro Profissional",
                   "rotulorel": "Registro Profissional",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 3,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.profissionaisprestadores.fm07_situacao IS
                '{ "descricao": "Situação do Profissional",
                   "rotulo": "Situação do Profissional",
                   "rotulorel": "Situação do Profissional",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;
            COMMENT ON COLUMN fumam.profissionaisprestadores.fm07_cbo IS
                '{ "descricao": "CBO do Profissional",
                   "rotulo": "CBO do Profissional",
                   "rotulorel": "CBO do Profissional",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;                 

            -- tabela de servicos/prestador FUMAM

            CREATE TABLE IF NOT EXISTS fumam.servicosprestadores (
                fm08_codigo serial PRIMARY KEY,
                fm08_prestador integer NOT NULL,
                fm08_servico integer NOT NULL,
                fm08_situacao integer NOT NULL,
                fm08_autoriza boolean NOT NULL,
                CONSTRAINT servicosprestadores_prestadores_fk FOREIGN KEY (fm08_prestador)
                    REFERENCES fumam.prestadores(fm06_codigo),
                CONSTRAINT servicosprestadores_servico_fk FOREIGN KEY (fm08_servico)
                    REFERENCES fumam.associadoservicos(fm12_codigo),
                CONSTRAINT servicosprestadores_situacao_fk FOREIGN KEY (fm08_situacao)
                    REFERENCES fumam.associadosituacao(fm02_situacao)
            );

            COMMENT ON TABLE fumam.servicosprestadores IS
                '{"descricao": "Tabela que armazena os parâmetros de Serviço dos prestadores da FUMAM",
                  "sigla": "fm08",
                  "dataincl": "2022-06-30",
                  "rotulo": "servicosprestadores",
                  "tipotabela": "0",
                  "naolibclass": "false",
                  "naolibfunc": "false",
                  "naolibprog": "false",
                  "naolibform": "false"
                 }';

            COMMENT ON COLUMN fumam.servicosprestadores.fm08_codigo IS
                '{ "descricao": "Código dos Serviços Prestados",
                   "rotulo": "Código dos Serviços Prestados",
                   "rotulorel": "Código dos Serviços Prestados",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.servicosprestadores.fm08_prestador IS
                '{ "descricao": "Código do Prestador",
                   "rotulo": "Código do Prestador",
                   "rotulorel": "Código do Prestador",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.servicosprestadores.fm08_servico IS
                '{ "descricao": "Código do Serviço Prestado",
                   "rotulo": "Código do Serviço Prestado",
                   "rotulorel": "Código do Serviço Prestado",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.servicosprestadores.fm08_situacao IS
                '{ "descricao": "Código da Situação",
                   "rotulo": "Código da Situação",
                   "rotulorel": "Código da Situação",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 1,
                   "tamanho": 10,
                   "tipoobj": "text"
                 }' ;

            COMMENT ON COLUMN fumam.servicosprestadores.fm08_autoriza IS
                '{ "descricao": "Autoriza Pedido",
                   "rotulo": "Autoriza Pedido",
                   "rotulorel": "Autoriza Pedido",
                   "maiusculo": false,
                   "autocompl": false,
                   "aceitatipo": 5,
                   "tamanho": 1,
                   "tipoobj": "text"
                 }' ;

            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'prestadores');
            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'profissionaisprestadores');
            SELECT fc_gera_dicionario_apartir_tabela('fumam', 'servicosprestadores');

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

            UPDATE db_sysprikey SET camiden = (SELECT codcam FROM db_syscampo WHERE nomecam = 'fm08_prestador')
              WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'servicosprestadores');

SQL
        );
    }

    private function upMenuDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              values ( 228704 ,'Prestadores' ,'Prestadores' ,'' ,'1' ,'1' ,'Menu de cadastro de Prestadores da FUMAN' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 29 ,228704 ,305 ,228703 );
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              values ( 228705 ,'Inclusão' ,'Inclusão' ,'fum4_prestadores001.php' ,'1' ,'1' ,'Inclusão dos prestadores da FUMAN' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228704 ,228705 ,1 ,228703 );
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              values ( 228706 ,'Alteração' ,'Alteração' ,'fum4_prestadores002.php' ,'1' ,'1' ,'Alteração dos prestadores da FUMAN' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228704 ,228706 ,2 ,228703 );
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                              values ( 228707 ,'Exclusão' ,'Exclusão' ,'fum4_prestadores003.php' ,'1' ,'1' ,'Exclusão dos prestadores da FUMAN' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228704 ,228707 ,3 ,228703 );

SQL
        );
    }    

    private function downTabelas()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            SELECT fc_remove_dicionario_tabela('fumam', 'profissionaisprestadores');
            SELECT fc_remove_dicionario_tabela('fumam', 'servicosprestadores');
            SELECT fc_remove_dicionario_tabela('fumam', 'prestadores');

            -- drop das tabelas
            DROP TABLE IF EXISTS fumam.profissionaisprestadores;
            DROP TABLE IF EXISTS fumam.servicosprestadores;
            DROP TABLE IF EXISTS fumam.prestadores;

SQL
        );
    }

    private function downMenuDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            DELETE FROM db_menu WHERE id_item_filho BETWEEN 228704 AND 228707;
            DELETE FROM db_itensmenu WHERE id_item BETWEEN 228704 AND 228707;

SQL
        );
    }    

}
