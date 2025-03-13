<?php

use Classes\PostgresMigration;

class M17965AjusteEmTamanhoDeColunasNaTabelaCgsUnd extends PostgresMigration
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
                UPDATE db_syscampo SET nomecam = 'z01_v_ender', conteudo = 'varchar(60)', descricao = 'Endereço', valorinicial = '', rotulo = 'Endereço', nulo = 'f', tamanho = 60, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Endereço' WHERE codcam = 1008846;
                UPDATE db_syscampo SET nomecam = 'z01_v_compl', conteudo = 'varchar(40)', descricao = 'Complemento', valorinicial = '', rotulo = 'Complemento', nulo = 't', tamanho = 40, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Complemento' WHERE codcam = 1008848;
                UPDATE db_syscampo SET nomecam = 'z01_v_bairro', conteudo = 'varchar(60)', descricao = 'Bairro', valorinicial = '', rotulo = 'Bairro', nulo = 'f', tamanho = 60, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Bairro' WHERE codcam = 1008849;

                ALTER TABLE cgs_und ALTER COLUMN z01_v_ender TYPE VARCHAR(60);
                ALTER TABLE cgs_und ALTER COLUMN z01_v_compl TYPE VARCHAR(40);
                ALTER TABLE cgs_und ALTER COLUMN z01_v_bairro TYPE VARCHAR(60);

SQL
        );
    }

    public function down()
    {
        $this->execute(
            <<<SQL
                UPDATE db_syscampo SET nomecam = 'z01_v_ender', conteudo = 'varchar(40)', descricao = 'Endereço', valorinicial = '', rotulo = 'Endereço', nulo = 'f', tamanho = 60, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Endereço' WHERE codcam = 1008846;
                UPDATE db_syscampo SET nomecam = 'z01_v_compl', conteudo = 'varchar(20)', descricao = 'Complemento', valorinicial = '', rotulo = 'Complemento', nulo = 't', tamanho = 40, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Complemento' WHERE codcam = 1008848;
                UPDATE db_syscampo SET nomecam = 'z01_v_bairro', conteudo = 'varchar(40)', descricao = 'Bairro', valorinicial = '', rotulo = 'Bairro', nulo = 'f', tamanho = 60, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Bairro' WHERE codcam = 1008849;

                ALTER TABLE cgs_und ALTER COLUMN z01_v_ender TYPE VARCHAR(40);
                ALTER TABLE cgs_und ALTER COLUMN z01_v_compl TYPE VARCHAR(20);
                ALTER TABLE cgs_und ALTER COLUMN z01_v_bairro TYPE VARCHAR(40);
SQL
        );
    }
}
