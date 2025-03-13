<?php

use Classes\PostgresMigration;

class M17080CriacaoGruposExame extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(
<<<SQL
            --lab_grupo
            INSERT INTO db_sysarquivo VALUES (1010665, 'lab_grupo','Grupo de Exames','la66','2021-01-22','Grupo de Exames',0,'f','f','f','f');
            INSERT INTO db_sysarqmod VALUES (67,1010665);
            INSERT INTO db_syscampo VALUES(1012002,'la66_codigo','int4','Código do Grupo','0', 'Código do Grupo',10,'f','f','f',1,'text','Código do Grupo');
            INSERT INTO db_syscampo VALUES(1012003,'la66_descricao','varchar(255)','Descrição do Grupo','', 'Descrição do Grupo',255,'f','f','f',0,'text','Descrição do Grupo');
            INSERT INTO db_sysarqcamp VALUES(1010665,1012002,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010665,1012003,2,0);
            INSERT into db_syssequencia VALUES(1000987, 'lab_grupo_la66_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000987 WHERE codarq = 1010665 AND codcam = 1012002;
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010665,1012002,1,1012002);

            --lab_grupoexame
            INSERT INTO db_sysarquivo VALUES (1010668, 'lab_grupoexame', 'Vinculo de Grupo com Exames', 'la68', '2021-01-28', 'Vinculo de Grupo com Exames', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (67,1010668);
            INSERT INTO db_syscampo VALUES(1012010,'la68_codigo','int4','Código do Grupo de Exames','0', 'Código do Grupo de Exames',10,'f','f','f',1,'text','Código do Grupo de Exames');
            INSERT INTO db_syscampo VALUES(1012012,'la68_exame','int4','Código do Exame','0', 'Código do Exame',10,'f','f','f',1,'text','Código do Exame');
            INSERT INTO db_syscampo VALUES(1012120,'la68_labgrupoexame','int4','Grupo Laboratório','0', 'Grupo Laboratório',10,'f','f','f',1,'text','Grupo Laboratório');
            INSERT INTO db_sysarqcamp VALUES(1010668,1012010,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010668,1012012,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010668,1012120,3,0);
            INSERT INTO db_syssequencia VALUES(1000988, 'lab_grupoexame_la68_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000988 WHERE codarq = 1010668 AND codcam = 1012010;
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010668,1012010,1,1012010);
            insert into db_sysforkey values(1010668,1012120,1,1010667,0);
            INSERT INTO db_sysforkey VALUES(1010668,1012012,1,2758,0);

            --lab_labgrupoexame
            INSERT INTO db_sysarquivo VALUES (1010667, 'lab_labgrupoexame', 'Vinculo de Grupo de Exames com Laboratório', 'la67', '2021-01-28', 'Vinculo de Grupo de Exames com Laboratório', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (67,1010667);
            INSERT INTO db_syscampo VALUES(1012004,'la67_codigo','int4','Código do vinculo do grupo de exame com o laboratório','0', 'Código',10,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo VALUES(1012005,'la67_laboratorio','int4','Código do laboratório','0', 'Código do laboratório',10,'f','f','f',1,'text','Código do laboratório');
            INSERT INTO db_syscampo VALUES(1012119,'la67_grupo','int4','Grupo','0', 'Grupo',10,'f','f','f',1,'text','Grupo');
            INSERT INTO db_sysarqcamp VALUES(1010667,1012004,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010667,1012005,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010667,1012119,3,0);
            INSERT into db_syssequencia VALUES(1000989, 'lab_labgrupoexame_la67_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000989 WHERE codarq = 1010667 AND codcam = 1012004;
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010667,1012004,1,1012004);
            INSERT INTO db_sysforkey VALUES(1010667,1012005,1,2753,0);
            INSERT INTO db_sysforkey VALUES(1010667,1012119,1,1010665,0);

            CREATE SEQUENCE laboratorio.lab_grupo_la66_codigo_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            --Cria tabela para cadastro de grupo de exames.
            CREATE TABLE laboratorio.lab_grupo (
                la66_codigo INTEGER NOT NULL default nextval('laboratorio.lab_grupo_la66_codigo_seq'),
                la66_descricao VARCHAR(25) NOT NULL,
                CONSTRAINT lab_grupo_sequ_pk PRIMARY KEY (la66_codigo)
            );

            CREATE SEQUENCE laboratorio.lab_labgrupoexame_la67_codigo_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            --Cria tabela de vinculo entre a tabela de vinculo de grupo de exames com exames com laboratorio.
            CREATE TABLE laboratorio.lab_labgrupoexame (
                la67_codigo INTEGER NOT NULL DEFAULT nextval('laboratorio.lab_labgrupoexame_la67_codigo_seq'),
                la67_laboratorio INTEGER NOT NULL,
                la67_grupo INTEGER NOT NULL,
                CONSTRAINT lab_labgrupoexame_sequ_pk PRIMARY KEY (la67_codigo)
            );

            ALTER TABLE laboratorio.lab_labgrupoexame
                ADD CONSTRAINT lab_labgrupoexame_lab_laboratorio_fk FOREIGN KEY (la67_laboratorio)
                REFERENCES laboratorio.lab_laboratorio(la02_i_codigo),
                ADD CONSTRAINT lab_labgrupoexame_lab_grupo_fk FOREIGN KEY (la67_grupo)
                REFERENCES laboratorio.lab_grupo(la66_codigo);

            CREATE SEQUENCE laboratorio.lab_grupoexame_la68_codigo_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            --Cria tabela de vinculo entre a tabela de grupo de exames com exames.
            CREATE TABLE laboratorio.lab_grupoexame (
                la68_codigo INTEGER NOT NULL DEFAULT nextval('laboratorio.lab_grupoexame_la68_codigo_seq'),
                la68_labgrupoexame INTEGER NOT NULL,
                la68_exame INTEGER NOT NULL,
                CONSTRAINT lab_grupoexame_sequ_pk PRIMARY KEY (la68_codigo)
            );

            ALTER TABLE laboratorio.lab_grupoexame
                ADD CONSTRAINT lab_grupoexame_lab_exame_fk FOREIGN KEY (la68_exame)
                REFERENCES laboratorio.lab_exame(la08_i_codigo),
                ADD CONSTRAINT lab_grupoexame_lab_labgrupoexame_fk FOREIGN KEY (la68_labgrupoexame)
                REFERENCES laboratorio.lab_labgrupoexame(la67_codigo);

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228370 ,'Grupos de Exames' ,'Grupos de Exames' ,'lab1_grupoExame001.php' ,'1' ,'1' ,'Onde é possível efetuar a criação de grupos de exames.' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 8170 ,228370 ,18 ,8167 );

            UPDATE db_menu SET menusequencia = 1 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8399;
            UPDATE db_menu SET menusequencia = 2 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8190;
            UPDATE db_menu SET menusequencia = 3 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8194;
            UPDATE db_menu SET menusequencia = 4 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8178;
            UPDATE db_menu SET menusequencia = 5 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8198;
            UPDATE db_menu SET menusequencia = 6 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8202;
            UPDATE db_menu SET menusequencia = 7 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8210;
            UPDATE db_menu SET menusequencia = 8 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8214;
            UPDATE db_menu SET menusequencia = 9 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8206;
            UPDATE db_menu SET menusequencia = 10 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8186;
            UPDATE db_menu SET menusequencia = 11 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 228370;
            UPDATE db_menu SET menusequencia = 12 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8182;
            UPDATE db_menu SET menusequencia = 13 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8359;
            UPDATE db_menu SET menusequencia = 14 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 8451;
            UPDATE db_menu SET menusequencia = 15 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 7998;
            UPDATE db_menu SET menusequencia = 16 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 10182;
            UPDATE db_menu SET menusequencia = 17 WHERE id_item = 8170 AND modulo = 8167 AND id_item_filho = 10239;
SQL
        );

    }

    public function down()
    {
        $this->execute(
<<<SQL
            DELETE FROM db_sysforkey WHERE codarq = 1010668;
            DELETE FROM db_sysforkey WHERE codarq = 1010667;

            DELETE FROM db_sysprikey WHERE codarq = 1010668;
            DELETE FROM db_sysprikey WHERE codarq = 1010667;
            DELETE FROM db_sysprikey WHERE codarq = 1010665;

            DELETE FROM db_syssequencia WHERE codsequencia = 1000987;
            DELETE FROM db_syssequencia WHERE codsequencia = 1000988;
            DELETE FROM db_syssequencia WHERE codsequencia = 1000989;

            DELETE FROM db_sysarqcamp WHERE codarq = 1010668;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010667;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010665;

            DELETE FROM db_syscampo WHERE codcam = 1012012;
            DELETE FROM db_syscampo WHERE codcam = 1012120;
            DELETE FROM db_syscampo WHERE codcam = 1012010;
            DELETE FROM db_syscampo WHERE codcam = 1012119;
            DELETE FROM db_syscampo WHERE codcam = 1012005;
            DELETE FROM db_syscampo WHERE codcam = 1012004;
            DELETE FROM db_syscampo WHERE codcam = 1012003;
            DELETE FROM db_syscampo WHERE codcam = 1012002;
            
            DELETE FROM db_sysarqmod WHERE codarq = 1010668;
            DELETE FROM db_sysarqmod WHERE codarq = 1010667;
            DELETE FROM db_sysarqmod WHERE codarq = 1010665;

            DELETE FROM db_sysarquivo WHERE codarq = 1010668;
            DELETE FROM db_sysarquivo WHERE codarq = 1010667;
            DELETE FROM db_sysarquivo WHERE codarq = 1010665;

            DROP TABLE laboratorio.lab_grupoexame;
            DROP TABLE laboratorio.lab_labgrupoexame;
            DROP TABLE laboratorio.lab_grupo;

            DROP SEQUENCE laboratorio.lab_grupoexame_la68_codigo_seq;
            DROP SEQUENCE laboratorio.lab_labgrupoexame_la67_codigo_seq;
            DROP SEQUENCE laboratorio.lab_grupo_la66_codigo_seq;

            DELETE FROM db_menu WHERE id_item_filho = 228370;
            DELETE FROM db_itensmenu WHERE id_item = 228370;
SQL
        );

    }
}
