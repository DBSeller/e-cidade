<?php

use Classes\PostgresMigration;

class M17679 extends PostgresMigration
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
    public function up() {
        $this->upDicionario();
        $this->upEstrutura();

    }
    public function upEstrutura()
    {
        $this->execute(<<<SQL
            CREATE SEQUENCE homologacaoacordo_ac59_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            create table if not exists acordos.homologacaoacordo (
                ac59_sequencial SERIAL PRIMARY KEY,
                ac59_instituicao integer not null,
                ac59_automatica boolean,

                foreign key(ac59_instituicao) references configuracoes.db_config
            );

SQL
);
    }
    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010775, 'homologacaoacordo', 'Permissão Homologação Acordo', 'ac59', '2021-03-17', 'Permissão Homologação Acordo', 1, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (69,1010775);
            insert into db_syscampo values
                (1013058,'ac59_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
                (1013059,'ac59_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'),
                (1013060,'ac59_automatica','bool','Homologação Automatica','f', 'Homologação Automatica',1,'f','f','f',5,'text','Homologação Automatica');
            insert into db_sysarqcamp values
                (1010775,1013058,1,0),
                (1010775,1013059,2,0),
                (1010775,1013060,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010775,1013058,1,1013058);
            insert into db_sysforkey values(1010775,1013059,1,83,1);
            insert into db_syssequencia values(1000995, 'homologacaoacordo_ac59_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000995 where codarq = 1010775 and codcam = 1013058;

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228399 ,'Homologação' ,'con4_acordohomologacao001.php' ,'con4_acordohomologacao001.php' ,'1' ,'1' ,'Homologação Automática' ,'true' );delete from db_menu where id_item_filho = 228400 AND modulo = 8251;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228400 ,228399 ,1 ,8251 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228400 ,'Parâmetros' ,'Parâmetros' ,'' ,'1' ,'1' ,'Parâmetros' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228400 ,521 ,8251 );
SQL
);
    }
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();

    }
    public function downEstrutura()
    {
        $this->execute(<<<SQL
            drop table acordos.homologacaoacordo;
            drop sequence if exists homologacaoacordo_ac59_sequencial_seq;
SQL
);
    }
    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysarqmod where codarq = 1010775;
            delete from db_sysarqcamp where codarq = 1010775;
            delete from db_sysprikey where codarq = 1010775;
            delete from db_sysforkey where codarq = 1010775 and referen = 83;
            delete from db_sysarquivo where codarq = 1010775;
            delete from db_syssequencia where codsequencia = 1000995;
            delete from db_syscampo where codcam in(1013058, 1013059, 1013060);
            delete from db_menu where id_item = 228400;
            delete from db_menu where id_item_filho = 228399;
            delete from db_menu where id_item_filho = 228400;
            delete from db_itensmenu where id_item = 228399;
            delete from db_itensmenu where id_item = 228400;
SQL
);
    }
}
