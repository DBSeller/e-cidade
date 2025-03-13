<?php

use Classes\PostgresMigration;

class M18690CriaTabelaAuxiliarReciboAvulso extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            CREATE TABLE caixa.reciboavulsoboleto
            (
                k201_sequencial SERIAL NOT NULL,
                k201_numpre INTEGER NOT NULL,
                k201_data TIMESTAMP NOT NULL,
                k201_usuario INTEGER NOT NULL,
                k201_ip TEXT NOT NULL,
                CONSTRAINT reciboavulsoboleto_pk PRIMARY KEY(k201_sequencial),
                CONSTRAINT reciboavulsoboleto_usuario_fk FOREIGN KEY(k201_usuario) REFERENCES db_usuarios(id_usuario)
            );

            insert into db_sysarquivo values (1010816, 'reciboavulsoboleto', 'Tabela auxiliar para recibos avulsos', 'k201', '2021-07-27', 'Recibo Avulso Boleto', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (5,1010816);

            insert into db_syscampo values(1013364,'k201_sequencial','int4','Sequencial da tabela reciboavulsoboleto','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1013365,'k201_numpre','int4','Numpre do Recibo avulso','0', 'Numpre',11,'f','f','f',1,'text','Numpre');
            insert into db_syscampo values(1013366,'k201_data','text','Data da inclusão','', 'Data',20,'f','t','f',0,'text','Data');
            insert into db_syscampo values(1013367,'k201_usuario','int4','Usuário que executou a inclusão','0', 'Usuário',11,'f','f','f',1,'text','Usuário');
            insert into db_syscampo values(1013368,'k201_ip','text','IP da maquina utilizada na inclusão','', 'IP',40,'f','t','f',0,'text','IP');

            insert into db_syssequencia values(1001012, 'reciboavulsoboleto_k201_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysarqcamp values(1010816,1013364,1,1001012);
            insert into db_sysarqcamp values(1010816,1013365,2,0);
            insert into db_sysarqcamp values(1010816,1013366,3,0);
            insert into db_sysarqcamp values(1010816,1013367,4,0);
            insert into db_sysarqcamp values(1010816,1013368,5,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010816,1013364,1,1013364);
            insert into db_sysforkey values(1010816,1013367,1,109,0);

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            DROP TABLE caixa.reciboavulsoboleto;

            DELETE FROM db_sysprikey WHERE codarq = 1010816;
            DELETE FROM db_sysforkey WHERE codarq = 1010816;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010816;

            DELETE FROM db_syssequencia WHERE codsequencia = 1001012;

            DELETE FROM db_syscampo WHERE codcam in (
                1013364,
                1013365,
                1013366,
                1013367,
                1013368
            );

            DELETE FROM db_sysarqmod WHERE codarq = 1010816;
            DELETE FROM db_sysarquivo WHERE codarq = 1010816;
SQL
        );
    }
}
