
CREATE TEMP TABLE w_valor_real_diarioresultado as
  SELECT ed73_i_codigo, ed37_c_tipo, ed73_i_valornota
    FROM diarioresultado
   inner join diario         on diario.ed95_i_codigo         = diarioresultado.ed73_i_diario
   inner join regencia       on regencia.ed59_i_codigo       = diario.ed95_i_regencia
   inner join procedimento   on procedimento.ed40_i_codigo   = regencia.ed59_procedimento
   inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procedimento.ed40_i_formaavaliacao
   inner join turma          on turma.ed57_i_codigo          = regencia.ed59_i_turma
   inner join calendario     on calendario.ed52_i_codigo     = turma.ed57_i_calendario
   where formaavaliacao.ed37_c_tipo = 'NOTA'
     and ed73_valorreal is null
     and ed52_i_ano <= 2015;


UPDATE diarioresultado
   SET ed73_valorreal = w_valor_real_diarioresultado.ed73_i_valornota
  FROM w_valor_real_diarioresultado
 WHERE diarioresultado.ed73_i_codigo = w_valor_real_diarioresultado.ed73_i_codigo;
