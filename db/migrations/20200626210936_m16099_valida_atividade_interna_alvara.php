<?php

use Classes\PostgresMigration;

class M16099ValidaAtividadeInternaAlvara extends PostgresMigration
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

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upDicionario()
    {
        $this->execute(
        <<<SQL
        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES (1011617, 'q07_val_ativ_int', 'varchar(50)', 'Se for Atividade Interna, recebe true caso contrário false', 'f', 'Atividade Interna', 50, 'f', 'f', 'f', 
        0, 'text', 'Atividade Interna');
        INSERT INTO db_sysarqcamp VALUES(67,1011617,12,0);
SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(
        <<<SQL
        ALTER TABLE tabativ ADD COLUMN q07_val_ativ_int VARCHAR(50);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(
        <<<SQL
        DELETE FROM db_sysarqcamp WHERE codcam = 1011617;
        DELETE FROM db_syscampo WHERE codcam = 1011617;
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(
        <<<SQL
        ALTER TABLE tabativ DROP COLUMN q07_val_ativ_int;
SQL
        );
    }    
}
