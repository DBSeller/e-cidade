<?php

use Classes\PostgresMigration;

class M10222MenuImportacaoExportacao extends PostgresMigration
{
    public function up()
    {
        $sSql = "
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10498 ,'Vinculação de Recursos' ,'Vincula o recurso ao código SICONFI' ,'con4_vincularecursosiconfi001.php' ,'1' ,'1' ,'Vincula o recurso ao código SICONFI' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10496 ,10498 ,2 ,209 );
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10497 ,'Processamento e Emissão' ,'Processamento e exportação da matriz de saldo contábil' ,'con4_matrizsaldocontabil001.php' ,'1' ,'1' ,'Processamento e exportação da matriz de saldo contábil' ,'false' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10496 ,10497 ,1 ,209 );
        ";
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
            delete from db_itensmenu where id_item = 10498;
            delete from db_menu where id_item_filho = 10498 AND modulo = 209;
            delete from db_menu where id_item_filho = 10497 AND modulo = 209;
            delete from db_itensmenu where id_item = 10497;
        ";
        $this->execute($sSql);
    }
}
