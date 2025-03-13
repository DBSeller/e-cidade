<?php

use Classes\PostgresMigration;

class M12001RetencoesEnteRecebedor extends PostgresMigration
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
      $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010015 ,'e21_enterecebedor' ,'int4' ,'Ente Recebedor da retenção' ,'null' ,'Ente Recebedor' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Ente Recebedor' );");
      $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 2112 ,1010015 ,8 ,0 );");
      $this->execute("insert into db_sysindices values(1008333,'retencaotiporec_enterecebedor_in',2112,'0');");
      $this->execute("insert into db_syscadind values(1008333,1010015,1);");
      $this->execute("alter table empenho.retencaotiporec add e21_enterecebedor integer");
      $this->execute("alter table empenho.retencaotiporec add constraint retencaotiporec_enterecebedor_fk foreign key (e21_enterecebedor) references db_config(codigo)");
      $this->execute("create index retencaotiporec_enterecebedor_in on empenho.retencaotiporec(e21_enterecebedor)");
    }

    public function down()
    {

        $this->execute("delete from db_sysarqcamp where codcam = 1010015");
        $this->execute("delete from db_syscadind  where codcam = 1010015");
        $this->execute("delete from db_sysindices where codind = 1008333");
        $this->execute("delete from db_syscampo  where codcam = 1010015");
        $this->execute("alter table empenho.retencaotiporec drop e21_enterecebedor");

    }
}
