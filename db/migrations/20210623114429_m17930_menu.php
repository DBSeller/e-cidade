<?php

use Classes\PostgresMigration;

class M17930Menu extends PostgresMigration
{
  
    public function up()
    {
$this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
values (228524 ,'Dem II - Avaliação das Metas Fiscais do Exercício Anterior',
    'Dem II - Avaliação das Metas Fiscais do Exercício Anterior' ,'pla2_abas_anexos_metas_riscos.php?tipo=LDO&anexo=2' ,'1' ,'1',
    'Dem II - Avaliação das Metas Fiscais do Exercício Anterior' ,'true');
insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (228507, 228524, 2, 228358);
SQL
        );
    }

     public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228524 AND modulo = 228358;
delete from db_itensmenu where id_item = 228524;
SQL
        );
    }

}
