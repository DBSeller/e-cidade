<?php

use Classes\PostgresMigration;

class M12227IdentificadorResultadoPrimario extends PostgresMigration
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

        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010125 ,'c60_identificadoresultadoprimario' ,'int4' ,'Identificador do Resultado Primário' ,'' ,'Identificador do Resultado Primário' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Identificador do Resultado Primário' );");
        $this->execute("insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 1010125 ,'0' ,'Não se Aplica' );");
        $this->execute("insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 1010125 ,'1' ,'Financeira' );");
        $this->execute("insert into db_syscampodef ( codcam ,defcampo ,defdescr ) values ( 1010125 ,'2' ,'Primária' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3268 ,1010125 ,12 ,0 );");

        $this->execute("alter table contabilidade.conplanoorcamento add c60_identificadoresultadoprimario integer default 0");
    }


    /**
     *
     */
    public function down()
    {
        $this->execute("delete from db_syscampodef where codcam = 1010125;");
        $this->execute("delete from db_sysarqcamp where codcam = 1010125");
        $this->execute("delete from db_syscampo where codcam = 1010125");
        $this->execute("alter table contabilidade.conplanoorcamento drop c60_identificadoresultadoprimario");
    }
}
