<?php

use Classes\PostgresMigration;

class M10265Siconfi extends PostgresMigration
{
    public function up()
    {
        $this->incluirMenu();
        $this->upDicionario();
        $this->alteracaoNomeMenu();
        $this->migraContasConPlanoAtributos();
        $this->upDDL();
        $this->atualizarContasPadroes();
    }

    public function down()
    {
        $this->removerMenu();
        $this->downDicionario();
        $this->removeAlteracaoNomeMenu();
        $this->deletaMigracaoConPlanoAtributos();
        $this->downDDL();
    }

    private function alteracaoNomeMenu()
    {
        $this->execute(<<<MENU
            update db_itensmenu set id_item = 10497 , descricao = 'Emissão' , help = 'Processamento e exportação da matriz de saldo contábil' , funcao = 'con4_matrizsaldocontabil001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Processamento e exportação da matriz de saldo contábil' , libcliente = 'true' where id_item = 10497;
MENU
        );
    }

    private function removeAlteracaoNomeMenu()
    {
        $this->execute(<<<MENU
            update db_itensmenu set id_item = 10497 , descricao = 'Processamento e Emissão' , help = 'Processamento e exportação da matriz de saldo contábil' , funcao = 'con4_matrizsaldocontabil001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Processamento e exportação da matriz de saldo contábil' , libcliente = 'true' where id_item = 10497;
MENU
        );
    }

    private function migraContasConPlanoAtributos()
    {
        $contas = $this->fetchAll("select * from conplanoatributos where c120_anousu = 2018");

        foreach ($contas as $conta) {

            $anoMaximo = $this->fetchRow("select max(c60_anousu) as ano from conplano where c60_codcon = {$conta['c120_conplano']}");

            for($anoMinimo = 2019; $anoMinimo <= $anoMaximo['ano']; $anoMinimo++) {
                $this->execute(
                    "insert into conplanoatributos 
                    values (nextval('conplanoatributos_c120_sequencial_seq'), $anoMinimo, {$conta['c120_conplano']}, {$conta['c120_infocomplementar']}, {$conta['c120_conplanosistema']})");
            }
        }
    }

    private  function deletaMigracaoConPlanoAtributos()
    {
        $this->execute("delete from conplanoatributos where c120_anousu >= 2019");

    }

    private function upDicionario()
    {
        $this->execute(<<<TABELA
            insert into db_sysarquivo values (1010265, 'configuracaoinstituicaosiconfi', 'Configuração de Instituições que devem prestar contas para o Siconfi.', 'c125', '2018-03-01', 'Configuração de Instituições do Siconfi', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (32,1010265);
            insert into db_syscampo values(1009650,'c125_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009651,'c125_db_config','int4','Vínculo com a instituição.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_sysarqcamp values(1010265,1009650,1,0);
            insert into db_sysarqcamp values(1010265,1009651,2,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010265,1009650,1,1009650);
            insert into db_sysforkey values(1010265,1009651,1,83,0);
            insert into db_sysindices values(1008258,'configuracaoinstituicaosiconfi_c125_db_config_in',1010265,'0');
            insert into db_syscadind values(1008258,1009651,1);
            insert into db_syssequencia values(1000720, 'configuracaoinstituicaosiconfi_c125_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000720 where codarq = 1010265 and codcam = 1009650;
TABELA
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<TABELA
            delete from db_syscadind where codcam = 1009651;
            delete from db_sysindices where codarq = 1010265;
            delete from db_sysforkey where codarq = 1010265;
            delete from db_sysprikey where codarq = 1010265;
            delete from db_sysarqcamp where codarq = 1010265;
            delete from db_syssequencia where codsequencia = 1000720;
            delete from db_syscampo where codcam in (1009650, 1009651);
            delete from db_sysarqmod where codarq = 1010265;
            delete from db_sysarquivo where codarq = 1010265;
TABELA
        );
    }

    private function incluirMenu()
    {
        $this->execute(<<<MENU
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10509 ,'Configuração de Instituições' ,'Configuração de instituições do Siconfi' ,'con4_configuracaoinstituicoessiconfi001.php' ,'1' ,'1' ,'Configuração de Instituições que devem prestar contas para o Siconfi.' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10496 ,10509 ,3 ,209 );       
MENU
        );
    }

    private function removerMenu()
    {
        $this->execute(<<<MENU
          delete from db_menu where id_item_filho = 10509 AND modulo = 209;
          delete from db_itensmenu where  id_item = 10509;
MENU
        );
    }

    private function upDDL()
    {
        $this->execute(<<<TABELA
            CREATE SEQUENCE contabilidade.configuracaoinstituicaosiconfi_c125_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE TABLE contabilidade.configuracaoinstituicaosiconfi(
            c125_sequencial		int4 NOT NULL default 0,
            c125_db_config		int4 default 0,
            CONSTRAINT configuracaoinstituicaosiconfi_sequ_pk PRIMARY KEY (c125_sequencial));
            
            ALTER TABLE contabilidade.configuracaoinstituicaosiconfi
            ADD CONSTRAINT configuracaoinstituicaosiconfi_config_fk FOREIGN KEY (c125_db_config)
            REFERENCES db_config;
            
            CREATE  INDEX configuracaoinstituicaosiconfi_c125_db_config_in ON configuracaoinstituicaosiconfi(c125_db_config);
TABELA
        );
    }

    private function downDDL()
    {
        $this->execute(<<<TABELA
            DROP TABLE IF EXISTS configuracaoinstituicaosiconfi CASCADE;
            DROP SEQUENCE IF EXISTS configuracaoinstituicaosiconfi_c125_sequencial_seq;
TABELA
        );
    }

    private function atualizarContasPadroes()
    {
        $this->execute(<<<SQL
         delete from conplano where c60_anousu > 
            (select max(c60_anousu) from conplano 
              where c60_estrut not in('999999999999999', '999999999999998')) 
                AND c60_estrut in('999999999999999', '999999999999998');
SQL
        );
    }

}
