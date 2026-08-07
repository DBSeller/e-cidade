<?php

use Classes\PostgresMigration;

class M16252AjustaDiasLetivos extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
begin;
drop table if exists turmas_incorretas;
drop table if exists horarios_turmas;
drop table if exists w_turmas;

create temporary table turmas_incorretas as (
    select distinct ed57_i_codigo as codigo_turma
    from regenciahorario
        join regencia ON regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia
        join turma ON turma.ed57_i_codigo = regencia.ed59_i_turma
    where not exists (
        select 1 from dialetivo where ed04_i_escola = ed57_i_escola
    and ed04_i_diasemana = 7
    and ed04_c_letivo = 'S'
        )
        and ed58_i_diasemana = 7
);
create temporary table horarios_turmas as (
    select ed59_i_turma as turma, *
    from regenciahorario
             join regencia ON regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia
    where exists(select 1 from turmas_incorretas where codigo_turma = ed59_i_turma)
);
create temporary table w_turmas as
with lista_dias as (
    select turma, array_agg(distinct ed58_i_diasemana)::integer[] as dias from horarios_turmas group by turma
), turmas_para_acerto as (
    select turma,
           case
               when array[2]::integer[] <@  dias
                   then false
               else true
               end as incorreta
           from lista_dias
) select * from turmas_para_acerto where incorreta = 'True';

update regenciahorario set ed58_i_diasemana = ed58_i_diasemana-1
    where ed58_i_codigo in (select regenciahorario.ed58_i_codigo
         from regenciahorario
              join regencia ON regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia
              join w_turmas on w_turmas.turma = ed59_i_turma);
SQL
        );
    }
}
