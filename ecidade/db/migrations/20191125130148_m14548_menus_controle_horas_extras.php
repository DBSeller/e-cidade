<?php

use Classes\PostgresMigration;

class M14548MenusControleHorasExtras extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228175 ,'Controle de Rubricas' ,'Agrupa funções de controle de rubricas' ,'' ,'1' ,'1' ,'Agrupa funções de controle de rubricas' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 1818 ,228175 ,119 ,952 );
            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228176 ,'Parâmetros' ,'Configura os parâmetros a serem utilizados no controle de rubricas' ,'pes1_controle_rubricas_parametros.php' ,'1' ,'1' ,'Configura os parâmetros a serem utilizados no controle de rubricas' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228175 ,228176 ,1 ,952 );
            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228177 ,'Manutenção' ,'Manutenção das rubricas que possuem controle de rubricas' ,'pes1_controle_rubricas.php' ,'1' ,'1' ,'Manutenção das rubricas que possuem controle de rubricas' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228175 ,228177 ,2 ,952 );
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE FROM db_menu WHERE id_item_filho = 228175;
            DELETE FROM db_itensmenu WHERE id_item IN (228175, 228176, 228177);
            DELETE FROM db_menu WHERE id_item IN (228175, 228176, 228177);
        ";

        $this->execute($sql);
    }
}
