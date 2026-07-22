<?php

use Classes\PostgresMigration;

class M18161TabelaCobrancaRegistradaWebservice extends PostgresMigration
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
            CREATE TABLE caixa.recibocobrancawebservice (
                k199_sequencial serial not null,
                k199_numnov integer not null,
                k199_convenio integer not null,
                CONSTRAINT recibocobrancawebservice_pk PRIMARY KEY(k199_sequencial)
            );
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            DROP TABLE caixa.recibocobrancawebservice;
SQL
        );
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010800, 'recibocobrancawebservice', 'Salva os recibos que foram registrados por webservice.', 'k199', '2021-05-14', 'Recibo Cobrança Registrada Webservice', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (5,1010800);

            insert into db_syscampo values(1013253,'k199_sequencial','int8','Sequencial da tabela recibocobrancawebservice.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1013254,'k199_numnov','int8','Numnov do recibo','0', 'Numnov',11,'f','f','f',1,'text','Numnov');
            insert into db_syscampo values(1013255,'k199_convenio','int8','Convênio utilizado no recibo que foi registrado por webservice.','0', 'Convênio',11,'f','f','f',1,'text','Convênio');

            insert into db_syssequencia values(1001005, 'recibocobrancawebservice_k199_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysarqcamp values(1010800,1013253,1,1001005);
            insert into db_sysarqcamp values(1010800,1013254,2,0);
            insert into db_sysarqcamp values(1010800,1013255,3,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010800,1013253,1,1013253);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysprikey where codarq = 1010800;
            delete from db_sysarqcamp where codarq = 1010800;
            delete from db_syssequencia where codsequencia = 1001005;
            delete from db_syscampo where codcam in (
                1013253,
                1013254,
                1013255
            );
            delete from db_sysarqmod where codarq = 1010800;
            delete from db_sysarquivo where codarq = 1010800;
SQL
        );
    }
}
