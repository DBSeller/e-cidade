<?php

use Classes\PostgresMigration;

class M17413AlteraValorDefaultCamposTabelaArretipo extends PostgresMigration
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
        $this->execute(<<<SQL
            update db_syscampo set nomecam = 'k00_liberacarnesis', conteudo = 'bool', descricao = 'Emitir carnê banco vencidos no sistema', valorinicial = 't', rotulo = 'Emitir carnê banco vencidos no sistema', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'Emitir carnê banco vencidos no sistema' where codcam = 1012018;
            update db_syscampo set nomecam = 'k00_liberacarnepref', conteudo = 'bool', descricao = 'Emitir carnê banco vencidos no DBPref', valorinicial = 't', rotulo = 'Emitir carnê banco vencidos no DBPref', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'Emitir carnê banco vencidos no DBPref' where codcam = 1012019;
            update db_syscampodef set defcampo = 'true' where codcam = 1012018;
            update db_syscampodef set defcampo = 'true' where codcam = 1012019;
SQL
);
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            ALTER TABLE caixa.arretipo 
                ALTER COLUMN k00_liberacarnesis SET DEFAULT TRUE,
                ALTER COLUMN k00_liberacarnepref SET DEFAULT TRUE;
SQL
);
    }

    public function downDicionario() {
        $this->execute(<<<SQL
            update db_syscampo set nomecam = 'k00_liberacarnesis', conteudo = 'bool', valorinicial = 'f', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text' where codcam = 1012018;
            update db_syscampo set nomecam = 'k00_liberacarnepref', conteudo = 'bool', valorinicial = 'f', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text' where codcam = 1012019;
            update db_syscampodef set defcampo = 'false' where codcam = 1012018;
            update db_syscampodef set defcampo = 'false' where codcam = 1012019;
SQL
);
    }
    public function downEstrutura() {

    }

}
