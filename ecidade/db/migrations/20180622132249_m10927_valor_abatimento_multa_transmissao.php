<?php

use Classes\PostgresMigration;

class M10927ValorAbatimentoMultaTransmissao extends PostgresMigration
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

        $this->execute(<<<SQL
        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009798 ,'e74_valorabatimento' ,'float4' ,'Abatimento' ,'0' ,'Abatimento' ,40 ,'true' ,'false' ,'false' ,4 ,'text' ,'Abatimento' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3595 ,1009798 ,11 ,0 );
        insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009799 ,'e74_valormulta' ,'float4' ,'Multa' ,'0' ,'Multa' ,40 ,'true' ,'false' ,'false' ,4 ,'text' ,'Multa' );
        insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3595 ,1009799 ,12 ,0 );

      alter table empagemovdetalhetransmissao add e74_valorabatimento numeric default 0;
      alter table empagemovdetalhetransmissao add e74_valormulta numeric default 0;
SQL
);

    }

    public function down()
    {
        $this->execute(
            <<<SQL
        delete from  db_sysarqcamp where codcam in (1009798,  1009799);
        delete from  db_syscampo where codcam in (1009798,  1009799);
        

        alter table 10927 drop e74_valorabatimento ;
        alter table empagemovdetalhetransmissao drop e74_valormulta ;
SQL
        );
    }
}
