--
-- acerta dados da matricula mov
--
set client_encoding = latin1;

create temp table w_aluno_acertar_matricula_mov as
select x.aluno, x.matricula, x.situacao_matricula,
       x.proxima_matricula, matricula.ed60_c_situacao
  from (
        select ed60_i_codigo        as matricula,
               ed60_i_aluno         as aluno ,
               ed60_c_situacao      as situacao_matricula,
               ed60_d_datamatricula as data_matricula,
               (select ed60_i_codigo
                  from matricula as a
                 where a.ed60_i_aluno  = matricula.ed60_i_aluno
                   and a.ed60_i_codigo > matricula.ed60_i_codigo order by 1 limit 1) as proxima_matricula
          from matricula
         where matricula.ed60_c_situacao = 'TRANSFERIDO FORA'
       ) as x
 inner join matricula on matricula.ed60_i_codigo = x.proxima_matricula
 where x.proxima_matricula is not null
 order by data_matricula, aluno, x.matricula;

create temp table w_alteracao_movimentacao as
 select ed229_i_codigo,
        'MATRICULAR ALUNO'::varchar as novo_procedimento,
        'ALUNO MATRICULADO NA TURMA '|| trim(turma.ed57_c_descr) || ' SITUAÇÃO ANTERIOR: TRANSFERIDO FORA'::varchar as nova_situacao
  from w_aluno_acertar_matricula_mov
 inner join matriculamov on ed229_i_matricula = proxima_matricula
 inner join matricula    on ed60_i_codigo     = proxima_matricula
 inner join turma        on ed57_i_codigo     = ed60_i_turma
 where ed229_c_procedimento = 'REMATRICULAR ALUNO' ;

 update matriculamov
    set ed229_c_procedimento = novo_procedimento,
        ed229_t_descr = nova_situacao
   from w_alteracao_movimentacao
  where matriculamov.ed229_i_codigo = w_alteracao_movimentacao.ed229_i_codigo;
