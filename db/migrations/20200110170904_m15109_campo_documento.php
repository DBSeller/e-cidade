<?php

use Classes\PostgresMigration;

class M15109CampoDocumento extends PostgresMigration
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

        $this->execute("insert into db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010904 ,'c42_coddoc' ,'int4' ,'Evento Contábil' ,'null' ,'Evento Contábil' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Evento Contábil' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1853 ,1010904 ,8 ,0 );");
        $this->execute("alter table conencerramento add c42_coddoc integer");
    }

    public function down()
    {
        $this->execute("alter table conencerramento drop c42_coddoc");
        $this->execute("delete from db_sysarqcamp where codcam = 1010904");
        $this->execute("delete from db_syscampo where codcam = 1010904");

    }
}
