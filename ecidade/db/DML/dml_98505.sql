update turmaacativ
   set ed267_i_censoativcompl = censoativcompl_correto
  from ( select ed267_i_codigo         as codigo,
                ed267_i_censoativcompl as censoativcompl_errado,
                case
                     when ed267_i_censoativcompl = 31008
                          then 39999
                     when ed267_i_censoativcompl = 61002
                          then 14104
                     when ed267_i_censoativcompl = 61003
                          then 14103
                     when ed267_i_censoativcompl = 61004
                          then 14102
                     when ed267_i_censoativcompl = 14108
                          then 14102
                     when ed267_i_censoativcompl = 61005
                          then 14999
                     when ed267_i_censoativcompl = 14106
                          then 14201
                     when ed267_i_censoativcompl = 16105
                          then 13105
                     when ed267_i_censoativcompl = 16104
                          then 16103
                 end as censoativcompl_correto
           from turmaacativ
                inner join turmaac    on ed268_i_codigo = ed267_i_turmaac
                inner join calendario on ed52_i_codigo  = ed268_i_calendario
          where ed267_i_censoativcompl in(31008, 61002, 61003, 61004, 61005, 14106, 16105, 16104, 14108)
            and ed52_i_ano = 2015 ) as turmaacativ_ajuste
 where turmaacativ.ed267_i_codigo = turmaacativ_ajuste.codigo;

delete from turmaacativ where ed267_i_censoativcompl = 81001;
delete from censoativcompl where ed133_i_codigo = 81001;