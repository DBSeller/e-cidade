<?php

use Classes\PostgresMigration;

class Acerto11178CgmTipoEmpresaDuplicado extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP
drop table if exists bkp_cgmtipoempresa_up;
create table bkp_cgmtipoempresa_up as select * from cgmtipoempresa;

drop table if exists bkp_cgmtipoempresa_excluir;
create table bkp_cgmtipoempresa_excluir as
      select *,
             (select z03_tipoempresa
                from cgmtipoempresa
               where z03_sequencial = sequencial) as tipoempresa
        from (select array_to_string(array_accum(z03_sequencial), ',') as todos_sequenciais,
                     max(z03_sequencial) as sequencial ,
                     z03_numcgm,
                     count(*)
                from cgmtipoempresa
               group by z03_numcgm having count(*) > 1 ) as x;
SQL_UP
);

        $todosRegistros = $this->fetchAll("select todos_sequenciais from bkp_cgmtipoempresa_excluir;");
        $listaExcluir = array();
        foreach ($todosRegistros as $dadosConsulta) {

            unset($dadosConsulta[0]);
            $dadosConsulta = (object)$dadosConsulta;
            $listaExcluir[] = $dadosConsulta->todos_sequenciais;
            $this->execute("delete from cgmtipoempresa where z03_sequencial in ( {$dadosConsulta->todos_sequenciais} )");
        }

        $this->execute("
            insert into cgmtipoempresa
                 select sequencial,
                        z03_numcgm,
                        tipoempresa
                   from bkp_cgmtipoempresa_excluir;
        ");
    }


    public function down()
    {

        $this->execute(<<<SQL_DOWN

create table bkp_cgmtipoempresa_down as select * from cgmtipoempresa;
truncate cgmtipoempresa;
insert into cgmtipoempresa select * from bkp_cgmtipoempresa_up;
SQL_DOWN
);

    }

}
