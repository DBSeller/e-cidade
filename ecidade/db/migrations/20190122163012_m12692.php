<?php

use Classes\PostgresMigration;

class M12692 extends PostgresMigration
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
        $this->addDicionarioDados();
        $this->adicionarCampos();
    }

    private function addDicionarioDados()
    {
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010006 ,'v57_matriculaadvogado' ,'varchar(20)' ,'Matrícula do Advogado' ,'' ,'Matrícula do Advogado' ,20 ,'true' ,'true' ,'false' ,0 ,'text' ,'Matrícula do Advogado' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 94 ,1010006 ,3 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010007 ,'v40_tipo' ,'int4' ,'Tipo' ,'' ,'Tipo' ,1 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo');");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010290 ,1010007 ,6 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010008 ,'v38_datacalculo' ,'date' ,'Data de Cálculo' ,'' ,'Data de Cálculo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data de Cálculo' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010286 ,1010008 ,6 ,0 );");

    }

    private function adicionarCampos()
    {
        $this->execute("alter table advog add v57_matriculaadvogado varchar(20)");
        $this->execute("alter table integracaoprocessoeletronicoarquivo add v40_tipo integer");
        $this->execute("alter table integracaoprocessoeletronico add v38_datacalculo date");
    }

    public  function down()
    {
        $this->execute("delete from db_sysarqcamp where codcam in(1010006,1010007, 1010008)");
        $this->execute("delete from db_syscampo where codcam in(1010006,1010007, 1010008)");
        $this->execute("alter table advog drop v57_matriculaadvogado");
        $this->execute("alter table integracaoprocessoeletronicoarquivo drop v40_tipo");
        $this->execute("alter table integracaoprocessoeletronico drop v38_datacalculo");
    }

}
