<?php

use Classes\PostgresMigration;

class M16054MenuParaManutencaoComplementoFonte extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL_UP

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228261 ,'Manutenção Complemento da Fonte' ,'Manutenção Complemento da Fonte' ,'con4_alteracaoorigemdespesa001.php' ,'1' ,'1' ,'Manutenção Complemento da Fonte' ,'true' );
delete from db_menu where id_item_filho = 228261 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4197 ,228261 ,19 ,209 );

SQL_UP
);
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN

delete from db_menu where id_item_filho = 228261;
delete from db_itensmenu where id_item = 228261;

SQL_DOWN
        );
    }

}
