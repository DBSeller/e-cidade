<?php

use Classes\PostgresMigration;

class M16098ComplementoTaborc extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL
insert into db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1011631 ,'k02_complemento' ,'int4' ,'Complemento do Recurso' ,'0' ,'Complemento do Recurso' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Complemento do Recurso' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 78 ,1011631 ,5 ,0 );
alter table caixa.taborc add k02_complemento integer default 0;
SQL
        );
    }


    public function down()
    {
        $this->execute(<<<SQL
delete from configuracoes.db_sysarqcamp where codcam = 1011631;
delete from configuracoes.db_syscampo where codcam = 1011631;
alter table caixa.taborc drop k02_complemento;

SQL
        );
    }
}
