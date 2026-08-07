<?php

use Classes\PostgresMigration;

class M10655ReaberturaEventosPeriodicos extends PostgresMigration
{
    public function up()
    {
        $this->execute(
          <<<SQL_UP
insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) 
              values ( 3000044, 5 ,'S-1298 - Reabertura de Eventos Periódicos' ,'s1298-reabertura-de-eventos-periodicos' ,'Registros do evento S-1298 de Reabertura de Eventos Periódicos' ,'true' ,'' ,'true' );

insert into esocialformulariotipo values(34, 'S-1298 - Reabertura de Eventos Periódicos');
insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000044, 34);
SQL_UP
        );
    }

    public function down()
    {
        $this->execute(
        <<<SQL_DOWN
delete from esocialversaoformulario where rh211_esocialformulariotipo = 34;
delete from esocialformulariotipo where rh209_sequencial = 34;
delete from avaliacao where db101_sequencial = 3000044;
SQL_DOWN
        );
    }
}
