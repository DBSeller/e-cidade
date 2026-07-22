<?php

use Classes\PostgresMigration;

class M11943LancamentoDeRubricasPorUsuario extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
        $this->upMenu();
    }

    private function upEstrutura()
    {
        $this->execute("
            CREATE SEQUENCE rubricasusuario_rh219_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            
            CREATE TABLE rubricasusuario(
                rh219_sequencial int4,
                rh219_usuario int4,
                rh219_instituicao int4,
                rh219_rubrica varchar(4),
                CONSTRAINT rubricasusuario_sequ_pk PRIMARY KEY (rh219_sequencial)
            );
            
            ALTER TABLE rubricasusuario ADD CONSTRAINT rubricasusuario_usuario_fk FOREIGN KEY (rh219_usuario) REFERENCES db_usuarios;
            ALTER TABLE rubricasusuario ADD CONSTRAINT rubricasusuario_rubrica_instituicao_fk FOREIGN KEY (rh219_rubrica,rh219_instituicao) REFERENCES rhrubricas;
            
            CREATE UNIQUE INDEX rubricasusuario_usuario_instituicao_rubrica_in ON rubricasusuario(rh219_usuario,rh219_instituicao,rh219_rubrica);
        ");
    }

    private function upDicionario()
    {
        $this->execute("
            insert into db_sysarquivo values (1010332, 'rubricasusuario', 'Rubricas por usuário', 'rh219', '2018-10-26', 'Rubricas por usuário', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010332);
            insert into db_syscampo values(1010044,'rh219_sequencial','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1010045,'rh219_usuario','int4','Usuário','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
            insert into db_syscampo values(1010046,'rh219_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_syscampo values(1010047,'rh219_rubrica','varchar(4)','Rubrica configurada','', 'Rubrica',4,'f','t','f',0,'text','Rubrica');
            insert into db_sysarqcamp values(1010332,1010044,1,0);
            insert into db_sysarqcamp values(1010332,1010045,2,0);
            insert into db_sysarqcamp values(1010332,1010046,3,0);
            insert into db_sysarqcamp values(1010332,1010047,4,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010332,1010044,1,1010044);
            insert into db_sysforkey values(1010332,1010045,1,109,0);
            insert into db_sysforkey values(1010332,1010047,1,1177,0);
            insert into db_sysforkey values(1010332,1010046,2,1177,0);
            insert into db_sysindices values(1008340,'rubricasusuario_usuario_instituicao_rubrica_in',1010332,'1');
            insert into db_syscadind values(1008340,1010045,1);
            insert into db_syscadind values(1008340,1010046,2);
            insert into db_syscadind values(1008340,1010047,3);
            insert into db_syssequencia values(1000776, 'rubricasusuario_rh219_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000776 where codarq = 1010332 and codcam = 1010044;
        ");
    }

    private function upMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            VALUES (228051, 'Rubricas por Usuário', 'Rubricas por Usuário', 'pes1_rubricas_usuario_1.php', DEFAULT, '1', 'Rubricas por Usuário', TRUE);
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
            VALUES (3516, 228051, 16, 952);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
        $this->downMenu();
    }

    private function downEstrutura()
    {
        $this->execute("
            DROP TABLE IF EXISTS rubricasusuario CASCADE;
            DROP SEQUENCE IF EXISTS rubricasusuario_rh219_sequencial_seq;
        ");
    }


    private function downDicionario()
    {
        $this->execute("
            delete from db_syssequencia where codsequencia = 1000776;
            delete from db_syscadind where codind = 1008340;
            delete from db_sysindices where codind = 1008340;
            delete from db_sysprikey where codarq = 1010332;
            delete from db_sysforkey where codarq = 1010332;
            delete from db_sysarqcamp where codarq = 1010332;
            delete from db_syscampo where codcam in (1010044, 1010045, 1010046, 1010047);
            delete from db_sysarqmod where codarq = 1010332;
            delete from db_sysarquivo where codarq = 1010332;
        ");
    }


    private function downMenu()
    {
        $sql = "
            DELETE FROM db_menu WHERE id_item_filho = 228051;
            DELETE FROM db_itensmenu WHERE id_item = 228051;
        ";

        $this->execute($sql);
    }
}
