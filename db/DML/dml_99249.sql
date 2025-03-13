create table w_acerto_matriculaserie_4620 as
      select nextval('matriculaserie_ed221_i_codigo_seq') as sequencial,
             matricula.ed60_i_codigo                      as matricula,
             serieregimemat.ed223_i_serie                 as serie,
             'S'                                          as origem,
             turma.ed57_i_calendario                      as calendario,
             matricula.ed60_i_aluno                       as aluno
        from matricula
             left  join matriculaserie      on matriculaserie.ed221_i_matricula  = matricula.ed60_i_codigo
             inner join turma               on turma.ed57_i_codigo               = matricula.ed60_i_turma
             inner join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo
             inner join serieregimemat      on serieregimemat.ed223_i_codigo     = turmaserieregimemat.ed220_i_serieregimemat
       where ed221_i_codigo is null;

create table w_diarios_exclusao_4620 as
      select diario.*
        from diario
             inner join w_acerto_matriculaserie_4620 on w_acerto_matriculaserie_4620.aluno      = diario.ed95_i_aluno
                                                    and w_acerto_matriculaserie_4620.serie      = diario.ed95_i_serie
                                                    and w_acerto_matriculaserie_4620.calendario = diario.ed95_i_calendario
                                                    and diario.ed95_c_encerrado = 'N'
       where not exists( select 1
                           from diarioavaliacao
                          where ed72_i_diario = ed95_i_codigo
                            and (    ed72_i_valornota     is not null
                                  or ed72_c_valorconceito <> ''
                                  or ed72_t_parecer       <> '' ) )
         and not exists( select 1
                           from diarioresultado
                          where ed73_i_diario = ed95_i_codigo
                            and (    ed73_i_valornota     is not null
                                  or ed73_c_valorconceito <> ''
                                  or ed73_t_parecer       <> '' ) )
         and not exists( select 1
                           from diariofinal
                          where ed74_i_diario = ed95_i_codigo
                            and (    ed74_c_valoraprov     <> ''
                                  or ed74_c_resultadoaprov <> ''
                                  or ed74_c_resultadofinal <> '' ) );

create table w_backup_diariofinal_4620 as
      select *
        from diariofinal
             inner join w_diarios_exclusao_4620 on w_diarios_exclusao_4620.ed95_i_codigo = diariofinal.ed74_i_diario;

create table w_backup_diarioresultado_4620 as
      select *
        from diarioresultado
             inner join w_diarios_exclusao_4620 on w_diarios_exclusao_4620.ed95_i_codigo = diarioresultado.ed73_i_diario;

create table w_backup_diarioavaliacao_4620 as
      select *
        from diarioavaliacao
             inner join w_diarios_exclusao_4620 on w_diarios_exclusao_4620.ed95_i_codigo = diarioavaliacao.ed72_i_diario;

delete from diariofinal
      using w_diarios_exclusao_4620
      where w_diarios_exclusao_4620.ed95_i_codigo = diariofinal.ed74_i_diario;

delete from diarioresultado
      using w_diarios_exclusao_4620
      where w_diarios_exclusao_4620.ed95_i_codigo = diarioresultado.ed73_i_diario;

delete from diarioavaliacao
      using w_diarios_exclusao_4620
      where w_diarios_exclusao_4620.ed95_i_codigo = diarioavaliacao.ed72_i_diario;

delete from diario
      using w_diarios_exclusao_4620
      where w_diarios_exclusao_4620.ed95_i_codigo = diario.ed95_i_codigo;

insert into matriculaserie
     select sequencial, matricula, serie, origem
       from w_acerto_matriculaserie_4620;