<?php

use Classes\PostgresMigration;

class M14863AgendaGeracaoCuboBi extends PostgresMigration
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

    public function up()
    {
        $this->upDicionarioDados();
        $this->upEstruturaCuboBi();
        $this->upAtualizaSequencia();
        $this->upInsertNovoGrupo();
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downEstruturaCuboBi();
    }

    public function upDicionarioDados()
    {
        $this->execute(<<<SQL

               insert into db_sysarquivo values (1010484, 'relatorioscubos', 'Tabela para agenda de cubo bi', 'db126', '2019-12-02', 'Relatórios Cubos', 0, 'f', 'f', 'f', 'f' );

               insert into db_sysarqmod values (7,1010484);

               insert into db_syscampo values(1010818,'db126_codigo','int4','Código','0', 'Código',8,'f','f','f',1,'text','Código');
               insert into db_syscampo values(1010819,'db126_tipo','int4','Tipo','null', 'Tipo',4,'f','f','f',1,'text','Tipo');
               insert into db_syscampo values(1010820,'db126_periodicidade','varchar(20)','Periodicidade','', 'Periodicidade',20,'f','f','f',0,'text','Periodicidade');
               insert into db_syscampo values(1010821,'db126_cubo','int4','Código Cubo','0', 'Código Cubo',8,'f','f','f',1,'text','Código Cubo');

               insert into db_sysarqcamp values(1010484,1010818,1,0);
               insert into db_sysarqcamp values(1010484,1010821,2,0);
               insert into db_sysarqcamp values(1010484,1010819,3,0);
               insert into db_sysarqcamp values(1010484,1010820,4,0);

               insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010484,1010818,1,1010818);

               insert into db_syssequencia values(1000857, 'relatorioscubos_db126_codigo_seq', 1, 1, 9223372036854775807, 1, 1);

               update db_sysarqcamp set codsequencia = 1000857 where codarq = 1010484 and codcam = 1010818;
SQL
);
    }

    public function downDicionarioDados()
    {
        $this->execute(<<<SQL

            delete from db_sysarqcamp where codarq = 1010484 and codcam in (1010818,1010819,1010820,1010821);
            delete from db_syscampo where codcam in (1010818,1010819,1010820,1010821);

            delete from db_sysprikey where codarq = 1010484;
            delete from db_syssequencia where codsequencia = 1000857;

            delete from db_sysarqmod where codarq = 1010484;
            delete from db_sysarquivo where codarq = 1010484;

SQL
);
    }

    public function upEstruturaCuboBi()
    {
        $this->execute(<<<SQL
            CREATE sequence if not exists configuracoes.relatorioscubos_db126_codigo_seq
                     INCREMENT 1
                     MINVALUE 1
                     MAXVALUE 9223372036854775807
                     START 1
                     CACHE 1;

            CREATE TABLE IF NOT EXISTS configuracoes.relatorioscubos(
                db126_codigo integer not null,
                db126_cubo integer not null,
                db126_tipo integer not null,
                db126_periodicidade varchar(20) not null
            );

SQL
);
    }

    public function downEstruturaCuboBi()
    {
        $this->execute("DROP TABLE configuracoes.relatorioscubos;");
    }

    public function upAtualizaSequencia()
    {
        $this->execute("SELECT setval('db_gruporelatorio_db13_sequencial_seq', (select max(db13_sequencial) from db_gruporelatorio), true);");
    }

    public function upInsertNovoGrupo()
    {
        $this->execute("insert into db_gruporelatorio select nextval('db_gruporelatorio_db13_sequencial_seq'), 'Cubo BI Temporario';");
    }



}
