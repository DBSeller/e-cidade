<?php

use Classes\PostgresMigration;

class M12588ObrasAlvara extends PostgresMigration
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
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downEstrutura();
    }

    public function upDicionarioDados()
    {
        $this->execute(<<<SQL
           insert into db_syscampo values(1010575,'ob04_classe','varchar(30)','Classe','', 'Classe',30,'t','t','f',0,'text','Classe');
           insert into db_syscampo values(1010576,'ob04_ativo','bool','Ativo','1', 'Ativo',1,'t','f','f',5,'text','Ativo');
           insert into db_syscampo values(1010577,'ob04_datacancelamentoreativacao','date','Data de Cancelamento Ativação','null', 'Data de Cancelamento Ativação',10,'t','f','f',1,'text','Data de Cancelamento Ativação');

           insert into db_sysarqcamp values(949,1010575,10,0);
           insert into db_sysarqcamp values(949,1010576,11,0);
           insert into db_sysarqcamp values(949,1010577,12,0);

SQL
);
    }

    public function downDicionarioDados()
    {
        $this->execute(<<<SQL

            delete from db_sysarqcamp where codarq = 949 and codcam in (1010575,1010576,1010577);
            delete from db_syscampo where codcam in (1010575,1010576,1010577);

SQL
);
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            ALTER TABLE obrasalvara
                ADD COLUMN ob04_classe character varying(30),
                ADD COLUMN ob04_ativo boolean default 't',
                ADD COLUMN ob04_datacancelamentoreativacao date;

SQL
);
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            ALTER TABLE obrasalvara 
                DROP COLUMN ob04_classe,
                DROP COLUMN ob04_ativo,
                DROP COLUMN ob04_datacancelamentoreativacao;

SQL
);
    }

}
