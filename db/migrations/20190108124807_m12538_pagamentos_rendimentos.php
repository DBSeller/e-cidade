<?php

use Classes\PostgresMigration;

class M12538PagamentosRendimentos extends PostgresMigration
{
    public function up()
    {
        $this->execute(
          <<<SQL_UP
insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) 
              values ( 3000041, 5 ,'S-1210 - Pagamentos de Rendimentos do Trabalho' ,'s1210-pagamentos-de-rendimentos-do-trabalho' ,'Registros do evento S-1210 de Pagamentos de Rendimentos do Trabalho' ,'true' ,'' ,'true' );

insert into esocialformulariotipo values(28, 'S-1210 - Pagamentos de Rendimentos do Trabalho');
insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000041, 28);
SQL_UP
        );
    }

    public function down()
    {
        $this->execute(
        <<<SQL_DOWN
delete from esocialversaoformulario where rh211_esocialformulariotipo = 28;
delete from esocialformulariotipo where rh209_sequencial = 28;
delete from avaliacao where db101_sequencial = 3000041;
SQL_DOWN
        );
    }
}
