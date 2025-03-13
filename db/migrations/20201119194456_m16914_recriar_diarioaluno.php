<?php

use Classes\PostgresMigration;

class M16914RecriarDiarioaluno extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
delete from diarioaluno
    where exists(select 1
                 from turma
                          join calendario ON calendario.ed52_i_codigo = turma.ed57_i_calendario
                 where calendario.ed52_i_ano < 2020
                   and turma.ed57_i_codigo = diarioaluno.ed161_turma);

drop table if exists w_alunos;

create temporary table w_alunos as
with alunos as (
    select ed95_i_aluno,
           ed59_i_turma,
           ed59_i_serie,
           array_agg(distinct ed95_c_encerrado)::text[] as situacao

    from diario
             join regencia ON regencia.ed59_i_codigo = diario.ed95_i_regencia
             join turma on ed57_i_codigo = ed59_i_turma
             join calendario ON ed52_i_codigo = ed57_i_calendario
    where ed52_i_ano < 2020
      and not exists(select 1
                     from diarioaluno
                     where ed161_aluno = ed95_i_aluno
                       and ed161_turma = ed59_i_turma
                       and ed161_serie = ed59_i_serie)
    group by ed95_i_aluno,
             ed59_i_turma,
             ed59_i_serie
),
     aluno_situacao_encerramento as (
         select ed95_i_aluno,
                ed59_i_turma,
                ed59_i_serie,
                case
                    when array ['N']::text[] <@ situacao
                        then false
                    else true
                    end as encerrado
         from alunos
     )
select nextval('diarioaluno_ed161_codigo_seq'), aluno_situacao_encerramento.*
from aluno_situacao_encerramento;

insert into diarioaluno
select *
from w_alunos;

insert into diarioalunoresultadofinal
select nextval('diarioalunoresultadofinal_ed165_codigo_seq'), nextval, null
from w_alunos;

with avaliacoes_alunos as (
    select w_alunos.nextval,
           w_alunos.ed95_i_aluno,
           w_alunos.ed59_i_turma,
           w_alunos.ed59_i_serie,
           array_agg(ed74_c_resultadofinal)::text[] as resultados
    from w_alunos
             join regencia ON regencia.ed59_i_turma = w_alunos.ed59_i_turma
        and regencia.ed59_i_serie = w_alunos.ed59_i_serie
             join diario ON diario.ed95_i_regencia = regencia.ed59_i_codigo
        and diario.ed95_i_aluno = w_alunos.ed95_i_aluno
             join diariofinal ON diariofinal.ed74_i_diario = diario.ed95_i_codigo
    group by w_alunos.nextval, w_alunos.ed95_i_aluno, w_alunos.ed59_i_turma, w_alunos.ed59_i_serie
),
     resultados as (
         select *,
                case
                    when array ['']::text[] <@ resultados
                        then null
                    when array ['R']::text[] <@ resultados
                        then 'R'
                    else 'A'
                    end as rf
         from avaliacoes_alunos
     )
update diarioalunoresultadofinal
set ed165_resultado_final = rf
from resultados
where ed165_diarioaluno = nextval;
SQL
        );
    }

    public function down()
    {
        return;
    }
}
