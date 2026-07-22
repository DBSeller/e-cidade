<?php

use Classes\PostgresMigration;

class M18785AtualizaEstruturaObrasAlvaraHabitese extends PostgresMigration
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
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
        insert into db_syscampo values(1013385,'ob09_alvara','int4','Chave Estrangeira de alvará, referencia tabela obrasalvara','0', 'Alvará',10,'f','f','f',1,'text','Alvará');
        insert into db_sysarqcamp values(954,1013385,19,0);

SQL
        );
    }

    public function upEstrutura()
    {
        // Cria campo de alvará na tabela obrashabite
        $this->execute(<<<SQL
        ALTER TABLE projetos.obrashabite ADD COLUMN ob09_alvara INTEGER; 

SQL
        );

        // Atualiza novo campo ob09_alvara da tabela obrashabite com valores da tabela obrasalvara
        $this->execute(<<<SQL
        UPDATE obrashabite a
        SET
            ob09_alvara = obrasalvara.ob04_alvara
        FROM
            obras
            INNER JOIN obrasalvara ON ob04_codobra = ob01_codobra
            INNER JOIN obrasconstr ON ob08_codobra = ob01_codobra
            INNER JOIN obrashabite b ON ob09_codconstr = ob08_codconstr
        WHERE a.ob09_codhab = b.ob09_codhab;

SQL
        );
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
        delete from db_sysarqcamp where codcam = 1013385;
        delete from db_syscampodep where codcam = 1013385;
        delete from db_syscampodef where codcam = 1013385;
        delete from db_syscampo where codcam = 1013385;

SQL
        );

    }

    public function downEstrutura()
    {
        // Remove campo de alvará da tabela obrashabite
        $this->execute(<<<SQL
        ALTER TABLE projetos.obrashabite DROP COLUMN ob09_alvara;

SQL
        );
    }
}
