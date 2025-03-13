<?php

use Classes\PostgresMigration;

class M19150MenusDeConsultasContabeis extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228578 ,'Saldos das Contas de Disponibilidade de Recursos' ,'Saldos das Contas de Disponibilidade de Recursos' ,'con3_saldocontasdisponibilidade001.php' ,'1' ,'1' ,'Saldos das Contas de Disponibilidade de Recursos' ,'true' );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228579 ,'Conferência por Recurso DDR' ,'Conferência por Recurso DDR' ,'con3_conferenciaporrecurso001.php' ,'1' ,'1' ,'Conferência por Recurso DDR' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3333 ,228578 ,26 ,209 );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3333 ,228579 ,27 ,209 );



SQL;
        $this->execute($sql);
    }




    public function down()
    {
        $sql = <<<SQL

delete from db_menu where id_item_filho in(228578, 228579) AND modulo = 209;
delete from db_itensmenu where id_item in (228578, 228579);

SQL;
        $this->execute($sql);
    }
}
