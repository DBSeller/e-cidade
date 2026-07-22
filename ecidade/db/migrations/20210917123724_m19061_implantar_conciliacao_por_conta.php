<?php

use Classes\PostgresMigration;

class M19061ImplantarConciliacaoPorConta extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL

insert into db_itensmenu( id_item ,
                          descricao ,
                          help ,
                          funcao ,
                          itemativo ,
                          manutencao ,
                          desctec ,
                          libcliente ) values (
                          228576 ,
                          'Implantação de Conciliação por Conta' ,
                          'Criar Conciliação Por Conta' ,
                          'cai4_criaconciliacaoporconta001.php' ,
                          '1' ,
                          '1' ,
                          'Implantação de Conciliação por Conta' ,
                          'true' );

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228484 ,228576 ,7 ,39 );
update db_itensmenu set descricao = 'Implantação de Conciliação por Lote' where id_item = 228485;


SQL;

       $this->execute($sql);
    }


    public function down()
    {
        $sql = <<<SQL

update db_itensmenu set descricao = 'Implantação de Conciliação por Conta' where id_item = 228485;
delete from db_menu where id_item_filho = 228576;
delete from db_itensmenu where id_item = 228576;

SQL;

       $this->execute($sql);
    }

}
