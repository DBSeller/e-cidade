<?php

use Classes\PostgresMigration;

class M17307IssRetidoEmpresaPublica extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            CREATE TABLE issqn.confissqnretidopublica
            (
                j170_sequencial serial not null,
                j170_receit integer not null,
                j170_anousu integer not null,
                CONSTRAINT confvencissqnretidopublica_pk PRIMARY KEY (j170_sequencial),
                CONSTRAINT confvencissqnretidopublica_tabrec_fk FOREIGN KEY (j170_receit) REFERENCES caixa.tabrec(k02_codigo)
            );

            CREATE TABLE issqn.confissqnretidopublicatipoempresa
            (
                j171_sequencial serial not null,
                j171_confissqnretidopublica integer not null,
                j171_tipoempresa integer not null,
                CONSTRAINT confissqnretidopublicatipoempresa_pk PRIMARY KEY (j171_sequencial),
                CONSTRAINT confvencissqnretidopublica_confissqnretidopublica_fk FOREIGN KEY (j171_confissqnretidopublica) REFERENCES issqn.confissqnretidopublica(j170_sequencial),
                CONSTRAINT confvencissqnretidopublica_tipoempresa_fk FOREIGN KEY (j171_tipoempresa) REFERENCES configuracoes.tipoempresa(db98_sequencial)
            );
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            DROP TABLE issqn.confissqnretidopublicatipoempresa;
            DROP TABLE issqn.confissqnretidopublica;
SQL
        );
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            INSERT INTO db_sysarquivo VALUES (1010650, 'confissqnretidopublica', 'Guarda as configurações de ISSQN Retido para Empresas Públicas.', 'j170', '2021-01-20', 'Configuração ISSQN Retido Empresa Pública', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (3,1010650);

            INSERT INTO db_syscampo VALUES(1011993,'j170_sequencial','int4','Sequencial da tabela confissqnretidopublica.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011994,'j170_receit','int4','Receita da configuração de ISSQN para empresa pública.','0', 'Receita',11,'f','f','f',1,'text','Receita');
            INSERT INTO db_syscampo VALUES(1011995,'j170_anousu','int4','Exercício da tabela confissqnretidopublica.','0', 'Exercício',11,'f','f','f',1,'text','Exercício');

            INSERT INTO db_syssequencia VALUES(1000984, 'confissqnretidopublica_j170_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010650,1011993,1,1000984);
            INSERT INTO db_sysarqcamp VALUES(1010650,1011994,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010650,1011995,3,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010650,1011993,1,1011993);

            INSERT INTO db_sysforkey VALUES(1010650,1011994,1,75,0);

            INSERT INTO db_sysarquivo VALUES (1010651, 'confissqnretidopublicatipoempresa', 'Guarda os tipos de empresa que usarão a configuração da tabela confissqnretidopublica.', 'j171', '2021-01-20', 'Tipo empresa publica ISSQN Retido', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (3,1010651);

            INSERT INTO db_syscampo VALUES(1011996,'j171_sequencial','int4','Sequencial da tabela confissqnretidopublicatipoempresa.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011997,'j171_confissqnretidopublica','int4','Chave estrangeira com a tabela confissqnretidopublica.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011998,'j171_tipoempresa','int4','Tipo de empresa que usava as configurações de empresa pública.','0', 'Tipo de Empresa',11,'f','f','f',1,'text','Tipo de Empresa');

            INSERT INTO db_syssequencia VALUES(1000985, 'confissqnretidopublicatipoempresa_j171_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010651,1011996,1,1000985);
            INSERT INTO db_sysarqcamp VALUES(1010651,1011997,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010651,1011998,3,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010651,1011996,1,1011996);

            INSERT INTO db_sysforkey VALUES(1010651,1011997,1,1010650,0);
            INSERT INTO db_sysforkey VALUES(1010651,1011998,1,2844,0);

SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            DELETE FROM db_sysforkey WHERE codarq IN (
                /* confissqnretidopublica */
                1010650,
                /* confissqnretidopublicatipoempresa */
                1010651
            );

            DELETE FROM db_sysprikey WHERE codarq IN (
                /* confissqnretidopublica */
                1010650,
                /* confissqnretidopublicatipoempresa */
                1010651
            );

            DELETE FROM db_sysarqcamp WHERE codarq IN (
                /* confissqnretidopublica */
                1010650,
                /* confissqnretidopublicatipoempresa */
                1010651
            );

            DELETE FROM db_syssequencia WHERE codsequencia IN (
                /* confissqnretidopublica */
                1000984,
                /* confissqnretidopublicatipoempresa */
                1000985
            );

            DELETE FROM db_syscampo WHERE codcam IN (
                /* confissqnretidopublica */
                1011993,
                1011994,
                1011995,
                /* confissqnretidopublicatipoempresa */
                1011996,
                1011997,
                1011998
            );

            DELETE FROM db_sysarqmod WHERE codarq IN (
                /* confissqnretidopublica */
                1010650,
                /* confissqnretidopublicatipoempresa */
                1010651
            );

            DELETE FROM db_sysarquivo WHERE codarq IN (
                /* confissqnretidopublica */
                1010650,
                /* confissqnretidopublicatipoempresa */
                1010651
            );
SQL
        );
    }
}
