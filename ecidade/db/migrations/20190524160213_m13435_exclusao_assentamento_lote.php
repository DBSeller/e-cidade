<?php

use Classes\PostgresMigration;

class M13435ExclusaoAssentamentoLote extends PostgresMigration
{
    public function up()
    {
        $this->criaTabelaLote();
        $this->criaTabelaVinculo();
        $this->atualizaMenus();
    }

    public function down()
    {
        $this->deletaTabelaVinculo();
        $this->deletaTabelaLote();
        $this->reverteMenus();
    }

    private function criaTabelaLote()
    {
        $sql = "
            INSERT INTO db_sysarquivo VALUES (1010449, 'lotelancamento', 'Guarda os lotes de assentamentos', 'h24', '2019-05-24', 'lotelancamento', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (29,1010449);
            INSERT INTO db_syscampo VALUES(1010522,'h23_sequencial','int4','Sequencial','0', 'Sequencial',15,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1010523,'h23_data','date','Data','null', 'Data',10,'f','f','f',1,'text','Data');
            INSERT INTO db_syscampo VALUES(1010533,'h23_instituicao','int4','Instituição','0', 'Instituição',3,'f','f','f',1,'text','Instituição');
            INSERT INTO db_syscampo VALUES(1010537,'h23_tipoassentamento','int4','Tipo','0', 'Tipo',10,'f','f','f',1,'text','Tipo');
            INSERT INTO db_sysarqcamp VALUES(1010449,1010522,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010449,1010523,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010449,1010533,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010449,1010537,2,0);
            INSERT INTO db_sysforkey VALUES(1010449,1010533,1,83,0);
            INSERT INTO db_sysforkey VALUES(1010449,1010537,1,596,0);
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010449,1010522,1,1010522);
            INSERT INTO db_sysindices VALUES(1008462,'loteassentamento_h23_sequencial_in',1010449,'0');
            INSERT INTO db_syscadind VALUES(1008462,1010522,1);
            INSERT INTO db_syssequencia VALUES(1000837, 'loteassentamento_h23_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000837 WHERE codarq = 1010449 AND codcam = 1010522;

            CREATE SEQUENCE loteassentamento_h23_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE recursoshumanos.lotelancamento(
            h23_sequencial		INT4 NOT NULL DEFAULT 0,
            h23_data		DATE NOT NULL DEFAULT now(),
            h23_instituicao INT4 NOT NULL,
            h23_tipoassentamento INT4 NOT NULL,
            CONSTRAINT loteassentamento_sequ_pk PRIMARY KEY (h23_sequencial));

            ALTER TABLE recursoshumanos.lotelancamento
            ADD CONSTRAINT lotelancamento_db_config_fk FOREIGN KEY (h23_instituicao)
            REFERENCES configuracoes.db_config;

            ALTER TABLE recursoshumanos.lotelancamento
            ADD CONSTRAINT lotelancamento_tipoasse_fk FOREIGN KEY (h23_tipoassentamento)
            REFERENCES recursoshumanos.tipoasse;

            CREATE  INDEX loteassentamento_h23_sequencial_in ON recursoshumanos.lotelancamento(h23_sequencial);
        ";

        $this->execute($sql);
    }

    private function criaTabelaVinculo()
    {
        $sql = "
            INSERT INTO db_sysarquivo VALUES (1010450, 'loteassentamento', 'Guarda o vinculo do lote com os assentamentos', 'h24', '2019-05-24', 'loteassentamento', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (29,1010450);
            INSERT INTO db_syscampo VALUES(1010525,'h24_sequencial','int4','Sequencial','0', 'Sequencial',15,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1010526,'h24_lotelancamento','int4','Lote','0', 'Lote',15,'f','f','f',1,'text','Lote');
            INSERT INTO db_syscampo VALUES(1010527,'h24_assenta','int4','Assentamento','0', 'Assentamento',15,'f','f','f',1,'text','Assentamento');            
            INSERT INTO db_sysarqcamp VALUES(1010450,1010525,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010450,1010526,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010450,1010527,3,0);
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010450,1010525,1,1010525);
            INSERT INTO db_sysforkey VALUES(1010450,1010526,1,1010449,0);
            INSERT INTO db_sysforkey VALUES(1010450,1010527,1,528,0);
            INSERT INTO db_sysindices VALUES(1008463,'loteassentamentovinculo_h24_sequencial_in',1010450,'0');
            INSERT INTO db_syscadind VALUES(1008463,1010525,1);
            INSERT INTO db_syssequencia VALUES(1000838, 'loteassentamentovinculo_h24_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000838 WHERE codarq = 1010450 AND codcam = 1010525;

            CREATE SEQUENCE loteassentamentovinculo_h24_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE recursoshumanos.loteassentamento(
                h24_sequencial		INT4 NOT NULL DEFAULT 0,
                h24_lotelancamento		INT4 NOT NULL DEFAULT 0,
                h24_assenta		INT4 DEFAULT 0,
            CONSTRAINT loteassentamentovinculo_sequ_pk PRIMARY KEY (h24_sequencial));

            ALTER TABLE recursoshumanos.loteassentamento
            ADD CONSTRAINT loteassentamentovinculo_assenta_fk FOREIGN KEY (h24_assenta)
            REFERENCES recursoshumanos.assenta;
            
            ALTER TABLE recursoshumanos.loteassentamento
            ADD CONSTRAINT loteassentamentovinculo_loteassentamento_fk FOREIGN KEY (h24_lotelancamento)
            REFERENCES recursoshumanos.lotelancamento;

            CREATE  INDEX loteassentamentovinculo_h24_sequencial_in ON recursoshumanos.loteassentamento(h24_sequencial);
        ";
        $this->execute($sql);
    }

    private function deletaTabelaVinculo()
    {
        $sql = "
            DELETE FROM db_syssequencia WHERE codsequencia = 1000838;
            DELETE FROM db_syscadind WHERE codind IN (1008463);
            DELETE FROM db_sysindices WHERE codind IN (1008463);
            DELETE FROM db_sysforkey WHERE codarq IN (1010450);
            DELETE FROM db_sysprikey WHERE codarq = 1010450;
            DELETE FROM db_sysarqcamp WHERE codarq IN (1010450);
            DELETE FROM db_syscampo WHERE codcam IN (1010525, 1010526, 1010527);
            DELETE FROM db_sysarqmod WHERE codarq = 1010450;
            DELETE FROM db_sysarquivo WHERE codarq = 1010450;

            DROP TABLE IF EXISTS recursoshumanos.loteassentamento;
            DROP SEQUENCE IF EXISTS loteassentamentovinculo_h24_sequencial_seq;
        ";

        $this->execute($sql);
    }

    private function deletaTabelaLote()
    {
        $sql = "
            DELETE FROM db_syssequencia WHERE codsequencia = 1000837;
            DELETE FROM db_syscadind WHERE codind IN (1008462);
            DELETE FROM db_sysindices WHERE codind IN (1008462);
            DELETE FROM db_sysforkey WHERE codarq = 1010449;
            DELETE FROM db_sysprikey WHERE codarq = 1010449;
            DELETE FROM db_sysarqcamp WHERE codarq IN (1010449);
            DELETE FROM db_syscampo WHERE codcam IN (1010522, 1010523, 1010533, 1010537);
            DELETE FROM db_sysarqmod WHERE codarq = 1010449;
            DELETE FROM db_sysarquivo WHERE codarq = 1010449;

            DROP TABLE IF EXISTS recursoshumanos.lotelancamento;
            DROP SEQUENCE IF EXISTS loteassentamento_h23_sequencial_seq;
        ";

        $this->execute($sql);
    }

    private function atualizaMenus()
    {
        $sql = "
            INSERT INTO db_itensmenu(id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente) VALUES (228127 ,'Exclusão em lote' ,'Exclusão em lote de assentamentos' ,'rec4_exclusao_lote_assentamento.php' ,'1' ,'1' ,'Rotina para excluir os assentamentos criados pelas rotinas de lote.' ,'true');
            INSERT INTO db_itensmenu(id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente) VALUES (228128 ,'Manutenção em Lote' ,'Rotinas de manutenção em lote' ,'' ,'1' ,'1' ,'Engloba as rotinas de manutenção em lote do ponto eletrônico.' ,'true');
            UPDATE db_itensmenu SET descricao = 'Manutenção do Ponto Eletrônico em Lote', help = 'Manutenção do Ponto Eletrônico em Lote' WHERE id_item = 10429;

            DELETE FROM db_menu WHERE id_item_filho IN (228128, 10430, 10429, 10524, 10481, 228127) AND modulo = 2323;
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (10384 ,228128 ,18 ,2323);            
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (228128,10430, 1, 2323);
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (228128,10429, 2, 2323);
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (228128,10524, 3, 2323);
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (228128,10481, 4, 2323);
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (228128,228127, 5, 2323);

        ";
        $this->execute($sql);
    }

    private function reverteMenus()
    {
        $sql = "
            UPDATE db_itensmenu SET descricao = 'Manutenção em Lote', help = 'Manutenção em Lote' WHERE id_item = 10429;
            DELETE FROM db_menu WHERE id_item_filho IN (228128, 10430, 10429, 10524, 10481, 228127) AND modulo = 2323;
            
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (10384,10429, 4, 2323);
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (10384,10430, 5, 2323);
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (10384,10524, 7, 2323);
            INSERT INTO db_menu(id_item ,id_item_filho ,menusequencia ,modulo) VALUES (10384,10481, 8, 2323);

            DELETE FROM db_itensmenu WHERE id_item in (228127, 228128);
        ";
        $this->execute($sql);
    }
}
