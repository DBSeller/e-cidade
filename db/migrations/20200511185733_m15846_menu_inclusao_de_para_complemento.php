<?php

use Classes\PostgresMigration;

class M15846MenuInclusaoDeParaComplemento extends PostgresMigration
{
    public function up()
    {
$this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 228252 ,'Vincular Complemento do Recurso' ,'vincular complemento recurso' ,'con4_sigapvincularcomplemento.php' ,'1' ,'1' ,'vincular complemento recurso' ,'true' );

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8467 ,228252 ,4 ,209 );
SQL
);

    }

    public function down()
    {

$this->execute(<<<SQL
        delete from db_menu where id_item_filho = 228252 and modulo = 209;
        delete from db_itensmenu where id_item  = 228252;
SQL
        );

    }
}
