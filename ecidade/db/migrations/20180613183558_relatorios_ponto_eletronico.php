<?php

use Classes\PostgresMigration;

class RelatoriosPontoEletronico extends PostgresMigration
{
    public function up()
    {
        $this->execute(
          <<<SQL_UP
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
     values ( 10526 ,'Apuração do Colaborador' ,'Apuração do Colaborador' ,'rec2_apuracaocolaborador001.php' ,'1' ,'1' ,'Relatório para apuração das faltas, horas trabalhadas, atrasos e saídas antecipadas do servidor.' ,'true' ),
            ( 10527 ,'Funcionários Por Escala de Horário' ,'Funcionários Por Escala de Horário' ,'rec2_funcionariosescalahorario001.php' ,'1' ,'1' ,'Relatório de funcionários por escala de horário do ponto eletrônico.' ,'true') ,
            ( 10531 ,'Absenteísmo' ,'Absenteísmo' ,'rec2_absenteismo001.php' ,'1' ,'1' ,'Relatório de absenteísmo dos servidores do ponto eletrônico.' ,'true' );
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) 
     values ( 10388 ,10526 ,2 ,2323 ),
            ( 10388 ,10527 ,3 ,2323 ),
            ( 10388 ,10531 ,7 ,2323 );
SQL_UP
        );
    }

    public function down()
    {
        $this->execute(
          <<<SQL_DOWN
delete from db_menu where id_item_filho in(10526, 10527, 10531) AND modulo = 2323;
delete from db_itensmenu where id_item in(10526, 10527, 10531);
SQL_DOWN
        );
    }
}
