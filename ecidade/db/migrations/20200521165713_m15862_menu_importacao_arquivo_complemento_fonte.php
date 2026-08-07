<?php

use Classes\PostgresMigration;

class M15862MenuImportacaoArquivoComplementoFonte extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL_UP

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228257 ,'Importação do Complemento da Fonte' ,'Importação do Complemento da Fonte' ,'con4_importacaoplanilhacomplemento001.php' ,'1' ,'1' ,'Importação do Complemento da Fonte' ,'true' );
delete from db_menu where id_item_filho = 228257 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4197 ,228257 ,18 ,209 );

SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from db_menu where id_item_filho = 228257 AND modulo = 209;
delete from db_itensmenu where id_item = 228257;

SQL_DOWN
);
    }
}
