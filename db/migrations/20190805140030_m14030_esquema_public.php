<?php

use Classes\PostgresMigration;

class M14030EsquemaPublic extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL
            insert into db_sysarquivo values (1010462, 'm14030_semschema', 'Testando script que atualiza a base schema.', '', '2019-08-05', 'm14030_semschema', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (7,1010462);
            insert into db_sysarquivo values (1010463, 'm14030_comschema', 'Testando script que atualiza a base schema.', '', '2019-08-05', 'm14030_comschema', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (7,1010463);
            insert into db_syscampo values(1010652,'semschema','int4','Testa cadastro schema.','0', 'Sem Schema',10,'f','f','f',1,'text','Sem Schema');
            insert into db_syscampo values(1010653,'comschema','int4','Testando schema.','0', 'Com Schema',10,'f','f','f',1,'text','Com Schema');
            insert into db_sysarqcamp values(1010463,1010653,1,0);
            insert into db_sysarqcamp values(1010462,1010652,1,0);

            create table m14030_semschema(
                semschema int not null
            );

            create table configuracoes.m14030_comschema(
                comschema int not null
            );
SQL;
        $this->execute($sql);

    }

    public function down()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codarq in (1010462, 1010463);
            delete from db_syscampo where codcam in (1010652, 1010653);
            delete from db_sysarqmod where codarq in (1010462, 1010463);
            delete from db_sysarquivo where codarq in (1010462, 1010463);
            drop table m14030_semschema;
            drop table m14030_comschema;
SQL;
        $this->execute($sql);
    }
}
