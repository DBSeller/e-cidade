<?php

use Classes\PostgresMigration;

class M12001ReceitaRetencao extends PostgresMigration
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
         $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010031 ,'k107_retencao' ,'int4' ,'Retenção vinculada ao slip' ,'null' ,'Retenção' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Retenção' );");
         $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 2174 ,1010031 ,7 ,0 );");
         $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010032 ,'e21_receitaenterecebedor' ,'int4' ,'Receita do ente Recebedor' ,'null' ,'Receita do ente Recebedor' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Receita do ente Recebedor' );");
         $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 2112 ,1010032 ,9 ,0 );");
         $this->execute("alter table caixa.empagemovslips add k107_retencao integer");
         $this->execute("alter table empenho.retencaotiporec add e21_receitaenterecebedor integer ");
    }

    /**
     * 
     */
    public function down()
    {
        $this->execute("delete from db_sysarqcamp where codcam in(1010031, 1010032)");
        $this->execute("delete from db_syscampo where codcam in(1010031, 1010032)");
        $this->execute("alter table caixa.empagemovslips drop k107_retencao;");
        $this->execute("alter table empenho.retencaotiporec drop e21_receitaenterecebedor ");
    }
}
