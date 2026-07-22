<?php

use Classes\PostgresMigration;

class M14672ConsultaStatusMaterialExame extends PostgresMigration
{
    public function up()
    {
        $sql = '
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228187 ,\'Status do Material de Exames\' ,\'Consulta status do material pelo código da requisição ou código de barras.\' ,\'lab3_statusmaterialexame.php\' ,\'1\' ,\'1\' ,\'Rotina de consulta de status do material de exames. É possível consultar informando o código da requisição ou utilizando um leitor de código de barras, para realizar a leitura do código de materiais coletados para exames.\' ,\'false\' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3491 ,228187 ,3 ,8167 );
        ';

        $this->execute($sql);
    }

    public function down()
    {
        $sql = '
            delete from db_menu where id_item_filho = 228187;
            delete from db_itensmenu where id_item = 228187;
        ';

        $this->execute($sql);
    }
}
