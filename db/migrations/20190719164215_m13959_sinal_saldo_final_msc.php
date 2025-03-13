<?php

use Classes\PostgresMigration;

class M13959SinalSaldoFinalMsc extends PostgresMigration
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
        $this->execute("insert into db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010599 ,'c133_natureza_final' ,'char(1)' ,'Natureza Final' ,'D' ,'Natureza Final' ,1 ,'false' ,'true' ,'false' ,0 ,'text' ,'Natureza Final' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010445 ,1010599 ,10 ,0 );");

        $this->execute("create index matriz_saldo_contabil_lancamentos_matriz_in on contabilidade.matriz_saldo_contabil_lancamentos(c133_matriz_saldo_contabil)");
        $this->execute("alter table contabilidade.matriz_saldo_contabil_lancamentos add c133_natureza_final char(1)");

    }

    public function down()
    {
        
        $this->execute("alter table contabilidade.matriz_saldo_contabil_lancamentos drop c133_natureza_final");
        $this->execute("delete from db_sysarqcamp where codcam = 1010599");
        $this->execute("delete from db_syscampo where codcam = 1010599");
    }

}
